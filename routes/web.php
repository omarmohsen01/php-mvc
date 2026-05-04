<?php

use PhpMvc\Http\Route;
use App\Controllers\HomeController;
use App\Controllers\AuthController;

use App\Controllers\UserController;

Route::get("/", [HomeController::class, 'index'])->middleware(['auth']);

Route::get("/login", [AuthController::class, 'showLogin'])->middleware('guest');
Route::post("/login", [AuthController::class, 'login'])->middleware('guest');

Route::get("/register", [AuthController::class, 'showRegister'])->middleware('guest');
Route::post("/register", [AuthController::class, 'register'])->middleware('guest');

Route::post("/logout", [AuthController::class, 'logout'])->middleware('auth');

// User CRUD Routes
Route::get("/users", [UserController::class, 'index'])->middleware('auth');
Route::get("/users/create", [UserController::class, 'create'])->middleware('auth');
Route::post("/users", [UserController::class, 'store'])->middleware('auth');
Route::get("/users/{id}", [UserController::class, 'show'])->middleware('auth');
Route::get("/users/{id}/edit", [UserController::class, 'edit'])->middleware('auth');
Route::put("/users/{id}", [UserController::class, 'update'])->middleware('auth');
Route::delete("/users/{id}", [UserController::class, 'destroy'])->middleware('auth');