<?php

namespace App\Controllers;

use App\Services\UserService;
use PhpMvc\Http\Response;

class UserController
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $users = $this->userService->getAllUsers();
        return view('users.index', ['users' => $users]);
    }

    public function create()
    {
        return view('users.create');
    }

    public function store()
    {
        $this->userService->createUser(request()->all());
        
        return (new Response())->redirect('/users');
    }

    public function show($id)
    {
        $user = $this->userService->getUserById($id);
        return view('users.show', ['user' => $user]);
    }

    public function edit($id)
    {
        $user = $this->userService->getUserById($id);
        return view('users.edit', ['user' => $user]);
    }

    public function update($id)
    {
        $this->userService->updateUser($id, request()->all());
        
        return (new Response())->redirect('/users');
    }

    public function destroy($id)
    {
        $this->userService->deleteUser($id);
        return (new Response())->redirect('/users');
    }
}
