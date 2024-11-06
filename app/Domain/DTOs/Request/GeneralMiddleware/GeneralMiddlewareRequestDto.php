<?php

 namespace App\Domain\DTOs\Request\GeneralMiddleware;

class GeneralMiddlewareRequestDto
{
    public string $key;
    public string $value;
    public int $prefix_id;

    public int $middleware_id;




    public function __construct(string $key, string $value, int $prefix_id, int $middleware_id = 0) {
      $this->key = $key;
      $this->value = $value;
      $this->prefix_id =$prefix_id;
      $this->middleware_id = $middleware_id;


    }


}
