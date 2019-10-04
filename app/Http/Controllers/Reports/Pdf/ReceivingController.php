<?php

namespace App\Http\Controllers\Reports\Pdf;
use App\Http\Controllers\Controller;
use App\Models\InvoiceModel;
use Illuminate\Http\Request;
use PDF;

class ReceivingController extends Controller
{

    public function __construct(InvoiceModel $InvoiceModel)
    {
       $this->InvoiceModel = $InvoiceModel;
    }

    public function index(request $request)
    {
        $request = $request->all();

        $data['data'] = $this->InvoiceModel->selectForReceivingCopy($request['invoice_id']);

        $pdf = PDF::loadView('reports.pdf.receiving', $data);
        $pdf->setPaper('A4', 'Portrait');
        return $pdf->stream('DealersReceivingCopy.pdf');

    }
}