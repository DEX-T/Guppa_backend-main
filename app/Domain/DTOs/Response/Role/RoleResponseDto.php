<?php

 namespace App\Domain\DTOs\Response\Role;

use App\Domain\Entities\RoleEntity;
use App\Models\Role;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Date;

class RoleResponseDto
{
    public string $role_id;
    public string $role;
    public string $status;
    public  $create_at;
    public  $updated_at;
    public $abilities;


    public function __construct(RoleEntity $role){
        $this->role_id = $role->getRoleId();
        $this->role = $role->getRole();
        $this->create_at = $role->getCreatedAt();
        $this->updated_at = $role->getUpdatedAt();
        $this->status = $role->getStatus();
        $this->abilities = $role->getAbilities();

    }

    public function toArray()
    {
            return [
                'role_id' => $this->role_id,
                'role' => $this->role,
                'created_at' => $this->create_at,
                'updated_at' => $this->updated_at,
                'status' => $this->status,
                'abilities' => $this->abilities,
                
             ];

    }
}
