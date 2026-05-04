<?php
namespace App\Controllers;

use App\Models\User;

class HomeController
{
    public function index()
    {
        // $users = User::with('posts')->find(1);
        // dd($users);
        return view('home');
    }
}