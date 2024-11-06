<?php

namespace App\Domain\Interfaces\Reports;

use Illuminate\Http\Request;

;

interface IReportService
{
    public function getJobsReport(Request $request);
    public function getAppliedJobsReport(Request $request);
    public function getContractsReport(Request $request);
    public function getTransactionReport(Request $request);
    public function getUsersReport(Request $request);
}
