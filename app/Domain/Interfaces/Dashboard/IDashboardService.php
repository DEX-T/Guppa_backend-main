<?php

namespace App\Domain\Interfaces\Dashboard;

interface IDashboardService
{
    public function GetClientTables();
    public function ClientStatistics();
    public function GetAdminTables();
    public function GetAdminStatistics();
    public function GetCounters();
    public function GetLatestUsers();
    public function GetLatestSupportTickets();
}
