<?php

namespace App\Domain\Entities;

use DateTime;
use App\Models\Role;
use App\Models\Ability;

class AbilityEntity
{
    private int $role_id;
    private int $ability_id;
    private  string $ability;
    private  $create_at;
    private  $update_at;
    private string $status;
    private $role;

    public function __construct(Ability $ability) {
      $this->ability = $ability->ability;
      $this->role_id = $ability->role_id;
      $this->ability_id = $ability->id;
      $this->create_at = $ability->created_at;
      $this->update_at = $ability->updated_at;
      $this->status = $ability->status;
      $this->role = $this->getRoleName($ability->role_id);
    }

    public function getRole(){
      return $this->role;
    }
  
    public function getRoleName($id){
      $role = Role::findOrFail($id);
      return $role ? $role->role : null;
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