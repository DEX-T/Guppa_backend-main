<?php

namespace App\Domain\Interfaces\Skill;


interface ISkillService
{
    public function upsertSkill(array $data);
    public function getAllSkill();
    public function getSkill(int $catId);
    public function getAllSkills(int $category_id);
    public function deleteSkill(int $catId);
    public function activateSkill(int $catId);
    public function deactivateSkill(int $catId);
}
