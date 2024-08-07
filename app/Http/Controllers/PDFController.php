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
}
