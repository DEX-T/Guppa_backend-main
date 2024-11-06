<?php

 namespace App\Domain\DTOs\Request\Role;

use App\Models\Role;

class RoleRequestDto
{
    private string $roles;
    private int $role_id;
    public function __construct(string $role, int $role_id = 0){
        $this->roles = $role;
        $this->role_id = $role_id;

    }

    public function getRoleId(): int
    {
        return $this->role_id;
    }
    public function getRole(): string
    {
        return $this->roles;
    }

}
