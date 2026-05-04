<?php

namespace PhpMvc;

use PhpMvc\Http\Route;
use PhpMvc\Http\Request;
use PhpMvc\Http\Response;
use PhpMvc\Database\Managers\MySQLManager;

class Application
{
    protected Request $request;
    protected Response $response;
    protected Route $route;
    protected $db;

    public function __construct()
    {
        $this->request = new Request;
        $this->response = new Response;
        $this->route = new Route($this->request, $this->response);
        $this->db = new MySQLManager;
        $this->db->connect();
    }

    public function run()
    {
        $this->route->resolve();
    } 
    public function __get($name)
    {
        if(property_exists($this, $name)) {
            return $this->$name;
        }
    }
    protected function loadConfigurations()
    {
        foreach(scandir(config_path()) as $file) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            $filename = explode('.', $file)[0];

            yield $filename => require config_path() . $file;
        }

    }
}
