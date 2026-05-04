<?php

namespace App\Middlewares;

use PhpMvc\Http\Request;
use PhpMvc\Http\Response;

class GuestMiddleware
{
    public function handle(Request $request, \Closure $next)
    {
        if (!empty($_SESSION['user_id'])) {
            return (new Response())->redirect('/');
        }

        return $next();
    }
}
