<?php

namespace App\Http\Controllers\AdminControllers;

use App\Http\Controllers\Controller;
use App\Models\Admin\AssessmentIntro\AssessmentIntro;

class SummaryReportController extends Controller
{
    //
    protected $assessmentIntro = null;

    public function __construct(AssessmentIntro $assessmentIntro)
    {
        $this->assessmentIntro = $assessmentIntro;
    }


    public function ManageSummaryReport()
    {
        try {

            $summaryReport = AssessmentIntro::summaryReport();

            return view('admin-dashboards.summary-report.index', compact('summaryReport'));

        } catch (\Exception $exception) {

            return redirect()->route('admin_all_questions')->with('error', $exception->getMessage());

        }
    }

    public function editSummaryReport($id)
    {
        try {

            $summary = AssessmentIntro::getSingleSummaryReport($id);

            return view('admin-dashboards.summary-report.edit', compact('summary'));

        } catch (\Exception $exception) {

            return redirect()->route('admin_all_questions')->with('error', $exception->getMessage());

        }
    }


}
