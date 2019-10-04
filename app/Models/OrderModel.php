<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;

class OrderModel extends Model
{

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // $this->uat = DB::connection('oracle_uat');
        $this->oracle = DB::connection('oracle');
    }

    public function selectDashboardData(){
        
        $sql = "SELECT COUNT (CASE hold.released_flag WHEN 'N' THEN 1 ELSE NULL END) tagged,
                        COUNT (CASE hold.released_flag WHEN 'Y' THEN 1 ELSE NULL END) for_invoice
                FROM oe_order_headers_all ooha
                        INNER JOIN oe_order_lines_all oola ON ooha.header_id = oola.header_id
                        INNER JOIN oe_order_holds_all hold
                        ON ooha.header_id = hold.header_id AND oola.line_id = hold.line_id
                        INNER JOIN mtl_reservations mr
                        ON oola.line_id = mr.demand_source_line_id
                        INNER JOIN mtl_serial_numbers msn
                        ON mr.reservation_id = msn.reservation_id
                WHERE 1 = 1 AND oola.ship_from_org_id = 121";

        $data = $this->oracle->select($sql);
        
        $sql2 = "SELECT COUNT ( CASE rct.trx_date WHEN TRUNC (SYSDATE) THEN 1 ELSE NULL END) today
                    FROM ra_customer_trx_all rct
                        LEFT JOIN ipc_vehicle_cm cm
                            ON rct.customer_trx_id = cm.orig_trx_id
                            AND cm.CM_TRX_TYPE_ID != 10081
                WHERE     1 = 1
                        AND cm.orig_trx_id IS NULL
                        AND rct.cust_trx_type_id = 1002
                        AND rct.trx_date >= TRUNC (SYSDATE - 1)";
        
        $data2 = $this->oracle->select($sql2);

        $sql3 = "SELECT COUNT (id) avail_wb
                    FROM ipc.ipc_vehicle_wb_master
                WHERE cs_number IS NULL";
        
        $data3 = $this->oracle->select($sql3);
        
        return [
                'tagged' => $data[0]->tagged, 
                'for_invoice' => $data[0]->for_invoice, 
                'today' => $data2[0]->today,
                'avail_wb' => $data3[0]->avail_wb
               ];
	}

    public function selectPendingInvoice(){
        
        $sql = "SELECT msn.serial_number                      cs_number,
                        NVL (hcaa.account_name, hp.party_name) customer,
                        msib.attribute9                        sales_model,
                        msib.attribute8                        body_color,
                        ooha.attribute3                        fleet_customer
                    FROM oe_order_headers_all ooha
                        INNER JOIN oe_order_lines_all oola ON ooha.header_id = oola.header_id
                        INNER JOIN oe_order_holds_all hold
                            ON ooha.header_id = hold.header_id AND oola.line_id = hold.line_id
                        INNER JOIN mtl_reservations mr
                            ON oola.line_id = mr.demand_source_line_id
                        INNER JOIN mtl_serial_numbers msn
                            ON mr.reservation_id = msn.reservation_id
                        INNER JOIN mtl_system_items_b msib
                            ON     msn.inventory_item_id = msib.inventory_item_id
                                AND msn.current_organization_id = msib.organization_id
                        INNER JOIN hz_cust_accounts_all hcaa
                            ON ooha.sold_to_org_id = hcaa.cust_account_id
                        INNER JOIN hz_parties hp ON hcaa.party_id = hp.party_id
                    WHERE 1 = 1 AND oola.ship_from_org_id = 121 
                    AND hold.released_flag = 'Y'
                    ORDER BY customer";

       return $this->oracle->select($sql);
    
    }

    public function selectTagged(){
        
        $sql = "SELECT msn.serial_number                      cs_number,
                        NVL (hcaa.account_name, hp.party_name) customer,
                        msib.attribute9                        sales_model,
                        msib.attribute8                        body_color,
                        ooha.attribute3                        fleet_customer
                    FROM oe_order_headers_all ooha
                        INNER JOIN oe_order_lines_all oola ON ooha.header_id = oola.header_id
                        INNER JOIN oe_order_holds_all hold
                            ON ooha.header_id = hold.header_id AND oola.line_id = hold.line_id
                        INNER JOIN mtl_reservations mr
                            ON oola.line_id = mr.demand_source_line_id
                        INNER JOIN mtl_serial_numbers msn
                            ON mr.reservation_id = msn.reservation_id
                        INNER JOIN mtl_system_items_b msib
                            ON     msn.inventory_item_id = msib.inventory_item_id
                                AND msn.current_organization_id = msib.organization_id
                        INNER JOIN hz_cust_accounts_all hcaa
                            ON ooha.sold_to_org_id = hcaa.cust_account_id
                        INNER JOIN hz_parties hp ON hcaa.party_id = hp.party_id
                    WHERE 1 = 1 AND oola.ship_from_org_id = 121 
                    AND hold.released_flag = 'N'
                    ORDER BY customer";

       return $this->oracle->select($sql);
    
    }

}
