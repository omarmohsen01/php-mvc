<?php 

namespace PhpMvc\Http;

use PhpMvc\View\View;

class Route
{
    protected static array $routes = [];
    protected static array $lastRoute = [];
    protected Request $request;
    protected Response $response;

    public static array $aliases = [
        'auth' => \App\Middlewares\AuthMiddleware::class,
        'guest' => \App\Middlewares\GuestMiddleware::class,
    ];

    public function __construct(Request $request = null, Response $response = null)
    {
        $this->request = $request ?: request();
        $this->response = $response ?: new Response();
    }

    public static function get($route, $action)
    {
        self::$routes['GET'][$route] = ['action' => $action, 'middlewares' => []];
        self::$lastRoute = ['method' => 'GET', 'path' => $route];
        return new static();
    }

    public static function post($route, $action)
    {
        self::$routes['POST'][$route] = ['action' => $action, 'middlewares' => []];
        self::$lastRoute = ['method' => 'POST', 'path' => $route];
        return new static();
    }

    public function middleware($middleware)
    {
        $method = self::$lastRoute['method'];
        $path = self::$lastRoute['path'];
        
        $middlewares = is_array($middleware) ? $middleware : [$middleware];

        foreach ($middlewares as $m) {
            if (array_key_exists($m, self::$aliases)) {
                $m = self::$aliases[$m];
            }
            self::$routes[$method][$path]['middlewares'][] = $m;
        }

        return $this;
    }

    public function resolve()
    {
        $path = $this->request->path();
        $method = $this->request->method();
        $routeData = self::$routes[$method][$path] ?? false;
        
        if ($routeData === false) {
            View::makeError('404');
            return;
        }

        $action = $routeData['action'];
        $middlewares = $routeData['middlewares'] ?? [];

        $actionFn = function() use ($action) {
            if (is_callable($action)) {
                return call_user_func_array($action, []);
            }
            if (is_array($action)) {
                return call_user_func_array([new $action[0], $action[1]], []);
            }
        };

        $middlewares = array_reverse($middlewares);
        $next = $actionFn;

        foreach ($middlewares as $middleware) {
            $next = function() use ($middleware, $next) {
                $instance = new $middleware();
                return $instance->handle($this->request, $next);
            };
        }

        return $next();
    }
}