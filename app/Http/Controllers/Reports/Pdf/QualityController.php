<?php

namespace App\Http\Controllers\Reports\Pdf;
use App\Http\Controllers\Controller;
use App\Models\VehicleModel;
use Illuminate\Http\Request;
use PDF;

class QualityController extends Controller
{

    public function __construct(VehicleModel $VehicleModel)
    {
       $this->VehicleModel = $VehicleModel;
    }

    public function index(request $request)
    {
        $request = $request->all();

        $data['data'] = $this->VehicleModel->selectDetails($request['invoice_id']);

        $pdf = PDF::loadView('reports.pdf.quality', $data);
        $pdf->setPaper('A4', 'Portrait');
        return $pdf->stream('CertificateOfQualityControl.pdf');

    }
}