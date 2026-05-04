<?php 

namespace PhpMvc\Http;

class Response
{
    public function redirect($path)
    {
        $url = str_starts_with($path, 'http') ? $path : url($path);
        header("Location: {$url}");
        exit;
    }

    public function back()
    {
        $url = $_SERVER['HTTP_REFERER'] ?? '/';
        header("Location: {$url}");
        exit;
    }
}
