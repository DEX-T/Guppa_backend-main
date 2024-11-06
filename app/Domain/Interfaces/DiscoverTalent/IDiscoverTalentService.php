<?php

namespace App\Domain\Interfaces\DiscoverTalent;

use Illuminate\Http\Request;

interface IDiscoverTalentService
{
    public function createDiscover(Request $request);
    public function GetAllDiscover();
    public function getDiscoverById(int $id);
    public function updateDiscover(Request $request);
    public function deleteDiscover(int $id);
    public function getDiscoverTalent();

    public function createDiscoverBackground(Request $request);
    public function GetAllDiscoverBackground();
    public function getDiscoverBackgroundById(int $id);
    public function updateDiscoverBackground(Request $request);
    public function deleteDiscoverBackground(int $id);
} 
