<?php
namespace App\Domain\DTOs\Response\SubMiddlewares;

use App\Domain\Entities\SubMiddlewareEntity;
use DateTime;

class SubMiddlewareResponseDto
{
    public int $middleware_id;
    public  string $key;
    public string $value;
    public DateTime $create_at;
    public DateTime $update_at;
    public string $status;


    public function __construct(SubMiddlewareEntity $middleware) {
      $this->middleware_id = $middleware->getMiddlewareId();
      $this->key = $middleware->getKey();
      $this->value = $middleware->getValue();
      $this->create_at = $middleware->getCreatedAt();
      $this->update_at = $middleware->getUpdatedAt();
      $this->status = $middleware->getStatus();

    }


    public function toArray(){
        return [
            'middleware_id' => $this->middleware_id,
            'key' => $this->key,
            'value' => $this->value,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
            'status' => $this->status
            ];
    }

}