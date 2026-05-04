<?php

namespace App\Controllers;

use App\Models\User;
use PhpMvc\Support\Hash;
use PhpMvc\Http\Response;

class AuthController
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function login()
    {
        $email = request()->get('email');
        $password = request()->get('password');

        $users = User::where(['email', '=', $email]);
        $user = $users[0] ?? null;

        if ($user && Hash::verify($password, $user->password)) {
            $_SESSION['user_id'] = $user->id;
            return (new Response())->redirect('/');
        }

        return back();
    }

    public function register()
    {
        $name = request()->get('name');
        $email = request()->get('email');
        $password = Hash::make(request()->get('password'));

        User::create([
            'name' => $name,
            'email' => $email,
            'password' => $password
        ]);

        $users = User::where(['email', '=', $email]);
        $user = $users[0] ?? null;
        if ($user) {
            $_SESSION['user_id'] = $user->id;
        }

        return (new Response())->redirect('/');
    }

    public function logout()
    {
        unset($_SESSION['user_id']);
        session_destroy();
        return (new Response())->redirect('/login');
    }
}
