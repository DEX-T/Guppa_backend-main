<?php

namespace App\Domain\Entities;

use App\Models\GuppaController;
use DateTime;

class ControllerEntity
{
    private int $controller_id;
    private int $prefix_id;
    private int $general_middleware_id;
    private String $controller;
    private String $status;
    private  $dateCreated;
    private  $dateUpdated;
    private $routes;

    public function __construct(GuppaController $controller){
        $this->controller_id = $controller->id;
        $this->prefix_id = $controller->prefix_id;
        $this->general_middleware_id = $controller->general_middleware_id;
        $this->controller = $controller->controller;
        $this->status = $controller->status;
        $this->dateCreated = $controller->created_at;
        $this->dateUpdated = $controller->updated_at;
        $this->routes = $controller->guppa_routes->map(function($route){
                                    return [
                                        'id' => $route->id,
                                        'route' => $route->route,
                                        'method' => $route->method,
                                        'status' => $route->status
                                      ];
                                    })->toArray();
    }

    public function getControllerId(){
        return $this->controller_id;
    }

    public function getPrefixId(){
        return $this->prefix_id;
    }

    //get general middleware id
    public function getGeneralMiddlewareId(){
        return $this->general_middleware_id;
    }

    //get controller
    public function getController(){
        return $this->controller;
    }

    //get status 
    public function getStatus(){
        return $this->status;
    }

    //get date created 
    public function getDateCreated(){
        return $this->dateCreated;
    }

    //get date updated 
    public function getDateUpdated(){
        return $this->dateUpdated;
    }

    //get routes
    public function getRoutes(){
        return $this->routes;
    }

   
}