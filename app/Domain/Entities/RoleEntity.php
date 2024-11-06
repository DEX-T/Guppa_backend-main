<?php

namespace App\Domain\Entities;

use App\Models\Role;

class RoleEntity
{
    private string $role;
    private string $role_id;
    private  $create_at;
    private  $updated_at;
    private string $status;
    private  $abilities;

    public function __construct(Role $role){
        $this->role = $role->role;
        $this->role_id = $role->id;
        $this->create_at = $role->created_at;
        $this->updated_at = $role->updated_at;
        $this->status = $role->status;
        $this->abilities = $role->abilities != null ? $role->abilities->map(function ($ability) {
            return [
                'id' => $ability->id,
                'name' => $ability->ability
            ];
        })->toArray() : null;
      

     
    }

    public function getRole(){
        return $this->role;
    }

    public function getRoleId(){
        return $this->role_id;
    }

    public function getCreatedAt(){
        return $this->create_at;
    }

    public function getUpdatedAt(){
        return $this->updated_at;
    }

    public function getStatus(){
        return $this->status;
    }

    public function getAbilities(){
        return $this->abilities;
    }
    
}