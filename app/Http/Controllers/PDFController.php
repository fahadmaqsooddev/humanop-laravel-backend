<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Illuminate\Support\Facades\Auth;
use App\Models\Assessment;
use App\Models\AssessmentColorCode;


class PDFController extends Controller
{
    public function generatePDF($id)
    {

        $reports = Assessment::getReport($id);

        $alchl_code = Assessment::getAlchlCode($id);

        $style_position = AssessmentColorCode::getStylePosition($id);
        $feature_position = AssessmentColorCode::getFeaturePosition($id);

        $contxt = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE,
            ]
        ]);

        $pdf = PDF::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $pdf->getDomPDF()->setHttpContext($contxt);

        $pdf->loadView('pdf.report_pdf', compact('reports', 'alchl_code','style_position','feature_position'))->setOptions(['defaultFont' => 'Poppins, sans-serif']);

        $filename = $reports['user_name']. '_report.pdf';

        return $pdf->stream($filename);
    }

    public function generateGridPDF($id)
    {

        $grid = Assessment::getGrid($id);

        $user_name = $grid['users']['first_name']. ' '. $grid['users']['last_name'];
        $user_gender = $grid['users']['gender'] === 0 || $grid['users']['gender'] === '0' ? "Male" : "Female";
        $user_age = $grid['users']['age_min'] . '-'. $grid['users']['age_max'];

        $grid_code_color = AssessmentColorCode::getCodeColor($grid['id']);

        $contxt = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE,
            ]
        ]);

        $pdf = PDF::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $pdf->getDomPDF()->setHttpContext($contxt);

        $pdf->loadView('pdf.grid_pdf', compact('grid', 'grid_code_color', 'user_name', 'user_gender', 'user_age'))->setOptions(['defaultFont' => 'Poppins, sans-serif']);

        $filename = $user_name. '_report.pdf';

        return $pdf->stream($filename);
    }
}
