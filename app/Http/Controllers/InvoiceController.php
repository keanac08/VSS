<?php
namespace App\Http\Controllers;
use App\Models\InvoiceModel;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{

    public function __construct(InvoiceModel $InvoiceModel)
    {
       $this->InvoiceModel = $InvoiceModel;
    }

    public function fetchForAllocation(request $request)
    {
        $rows = (object)$this->InvoiceModel->selectForAllocation($request->customer_id, $request->model_id);

        $data = [];
        foreach($rows as $row)
        {
            $data[] = array(
                        'invoice_number' => $row->invoice_number,
                        'invoice_date' => $row->invoice_date,
                        'fleet_customer' => $row->fleet_customer,
                        'sales_model' => $row->sales_model,
                        'csr_number' => $row->csr_number,
                        'wb_number' => $row->wb_number,
                        'wb_id' => $row->wb_id,
                        'w_csr' => ($row->w_csr == 1 ? true:false),
                        'cs_number' => $row->cs_number
            );

        }
        
        return response()->json($data);
    }

    public function fetchForPrint(request $request)
    {

        $rows = (object)$this->InvoiceModel->selectForPrint($request->customer_id, $request->from_date, $request->to_date);

        $data = [];
        foreach($rows as $row)
        {
            $data[] = array(
                        'invoice_id' => $row->invoice_id,
                        'invoice_number' => $row->invoice_number,
                        'invoice_date' => $row->invoice_date,
                        'fleet_customer' => $row->fleet_customer,
                        'sales_model' => $row->sales_model,
                        'csr_number' => $row->csr_number,
                        'wb_number' => $row->wb_number,
                        'cs_number' => $row->cs_number,
                        'print' => false
            );

        }
        
        return response()->json($data);
    }
}
