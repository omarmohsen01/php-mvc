<?php 

namespace PhpMvc\Http;

use PhpMvc\View\View;

class Route
{
    protected static array $routes = [];
    protected Request $request;
    protected Response $response;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public static function get($route, $action)
    {
        self::$routes['GET'][$route] = $action;
    }

    public static function post($route, $action)
    {
        self::$routes['POST'][$route] = $action;
    }

    public function resolve()
    {
        $path = $this->request->path();
        $method = $this->request->method();
        $action = self::$routes[$method][$path] ?? false;
        
        if ($action === false) {
            View::makeError('404');
            return;
        }

        //this for func return something 
        if(is_callable($action))
            call_user_func_array($action, []);
        
        //this for class and method
        if(is_array($action))
            call_user_func_array([new $action[0], $action[1]], []);
    }
}