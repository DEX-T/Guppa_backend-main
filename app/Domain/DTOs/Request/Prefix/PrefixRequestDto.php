<?php

 namespace App\Domain\DTOs\Request\Prefix;

class PrefixRequestDto
{
    public int $prefix_id;
    public  string $prefix;

   

    public function __construct(string $prefix, int $prefix_id = 0) {
      $this->prefix = $prefix;
      $this->prefix_id =$prefix_id;

    
    }


}