<?php

namespace App\Domain\Interfaces\Analytics;

use Illuminate\Http\Request;

interface IAnalyticsService
{
    public function getUserGrowth();
     public function getUserDemographics();
     public function getBehaviorMetrics();
     public function updateTimeSpent(Request $request);
    public function getProjectStatus();
    public function getProjectTypesCount();
}
