<?php

namespace App\Domain\Entities;

use DateTime;
use App\Models\SubMiddleware;

class SubMiddlewareEntity
{
    private int $middleware_id;
    private  string $key;
    private string $value;
    private DateTime $create_at;
    private DateTime $update_at;
    private string $status;


    public function __construct(SubMiddleware $middleware) {
      $this->middleware_id = $middleware->id;
      $this->key = $middleware->key;
      $this->value = $middleware->value;
      $this->create_at = $middleware->created_at;
      $this->update_at = $middleware->updated_at;
      $this->status = $middleware->status;

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

  
}