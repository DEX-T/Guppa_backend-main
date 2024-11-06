<?php

 namespace App\Domain\DTOs\Request\Configuration;

use Ramsey\Uuid\Type\Integer;

class AbilityRequestDto
{
  
  private  string $ability;
  private int $role_id;
  private int $ability_id;

  public function __construct(string $ability, int $role_id, int $ability_id = 0) {
    $this->ability = $ability;
    $this->role_id = $role_id;
    $this->ability_id = $ability_id;
  }

  public function getAbility(){
    return $this->ability;
  }

  public function getAbilityId(){
    return $this->ability_id;
  }

  public function getRoleId() : int
  {
    return $this->role_id;
  }
 
   
}