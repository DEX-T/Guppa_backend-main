<?php

 namespace App\Domain\DTOs\Request\GuppaRoutes;


class GuppaRouteRequestDto
{
    public int $controller_id;
    public string $method;
    public string $action;
    public String $url;
    public String $name;
    public $route_id;

    public function __construct(int $controller_id, string $method, string $action, string $url, string $name, int $route_id = 0){
        $this->controller_id = $controller_id;
        $this->method = $method;
        $this->action = $action;
        $this->url = $url;
        $this->name = $name;
        $this->route_id = $route_id;
      
    }
}