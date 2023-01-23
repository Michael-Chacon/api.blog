<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|string|email|unique:users|max:100',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create($request->all());

        return response($user, 200);
    }
}
