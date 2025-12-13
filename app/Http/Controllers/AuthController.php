<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use MongoDB\BSON\ObjectId;


class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name'=>'required|string',
            'email'=>'required|email|unique:users',
            'password'=>'required|min:6'
        ]);

        $user = User::create([
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password)
        ]);

        return response()->json(['message'=>'Реєстрація успішна', 'user'=>$user]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'=>'required|email',
            'password'=>'required'
        ]);

        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message'=>'Невірний email або пароль'], 401);
        }

        return response()->json(['message'=>'Вхід успішний', 'user'=>$user]);
    }

    public function updateAvatar(Request $request, $id)
    {
        $request->validate([
            'avatar' => 'required|image|max:5120', // макс 5MB
        ]);

        $user = User::find($id);
        if (!$user) return response()->json(['message' => 'Користувач не знайдений'], 404);

        // видаляємо стару аватарку
        if ($user->avatar) {
            $oldPath = str_replace('/storage/', '', $user->avatar);
            Storage::disk('public')->delete($oldPath);
        }

        // зберігаємо нову
        $file = $request->file('avatar');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('avatars', $filename, 'public');

        // записуємо шлях у БД
        $user->avatar = '/storage/' . $path;
        $user->save();

        return response()->json($user);
    }
}