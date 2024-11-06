<?php

 namespace App\Domain\DTOs\Response\GuppaControllers;

use App\Domain\Entities\ControllerEntity;

class ControllerResponseDto
{
    public int $controller_id;
    public int $prefix_id;
    public int $general_middleware_id;
    public String $controller;
    public $status;
    public $dateCreated;
    public $dateUpdated;
    public $routes;

    public function __construct(ControllerEntity $controllerEntity){
        $this->controller_id = $controllerEntity->getControllerId();
        $this->prefix_id = $controllerEntity->getPrefixId();
        $this->general_middleware_id = $controllerEntity->getGeneralMiddlewareId();
        $this->controller = $controllerEntity->getController();
        $this->status = $controllerEntity->getStatus();
        $this->dateCreated = $controllerEntity->getDateCreated();
        $this->dateUpdated = $controllerEntity->getDateUpdated();
        $this->routes = $controllerEntity->getRoutes();
    }



    public function toArray(){
        return [
            'controller_id' => $this->controller_id,
            'prefix_id' => $this->prefix_id,
            'general_middleware_id' => $this->general_middleware_id,
            'controller' => $this->controller,
            'status' => $this->status,
            'dateCreated' => $this->dateCreated,
            'dateUpdated' => $this->dateUpdated,
            'routes' => $this->routes
            
        ];
    }
}