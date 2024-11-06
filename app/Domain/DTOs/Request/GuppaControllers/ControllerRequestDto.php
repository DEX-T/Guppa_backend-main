<?php

 namespace App\Domain\DTOs\Request\GuppaControllers;

class ControllerRequestDto
{
    public int $controller_id;
    public int $prefix_id;
    public int $general_middleware_id;
    public String $controller;
  

    public function __construct(int $prefix_id, int $general_middleware_id, String $controller, int $controller_id = 0){
        $this->prefix_id = $prefix_id;
        $this->general_middleware_id = $general_middleware_id;
        $this->controller = $controller;
        $this->controller_id = $controller_id;
        
    }
}