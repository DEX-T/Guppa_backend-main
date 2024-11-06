<?php

namespace App\Services\Analytics;

use App\Domain\DTOs\ApiResponseDto;
use App\Domain\Interfaces\Analytics\IAnalyticsService;
use App\enums\HttpStatusCode;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnalyticsService implements IAnalyticsService
{
    protected ?\Illuminate\Contracts\Auth\Authenticatable $_currentUser;

    function __construct()
    {
        $this->_currentUser = Auth::user();
    }
    public function getUserGrowth(): \Illuminate\Support\Collection
    {
        return DB::table('users')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total_users'))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->get();
    }

    public function getUserDemographics(): \Illuminate\Support\Collection
    {
        return DB::table('users')
            ->select('age_group', DB::raw('COUNT(*) as user_count'))
            ->groupBy('age_group')
            ->get();
    }

    public function getBehaviorMetrics(): \Illuminate\Support\Collection
    {
        return DB::table('user_activity_logs')
            ->select('activity', DB::raw('AVG(time_spent) as avg_time_spent'))
            ->groupBy('activity')
            ->get();
    }

    public function updateTimeSpent(Request $request): ApiResponseDto
    {
        try {
            $validated = $request->validate([
                'time_spent' => 'required|numeric|min:0',
                'activity' => 'required|string',
            ]);

            $timeSpent = $validated['time_spent'];
            $activity = $validated['activity'];
            UserActivityLog::create([
                'user_id' => $this->_currentUser->id,
                'activity' => $activity,
                'time_spent' => $timeSpent,
                'created_at' => now(),
            ]);

            return new ApiResponseDto(true, "updated",HttpStatusCode::OK);
        }catch (\Exception $e){
            return new ApiResponseDto(false, "Server error ".$e->getMessage(), HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get the number and types of projects posted.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getProjectTypesCount(): \Illuminate\Support\Collection
    {
        return DB::table('guppa_jobs')
            ->select('project_type', DB::raw('COUNT(*) as count'))
            ->groupBy('project_type')
            ->get();
    }

    /**
     * Get project completion rates and client satisfaction.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getProjectStatus(): \Illuminate\Support\Collection
    {
        return DB::table('contracts')
            ->select(
                DB::raw('COUNT(*) as total_projects'),
                DB::raw('SUM(CASE WHEN status = "Done" THEN 1 ELSE 0 END) as done_projects'),
                DB::raw('SUM(CASE WHEN status = "Awaiting Review" THEN 1 ELSE 0 END) as awaiting_review_projects'),
                DB::raw('SUM(CASE WHEN status = "In Progress" THEN 1 ELSE 0 END) as in_progress_projects')
            )
            ->get();
    }


}
