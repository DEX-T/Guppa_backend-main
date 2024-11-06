<?php

namespace App\Domain\Interfaces\Notification;;

interface INotificationService
{
    public function getAllNotification();
    public function getNotificationById(int $id);
    public function readNotification(int $id);
}
