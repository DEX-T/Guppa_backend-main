<?php

namespace App\Domain\Entities;

use App\Models\GuppaRoute;
use DateTime;

class GuppaRouteEntity
{
    private int $controller_id;
    private string $method;
    private string $action;
    private string $url;
    private String $name;
    private String $status;
    private DateTime $dateCreated;
    private DateTime $dateUpdated;
    private $route_id;

    public function __construct(GuppaRoute $route){
        $this->route_id = $route->id;
        $this->controller_id = $route->guppa_controller_id;
        $this->method = $route->method;
        $this->action = $route->action;
        $this->url = $route->url;
        $this->name = $route->name;
        $this->status = $route->status;
        $this->dateCreated = $route->created_at;
        $this->dateUpdated = $route->updated_at;
      
    }

    public function getControllerId(){
        return $this->controller_id;
    }

    public function getMethod(){
        return $this->method;
    }

    //get action
    public function getAction(){
        return $this->action;
    }

    //get name
    public function getName(){
        return $this->name;
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

    //get route id
    public function getRouteId(){
        return $this->route_id;
    }

    //get url
    public function getUrl(){
        return $this->url;
    }
}