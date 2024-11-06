<?php

namespace App\Domain\Interfaces\WhyChooseUs;

use Illuminate\Http\Request;

interface IWhyChoseUsService
{
    public function create(Request $request);
    public function GetAll();
    public function GetAllFE();
    public function getById(int $id);
    public function update(Request $request);
    public function delete(int $id);

    public function createCard(Request $request);
    public function GetAllCard();
    public function getCardById(int $id);
    public function updateCard(Request $request);
    public function deleteCard(int $id);
}
