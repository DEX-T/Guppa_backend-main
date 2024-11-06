<?php
namespace App\Domain\DTOs\Response\Prefix;

use App\Domain\Entities\PrefixEntity;
use DateTime;

class PrefixResponseDto
{
    public int $prefix_id;
    public  string $prefix;
    public DateTime $create_at;
    public DateTime $update_at;
    public string $status;
   

    public function __construct(PrefixEntity $prefixEntity) {
      $this->prefix = $prefixEntity->getPrefix();
      $this->prefix_id = $prefixEntity->getPrefixId();
      $this->create_at = $prefixEntity->getCreatedAt();
      $this->update_at = $prefixEntity->getUpdatedAt();
      $this->status = $prefixEntity->getStatus();
    }


    public function toArray(){
        return [
            'prefix_id' => $this->prefix_id,
            'prefix' => $this->prefix,
            'create_at' => $this->create_at,
            'update_at' => $this->update_at,
            'status' => $this->status
        ];
    }
  
}