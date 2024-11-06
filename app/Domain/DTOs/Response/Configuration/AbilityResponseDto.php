<?php
 namespace App\Domain\DTOs\Response\Configuration;

use App\Domain\Entities\AbilityEntity;
use Illuminate\Database\Eloquent\Collection;
use OpenApi\Annotations\Get;

class AbilityResponseDto
{
    public int $id;
    public string $ability;
    public string $role_id;
    public  $create_at;
    public  $updated_at;
    public string $status;
    public $role;

    public function __construct(AbilityEntity $ability){
        $this->ability = $ability->getAbility();
        $this->role_id = $ability->getRoleId();
        $this->create_at = $ability->getCreatedAt();
        $this->updated_at = $ability->getUpdatedAt();
        $this->status = $ability->getStatus();
        $this->id = $ability->getAbilityId();
        $this->role = $ability->getRole();

       
    }

   
    public function toArray(): array
    {
        return [
            'ability_id' => $this->id,
            'ability' => $this->ability,
            'role_id' => $this->role_id,
            'created_at' => $this->create_at,
            'updated_at' => $this->updated_at,
            'status' => $this->status,
            'role' => $this->role
        ];
       
    }
   
}