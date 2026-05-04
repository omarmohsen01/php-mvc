<?php 

namespace PhpMvc\Http;

class Request
{
    public function method()
    {
        $method = strtoupper($_SERVER["REQUEST_METHOD"]);
        if ($method === 'POST' && isset($_POST['_method'])) {
            return strtoupper($_POST['_method']);
        }
        return $method;
    }

    public function path()
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');
        if ($position !== false) {
            $path = substr($path, 0, $position);
        }

        // Calculate the base path of the application
        $scriptName = $_SERVER['SCRIPT_NAME'];
        $basePath = str_replace('\\', '/', dirname($scriptName));
        
        // If the server routes through /public, we remove it from the base path comparison
        $basePath = str_replace('/public', '', $basePath);

        if ($basePath !== '/' && str_starts_with($path, $basePath)) {
            $path = substr($path, strlen($basePath));
        }

        return $path ?: '/';
    }

    public function all()
    {
        return array_merge($_GET, $_POST);
    }

    public function get($key, $default = null)
    {
        return $this->all()[$key] ?? $default;
    }

    public function only(array $keys)
    {
        return array_intersect_key($this->all(), array_flip($keys));
    }
}
