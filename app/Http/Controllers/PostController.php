<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Post;
class PostController extends Controller
{
    // Додати нову роботу
    public function store(Request $request)
    {

        $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'nullable|string',
            'user_id' => 'required',
            'image' => 'nullable|image|max:5120', // 5MB
        ]);

        $imageUrl = null;

         if ($request->hasFile('image')) {
            $path = $request->file('image')->store('uploads', 'public'); 
            $imageUrl = Storage::url($path); // /storage/uploads/xxx.jpg
        } 

        $post = Post::create([
            'user_id' => $request->user_id,
            'title' => $request->title,
            'description' => $request->description,
            'image_url' => $imageUrl,
            'likes' => [],
            'created_at' => now(),
        ]);

        return response()->json($post);
    }

    // Показати всі роботи
    public function index()
    {
        return Post::all();
    }

    // Лайк роботи
    public function like($id, $userId)
    {
        $post = Post::find($id);
        $likes = $post->likes ?? [];
        if (!in_array($userId, $likes)) {
            $likes[] = $userId;
        }
        $post->likes = $likes;
        $post->save();
        return response()->json($post);
    }
    // Редагувати роботу
    public function update(Request $request, $id)
    {
        $post = Post::find($id);
        if (!$post) {
            return response()->json(['message' => 'Робота не знайдена'], 404);
        }

        $request->validate([
            'title' => 'required|string|max:200',
            'description' => 'nullable|string',
        ]);

        $post->title = $request->title;
        $post->description = $request->description;
        $post->save();

        return response()->json($post);
    }

    // Видалити роботу
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
