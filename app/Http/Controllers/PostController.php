<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Post;
use App\Models\Comment;

class PostController extends Controller
{
    // Додати нову роботу
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:200',
            'description' => 'nullable|string',
            'user_id'     => 'required',
            'image'       => 'nullable|image|max:5120', // 5MB
        ]);

        $imageUrl = null;

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('uploads', 'public');
            $imageUrl = Storage::url($path); // /storage/uploads/xxx.jpg
        }

        $post = Post::create([
            'user_id'     => $request->user_id,
            'title'       => $request->title,
            'description' => $request->description,
            'image_url'   => $imageUrl,
            'likes'       => [],
            'created_at'  => now(),
        ]);

        return response()->json($post);
    }

    public function index()
    {
        $posts = Post::with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        $data = $posts->map(function ($post) {
            return [
                'id'          => (string) $post->_id,
                'title'       => $post->title,
                'description' => $post->description,
                'image_url'   => $post->image_url,
                'author'      => [
                    'id'   => (string) ($post->user->_id ?? ''),
                    'name' => $post->user->name ?? 'Анонім',
                ],
                'likes_count' => is_array($post->likes) ? count($post->likes) : 0,
            ];
        });

        return response()->json($data);
    }

    public function show(string $id)
    {
        $post = Post::with(['user', 'comments.user'])->find($id);

        if (!$post) {
            return response()->json(['message' => 'Робота не знайдена'], 404);
        }

        return response()->json([
            'id'          => (string) $post->_id,
            'title'       => $post->title,
            'description' => $post->description,
            'image_url'   => $post->image_url,
            'author'      => [
                'id'   => (string) ($post->user->_id ?? ''),
                'name' => $post->user->name ?? 'Анонім',
            ],
            'likes'       => [
                'count' => is_array($post->likes) ? count($post->likes) : 0,
            ],
            'comments'    => $post->comments->map(function ($comment) {
                return [
                    'id'      => (string) $comment->_id,
                    'text'    => $comment->text,
                    'author'  => $comment->user->name ?? 'Анонім',
                    'created' => $comment->created_at?->toDateTimeString(),
                ];
            }),
        ]);
    }

    public function toggleLike(Request $request, string $id)
    {
        $request->validate([
            'user_id' => 'required',
        ]);

        $post = Post::find($id);
        if (!$post) {
            return response()->json(['message' => 'Робота не знайдена'], 404);
        }

        $userId = (string) $request->user_id;
        $likes  = $post->likes ?? [];

        if (!is_array($likes)) {
            $likes = [];
        }

        if (in_array($userId, $likes)) {
            // забираємо лайк
            $likes = array_values(array_diff($likes, [$userId]));
            $liked = false;
        } else {
            // ставимо лайк
            $likes[] = $userId;
            $liked = true;
        }

        $post->likes = $likes;
        $post->save();

        return response()->json([
            'liked'       => $liked,
            'likes_count' => count($likes),
        ]);
    }

    // ДОДАТИ КОМЕНТАР
    public function addComment(Request $request, string $id)
    {
        $request->validate([
            'user_id' => 'required',
            'text'    => 'required|string|max:1000',
        ]);

        $post = Post::find($id);
        if (!$post) {
            return response()->json(['message' => 'Робота не знайдена'], 404);
        }

        $comment = Comment::create([
            'post_id' => $post->_id,
            'user_id' => $request->user_id,
            'text'    => $request->text,
        ]);

        $comment->load('user');

        return response()->json([
            'id'      => (string) $comment->_id,
            'text'    => $comment->text,
            'author'  => $comment->user->name ?? 'Анонім',
            'created' => $comment->created_at?->toDateTimeString(),
        ], 201);
    }

    // Редагувати роботу
    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['message' => 'Робота не знайдена'], 404);
        }

        $request->validate([
            'title'       => 'required|string|max:200',
            'description' => 'nullable|string',
        ]);

        $post->title       = $request->title;
        $post->description = $request->description;
        $post->save();

        return response()->json($post);
    }

    //  Видалити роботу
    public function destroy($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['message' => 'Робота не знайдена'], 404);
        }

        $post->delete();
        return response()->json(['message' => 'Робота видалена']);
    }
}
