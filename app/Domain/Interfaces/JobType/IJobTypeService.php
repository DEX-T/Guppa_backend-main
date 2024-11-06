<?php
namespace App\Domain\Interfaces\JobType;

use Illuminate\Http\Request;

interface IJobTypeService
{

    public function createJobType(Request $request);
    public function getAllJobType();
    public function getJobTypebyId(int $id);
    public function updateJobType(Request $request);
    public function deleteJobType(int $id);



}
