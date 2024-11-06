<?php

 namespace App\Domain\DTOs\Response\GuppaRoutes;

use DateTime;
use App\Domain\Entities\GuppaRouteEntity;

class GuppaRouteResponseDto
{
    public int $controller_id;
    public string $method;
    public string $action;
    public string $url;
    public String $name;
    public String $status;
    public DateTime $dateCreated;
    public DateTime $dateUpdated;
    public $route_id;

    public function __construct(GuppaRouteEntity $guppaRouteEntity){
        $this->controller_id = $guppaRouteEntity->getControllerId();
        $this->route_id = $guppaRouteEntity->getRouteId();
        $this->method = $guppaRouteEntity->getMethod();
        $this->action = $guppaRouteEntity->getAction();
        $this->url = $guppaRouteEntity->getUrl();
        $this->name = $guppaRouteEntity->getName();
        $this->status = $guppaRouteEntity->getStatus();
        $this->dateCreated = $guppaRouteEntity->getDateCreated();
        $this->dateUpdated = $guppaRouteEntity->getDateUpdated();
      
    }


    public function toArray(){
        return [
            'route_id' => $this->route_id,
            'controller_id' => $this->controller_id,
            'method' => $this->method,
            'action' => $this->action,
            'url' => $this->url,
            'name' => $this->name,
            'status' => $this->status,
            'created_at' => $this->dateCreated,
            'updated_at' => $this->dateUpdated 
            ];
    }
}