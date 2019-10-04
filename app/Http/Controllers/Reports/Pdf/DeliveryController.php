<?php

namespace App\Http\Controllers\Reports\Pdf;
use App\Http\Controllers\Controller;
use App\Models\InvoiceModel;
use Illuminate\Http\Request;
use PDF;

class DeliveryController extends Controller
{

    public function __construct(InvoiceModel $InvoiceModel)
    {
       $this->InvoiceModel = $InvoiceModel;
    }

    public function index(request $request)
    {
        $request = $request->all();

        // 40300015727 40300009167 902768 508788 3374448 $request['invoice_id']

        $data['data'] = $this->InvoiceModel->selectForInvoicePrinting($request['invoice_id']);

        $pdf = PDF::loadView('reports.pdf.delivery', $data);
        $pdf->setPaper([0, 0, 612, 864], 'Portrait');
        return $pdf->stream('DeliveryReceipt.pdf');

    }
}