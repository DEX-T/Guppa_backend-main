<?php
namespace App\Domain\DTOs\Response\GeneralMiddleware;

use App\Domain\Entities\GeneralMiddlewareEntity;
use DateTime;

class GeneralMiddlewareResponseDto
{
    public int $middleware_id;
    public  string $key;
    public string $value;
    public DateTime $create_at;
    public DateTime $update_at;
    public string $status;
    public int $prefix_id;

    public function __construct(GeneralMiddlewareEntity $generalMiddlewareEntity) {
      $this->key = $generalMiddlewareEntity->getKey();
      $this->value = $generalMiddlewareEntity->getValue();
      $this->middleware_id = $generalMiddlewareEntity->getMiddlewareId();
      $this->create_at = $generalMiddlewareEntity->getCreatedAt();
      $this->update_at = $generalMiddlewareEntity->getUpdatedAt();
      $this->status = $generalMiddlewareEntity->getStatus();
      $this->prefix_id = $generalMiddlewareEntity->getPrefixId();
    }


    public function toArray(){
        return [
            'middleware_id' => $this->middleware_id,
            'key' => $this->key,
            'value' => $this->value,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
            'status' => $this->status,
            "prefix_id" => $this->prefix_id
        ];
    }

}
