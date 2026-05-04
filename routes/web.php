<?php

use PhpMvc\Http\Route;
use App\Controllers\HomeController;
use App\Controllers\AuthController;

Route::get("/", [HomeController::class, 'index']);

Route::get("/login", [AuthController::class, 'showLogin']);
Route::post("/login", [AuthController::class, 'login']);

Route::get("/register", [AuthController::class, 'showRegister']);
Route::post("/register", [AuthController::class, 'register']);

Route::post("/logout", [AuthController::class, 'logout']);