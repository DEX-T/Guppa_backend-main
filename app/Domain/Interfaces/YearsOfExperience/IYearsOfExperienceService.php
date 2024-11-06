<?php
namespace App\Domain\Interfaces\YearsOfExperience;

use Illuminate\Http\Request;

interface IYearsOfExperienceService
{

    public function createYearsOfExperience(Request $request);
    public function getAllYearsOfExperience();
    public function getYearsOfExperiencebyId(int $id);
    public function updateYearsOfExperience(Request $request);
    public function deleteYearsOfExperience(int $id);



}
