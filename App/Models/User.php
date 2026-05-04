<?php

namespace App\Models;

class User extends Model
{
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}