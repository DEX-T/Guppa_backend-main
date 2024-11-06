<?php

namespace App\Domain\Interfaces\Monitor;

interface IMonitorService
{
    public function getAllApiUsage();
    public function getApiUsage(int $id);
    public function getAuditLogs();
    public function getAuditLog(int $id);
}
