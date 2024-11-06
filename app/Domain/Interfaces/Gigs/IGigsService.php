<?php
namespace App\Domain\Interfaces\Gigs;

use Illuminate\Http\Request;

interface IGigsService
{

    public function createGigs(Request $request);
    public function getAllGigs();
    public function getGigsbyId(int $id);
    public function updateGigs(Request $request);
    public function deleteGigs(int $id);



}
