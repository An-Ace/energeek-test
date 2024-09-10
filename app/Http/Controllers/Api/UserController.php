<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    // Mendapatkan semua pengguna
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    // Mendapatkan pengguna berdasarkan ID
    public function show($id)
    {
        $user = User::find($id);
        if ($user) {
            return response()->json($user);
        }
        return response()->json(['message' => 'User not found'], 404);
    }

    // Menambahkan pengguna baru
    public function store(Request $request)
    {
        $user = User::create($request->all());
        return response()->json($user, 201);
    }

    // Memperbarui pengguna
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if ($user) {
            $user->update($request->all());
            return response()->json($user);
        }
        return response()->json(['message' => 'User not found'], 404);
    }

    // Menghapus pengguna
    public function destroy($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return response()->json(['message' => 'User deleted successfully']);
        }
        return response()->json(['message' => 'User not found'], 404);
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json(['message' => 'User Not Found!', 'error' => true, 'redirect' => ''], 404);
        }
        Session::put('user', ["id" => $user->id, "name" => $user->name, "email" => $user->email, "username" => $user->username]);
        return response()->json([
            'message' => 'Login Success!',
            'error' => false, 'redirect' => '',
            "data" => ["id" => $user->id, "name" => $user->name, "email" => $user->email, "username" => $user->username],
        ], 200);
    }
    public function getMyTasks($email)
    {
        $user = User::with(['tasks' => function ($query) {
            $query->whereNull('deleted_at');
        }])->where('email', $email)->first();
        if (!$user) {
            return response()->json(['message' => 'User Not Found!', 'error' => true, 'redirect' => ''], 404);
        }
        return response()->json([
            'message' => '',
            'error' => false, 'redirect' => '',
            "data" => $user,
        ], 200);
    }
}
