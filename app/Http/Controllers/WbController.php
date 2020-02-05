<?php
namespace App\Http\Controllers;
use App\Models\WbModel;
use App\Models\InvoiceModel;
use Illuminate\Http\Request;

class WbController extends Controller
{

    public function __construct(WbModel $WbModel, InvoiceModel $InvoiceModel)
    {
       $this->WbModel = $WbModel;
       $this->InvoiceModel = $InvoiceModel;
    }
    
    public function store(Request $request)
    {
        while($request->wb_from <= $request->wb_to){
            $wb = $this->WbModel->insertWbs(
                [
                    'wb_number' => $request->wb_from,
                    'prefix' => $request->wb_prefix
                ],
                [
                    'batch_name' => $request->wb_batch_name,
                    'prefix' => $request->wb_prefix,
                    'wb_number' => $request->wb_from,
                    'uploaded_by' => session('user.shortname'),
                    'sales_model_id' => $request->wb_model_id
                ]);

            $request->wb_from++;
        }
    }

    public function inventory()
    {

        $rows =  $this->WbModel->selectWbs();

        $data = [];
        $cnt = 1;
        
        foreach($rows as $row)
        {
            $data[] = (object)[
                'cnt' => $cnt,
                'batch_name' => $row->batch_name,
                'wb_number' => $row->prefix . $row->wb_number,
                'cs_number' => $row->cs_number,
                'uploaded_date' => shortDate($row->uploaded_date),
                'uploaded_by' => $row->uploaded_by,
                'tagged_date' => shortDate($row->tagged_date),
                'tagged_by' =>  $row->tagged_by
            ];
            $cnt++;
        }

        return view('warranty_booklet.inventory', ['rows' => $data]);
    }

    public function fetchRequiredWbs(request $request)
    {
        return $this->WbModel->selectRequiredWbs($request->cnt, $request->model_id);
        
    }

    public function updateAllocatedWb(request $request)
    {
        $check_data = [];
        $update_data = [];
        
        foreach($request->invoices as $row){
            $row = (object)$row;
            if($row->wb_id){
                $check_data[] =  $row->wb_id;
                $update_data[] =  array('wb_id' => $row->wb_id,'wb_number' => $row->wb_number,'cs_number' =>  $row->cs_number);
            }
        }

        $count =  $this->WbModel->selectCheckAvailability($check_data);

        if($count != count($check_data)){
            //do not update
            return 0;
        }
        else{
            //update
            $return = [];
            foreach($update_data as $row){
                $row = (object)$row;

                if($this->WbModel->updateCsNumber($row->wb_id, $row->cs_number) == 1){
                    $return[] = $this->InvoiceModel->updateWbNumber($row->wb_number, $row->cs_number);
                }
            }
            return $return;
        }
        
    }
}
