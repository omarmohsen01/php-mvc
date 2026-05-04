<?php

use PhpMvc\Http\Route;
use App\Controllers\HomeController;
use App\Controllers\AuthController;

Route::get("/", [HomeController::class, 'index'])->middleware(['auth']);

Route::get("/login", [AuthController::class, 'showLogin'])->middleware('guest');
Route::post("/login", [AuthController::class, 'login'])->middleware('guest');

Route::get("/register", [AuthController::class, 'showRegister'])->middleware('guest');
Route::post("/register", [AuthController::class, 'register'])->middleware('guest');

Route::post("/logout", [AuthController::class, 'logout'])->middleware('auth');