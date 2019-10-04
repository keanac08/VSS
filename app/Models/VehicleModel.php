<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;

class VehicleModel extends Model
{

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->oracle = DB::connection('oracle');
    }

    public function selectDetails($invoice_ids){
        
        $sql = "SELECT msn.serial_number cs_number, 
                    msib.attribute9 sales_model,
                    msn.attribute3 engine_number,
                    msn.attribute2 chassis_number,
                    rcta.attribute4 wb_number
                FROM  ra_customer_trx_all rcta
                LEFT JOIN mtl_serial_numbers msn
                ON rcta.attribute3 = msn.serial_number
                LEFT JOIN mtl_system_items_b msib
                ON msn.inventory_item_id = msib.inventory_item_id
                AND msn.current_organization_id = msib.organization_id
                LEFT JOIN ra_customer_trx_all rcta
                ON msn.serial_number = rcta.attribute3
                WHERE 1 = 1
                AND rcta.customer_trx_id in (". join(',',$invoice_ids) .")
                ORDER BY rcta.trx_date, msib.attribute9, rcta.customer_trx_id";

		return $this->oracle->select($sql);
    }
}
