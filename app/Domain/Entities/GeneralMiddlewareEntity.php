<?php

namespace App\Domain\Entities;

use App\Models\GeneralMiddleware;
use App\Models\Prefix;
use DateTime;

class GeneralMiddlewareEntity
{
    private int $middleware_id;
    private  string $key;
    private string $value;
    private DateTime $create_at;
    private DateTime $update_at;
    private string $status;
    private int $prefix_id;


    public function __construct(GeneralMiddleware $middleware) {
      $this->key = $middleware->key;
      $this->value = $middleware->value;
      $this->middleware_id = $middleware->id;
      $this->create_at = $middleware->created_at;
      $this->update_at = $middleware->updated_at;
      $this->status = $middleware->status;
      $this->prefix_id = $middleware->prefix_id;

    }

    public function getKey(){
      return $this->key;
    }

    public function getValue(){
      return $this->value;
    }

    public function getMiddlewareId(){
      return $this->middleware_id;
    }

    public function getStatus(){
        return $this->status;
    }

    public function getCreatedAt(){
        return $this->create_at;
    }

    public function getUpdatedAt(){
        return $this->update_at;
    }

    public function getPrefixId(){
      return $this->prefix_id;
    }

    // Define your entity properties and methods here
}
