<?php

namespace App\Controllers;

use App\Models\User;
use PhpMvc\Http\Response;
use PhpMvc\Support\Hash;

class UserController
{
    public function index()
    {
        $users = User::all();
        return view('users.index', ['users' => $users]);
    }

    public function create()
    {
        return view('users.create');
    }

    public function store()
    {
        User::create([
            'name' => request()->get('name'),
            'email' => request()->get('email'),
            'password' => Hash::make(request()->get('password'))
        ]);
        
        return (new Response())->redirect('/users');
    }

    public function show($id)
    {
        $user = User::find($id);
        return view('users.show', ['user' => $user]);
    }

    public function edit($id)
    {
        $user = User::find($id);
        return view('users.edit', ['user' => $user]);
    }

    public function update($id)
    {
        $data = [
            'name' => request()->get('name'),
            'email' => request()->get('email')
        ];
        
        if (request()->get('password')) {
            $data['password'] = Hash::make(request()->get('password'));
        }

        User::update($id, $data);
        return (new Response())->redirect('/users');
    }

    public function destroy($id)
    {
        User::delete($id);
        return (new Response())->redirect('/users');
    }
}
