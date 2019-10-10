<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;

class InvoiceModel extends Model
{

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // $this->uat = DB::connection('oracle_uat');
        $this->oracle = DB::connection('oracle');
    }

    public function selectForAllocation($customer_id){

        if(session('user.user_type_name') != 'Administrator'){
            $and = 'AND msn.attribute1 is not null';
        }
        else{
            $and = '';
        }
        
        $sql = "SELECT  rcta.trx_number invoice_number,
                        to_char(rcta.trx_date, 'MM/DD/YYYY') invoice_date,
                        rcta.attribute3 cs_number,
                        NVL(ooha.attribute3, '-') fleet_customer,
                        msib.attribute9 sales_model,
                        msn.attribute1 csr_number,
                        null wb_number,
                        null wb_id,
                        (CASE when msn.attribute1 is null then 0 else 1 end) w_csr
                FROM ra_customer_trx_all rcta
                        LEFT JOIN ipc_vehicle_cm cm 
                            ON rcta.customer_trx_id = cm.orig_trx_id
                        LEFT JOIN oe_order_headers_all ooha
                            ON rcta.interface_header_attribute1 = ooha.order_number
                        LEFT JOIN mtl_serial_numbers msn
                            ON rcta.attribute3 = msn.serial_number
                        LEFT JOIN mtl_system_items_b msib
                            ON msn.inventory_item_id = msib.inventory_item_id
                            and 121 = msib.organization_id
                WHERE     1 = 1
                        AND rcta.trx_date >= '2019-10-09'
                        AND rcta.cust_trx_type_id = 1002
                        AND cm.orig_trx_id IS NULL
                        AND rcta.attribute4 IS NULL
                        ".$and."
                        AND rcta.sold_to_customer_id = :p_customer_id
                    ORDER BY invoice_date, sales_model, rcta.customer_trx_id";

        $params = [
            'p_customer_id' => $customer_id
        ];

		return $this->oracle->select($sql, $params);
    }
    
    public function selectForPrint($customer_id, $from_date, $to_date){
        
        $sql = "SELECT  rcta.customer_trx_id invoice_id,
                        rcta.trx_number invoice_number,
                        to_char(rcta.trx_date, 'MM/DD/YYYY') invoice_date,
                        rcta.attribute3 cs_number,
                        NVL(ooha.attribute3, '-') fleet_customer,
                        CASE WHEN oola.attribute1 is null then msib.attribute9 else msib.attribute9 || ' ' || oola.attribute1  end sales_model,                        msn.attribute1 csr_number,
                        rcta.attribute4 wb_number
                FROM ra_customer_trx_all rcta
                        LEFT JOIN ipc_vehicle_cm cm 
                            ON rcta.customer_trx_id = cm.orig_trx_id
                        LEFT JOIN oe_order_headers_all ooha
                            ON rcta.interface_header_attribute1 = ooha.order_number
                        LEFT JOIN oe_order_lines_all oola
                            ON ooha.header_id = oola.header_id
                        LEFT JOIN mtl_serial_numbers msn
                            ON rcta.attribute3 = msn.serial_number
                        LEFT JOIN mtl_system_items_b msib
                            ON msn.inventory_item_id = msib.inventory_item_id
                            and 121 = msib.organization_id
                WHERE     1 = 1
                        -- AND rcta.trx_date >= '2019-08-20'
                        AND rcta.trx_date between :p_from_date and :p_to_date
                        AND rcta.cust_trx_type_id = 1002
                        AND cm.orig_trx_id IS NULL
                        AND rcta.attribute4 IS NOT NULL
                        AND rcta.sold_to_customer_id = :p_customer_id
                    ORDER BY invoice_date, sales_model";

        $params = [
            'p_customer_id' => $customer_id,
            'p_from_date' => laravelDate($from_date),
            'p_to_date' => laravelDate($to_date)
        ];

		return $this->oracle->select($sql, $params);
    }

    public function selectForReceivingCopy($invoice_ids){
        
        $sql = "SELECT  rcta.customer_trx_id invoice_id,
                        rcta.trx_number invoice_number,
                        to_char(rcta.trx_date, 'MM/DD/YYYY') invoice_date,
                        rcta.attribute3 cs_number,
                        NVL(ooha.attribute3, '-') fleet_customer,
                        msib.attribute9 sales_model,
                        msn.attribute1 csr_number,
                        rcta.attribute4 wb_number,
                        hp.party_name,
                        hcaa.account_name
                FROM ra_customer_trx_all rcta
                        LEFT JOIN ipc_vehicle_cm cm 
                            ON rcta.customer_trx_id = cm.orig_trx_id
                        LEFT JOIN oe_order_headers_all ooha
                            ON rcta.interface_header_attribute1 = ooha.order_number
                        LEFT JOIN mtl_serial_numbers msn
                            ON rcta.attribute3 = msn.serial_number
                        LEFT JOIN mtl_system_items_b msib
                            ON msn.inventory_item_id = msib.inventory_item_id
                            and 121 = msib.organization_id
                        LEFT JOIN hz_cust_accounts_all hcaa
                            ON hcaa.cust_account_id = rcta.sold_to_customer_id
                        LEFT JOIN hz_parties hp
                            ON hcaa.party_id = hp.party_id
                WHERE     1 = 1
                        AND rcta.cust_trx_type_id = 1002
                        AND cm.orig_trx_id IS NULL
                        AND rcta.attribute4 IS NOT NULL
                        AND rcta.customer_trx_id in (".join(',',$invoice_ids).")
                    ORDER BY invoice_date, sales_model, rcta.customer_trx_id";

		return $this->oracle->select($sql);
    }

    public function selectForInvoicePrinting($invoice_ids){
        
        $sql = "SELECT rcta.customer_trx_id,
                    rcta.sold_to_customer_id,
                    cust.cust_account_id,
                    rcta.trx_number,
                    rcta.trx_date,
                    rtl.name                                                payment_terms,
                    rcta.interface_header_attribute1                        so_number,
                    rcta.attribute4                                         wb_number,
                    CONCAT ('313', LPAD (wdd.attribute2, 8, 0))             dr_number,
                    msn.inventory_item_id,
                    msn.attribute1                                          csr_number,
                    msn.attribute12                                         csr_or_number,
                    msib.segment1                                           model_code,
                    CASE WHEN oola.attribute1 is null then msib.attribute9 else msib.attribute9 || ' ' || oola.attribute1  end sales_model,                        
                    msn.attribute1 csr_number,
                    msn.lot_number                                          lot_number,
                    msn.serial_number                                       cs_number,
                    msn.attribute2                                          chassis_number,
                    msib.attribute11                                        engine_type,
                    msn.attribute3                                          engine_no,
                    (CASE
                        WHEN (msib.attribute8 IS NULL OR msib.attribute8 = 'NO COLOR')
                        THEN
                            NULL
                        ELSE
                            msib.attribute8
                        END)
                        body_color,
                    msib.attribute17                                        fuel,
                    msib.attribute14                                        gvw,
                    msn.attribute6                                          key_no,
                    msib.attribute13                                        tire_specs,
                    msn.attribute8                                          battery,
                    msib.attribute16                                        displacement,
                    reverse(substr(reverse(substr(msn.attribute1,7)),-4)) year_model,
                    msit.items1,
                    msit.items2,
                    cust.party_name,
                    cust.account_name,
                    cust.tax_reference,
                    cust.address1,
                    cust.address2,
                    cust.address3,
                    ooha.attribute3                                         fleet_name,
                    cust.business_style,
                    cust.class_code,
                    rctla.vatable_sales,
                    rctla.discount,
                    rctla.vatable_sales + rctla.discount                    amt_net_of_vat,
                    rctla.vat_amount,
                    rctla.vatable_sales + rctla.discount + rctla.vat_amount total_sales
                FROM ra_customer_trx_all rcta
                    INNER JOIN
                    (  SELECT customer_trx_id,
                                SUM (
                                    CASE
                                    WHEN LINE_RECOVERABLE > 0 THEN LINE_RECOVERABLE
                                    ELSE 0
                                    END)
                                    vatable_sales,
                                SUM (
                                    CASE
                                    WHEN LINE_RECOVERABLE < 0 THEN LINE_RECOVERABLE
                                    ELSE 0
                                    END)
                                    discount,
                                SUM (TAX_RECOVERABLE) vat_amount
                            FROM ra_customer_trx_lines_all
                        WHERE line_type = 'LINE'
                        GROUP BY customer_trx_id) rctla
                        ON rcta.customer_trx_id = rctla.customer_trx_id
                    INNER JOIN oe_order_headers_all ooha
                        ON rcta.interface_header_attribute1 = ooha.order_number
                    INNER JOIN oe_order_lines_all oola
                        ON rcta.interface_header_attribute6 = oola.line_id
                    INNER JOIN wsh_delivery_details wdd
						ON oola.line_id = wdd.source_line_id
                    INNER JOIN ra_terms_tl rtl 
                        ON ooha.payment_term_id = rtl.term_id
                    INNER JOIN mtl_serial_numbers msn
                        ON rcta.attribute3 = msn.serial_number
                    INNER JOIN mtl_system_items_b msib
                        ON     msib.inventory_item_id = msn.inventory_item_id
                        AND msib.organization_id = msn.current_organization_id
                    LEFT JOIN
                    (SELECT HCAA.cust_account_id,
                            hp.party_name,
                            hcaa.account_name,
                            REGEXP_REPLACE (
                                hl.address1,
                                'DEALERS-PARTS|DEALERS-VEHICLE|DEALERS-OTHERS|DEALERS-FLEET|FLEET-PARTS|FLEET')
                                address1,
                            hl.address2,
                            hl.address3,
                            hccd.class_code_description business_style,
                            hca.class_code,
                            hcsua.tax_reference,
                            hcsua.site_use_id
                        FROM hz_cust_accounts_all hcaa
                            LEFT JOIN hz_parties hp ON hcaa.party_id = hp.party_id
                            LEFT JOIN HZ_CODE_ASSIGNMENTS hca
                                ON     hca.owner_table_id = hp.party_id
                                    AND hca.end_date_active IS NULL
                            LEFT JOIN HZ_CLASS_CODE_DENORM hccd
                                ON     hca.class_code = hccd.class_code
                                    AND hca.class_category = hccd.class_category
                            LEFT JOIN hz_cust_acct_sites_all hcasa
                                ON hcaa.cust_account_id = hcasa.cust_account_id
                            LEFT JOIN hz_cust_site_uses_all hcsua
                                ON hcasa.cust_acct_site_id = hcsua.cust_acct_site_id
                            LEFT JOIN hz_party_sites hps
                                ON hcasa.party_site_id = hps.party_site_id
                            LEFT JOIN hz_locations hl ON hps.location_id = hl.location_id)
                    cust
                        ON     rcta.sold_to_customer_id = cust.cust_account_id
                            AND rcta.bill_to_site_use_id = cust.site_use_id
                    LEFT JOIN (  SELECT inventory_item_id,
                                        organization_id,
                                        MAX (SUBSTR (LONG_DESCRIPTION,
                                                        1,
                                                        INSTR (LONG_DESCRIPTION,
                                                                CHR (10),
                                                                1,
                                                                12)
                                                        - 1))
                                            items1,
                                        MAX (SUBSTR (LONG_DESCRIPTION,
                                                        INSTR (TRIM (LONG_DESCRIPTION),
                                                                CHR (10),
                                                                1,
                                                                12)
                                                        + 1,
                                                        3000))
                                            items2
                                    FROM MTL_SYSTEM_ITEMS_TL
                                GROUP BY inventory_item_id, organization_id) msit
                        ON     msib.inventory_item_id = msit.inventory_item_id
                            AND msit.organization_id = 121
                WHERE 1 = 1
                    AND rcta.customer_trx_id in (".join(',',$invoice_ids).")
                ORDER BY rcta.trx_date, msib.attribute9, rcta.customer_trx_id";

        return $this->oracle->select($sql);
    }
    
    public function updateWbNumber($wb_number, $cs_number){
        
        $sql = "UPDATE ra_customer_trx_all
                        SET attribute4 = :p_wb_number
                    WHERE customer_trx_id =
                            (SELECT rct.customer_trx_id
                                FROM ra_customer_trx_all rct
                                    LEFT JOIN ipc_vehicle_cm cm
                                        ON     rct.customer_trx_id = cm.orig_trx_id
                                            AND cm.CM_TRX_TYPE_ID != 10081
                                WHERE     1 = 1
                                    AND cm.orig_trx_id IS NULL
                                    AND rct.cust_trx_type_id = 1002
                                    AND rct.attribute3 = :p_cs_number)";

        $params = [
            'p_wb_number' => $wb_number,
            'p_cs_number' => $cs_number
        ];

		return $this->oracle->update($sql, $params);
    
    }
    
    public function selectToday(){
        
        $sql = "SELECT rcta.customer_trx_id,
                        NVL (hcaa.account_name, hp.party_name) account_name,
                        ooha.attribute3                        fleet_name,
                        rcta.attribute3                        cs_number,
                        msib.attribute9                        sales_model,
                        rcta.attribute4 wb_number,
                        (CASE
                            WHEN (msib.attribute8 IS NULL OR msib.attribute8 = 'NO COLOR')
                            THEN
                            NULL
                            ELSE
                            msib.attribute8
                        END)
                        body_color,
                        rcta.trx_number invoice_number,
                        to_char(rcta.trx_date,'MM/DD/YYYY') invoice_date
                FROM ra_customer_trx_all rcta
                        LEFT JOIN
                        (SELECT DISTINCT customer_trx_id, inventory_item_id, warehouse_id
                        FROM ra_customer_trx_lines_all
                        WHERE line_type = 'LINE') rctla
                        ON rcta.customer_trx_id = rctla.customer_trx_id
                        LEFT JOIN ipc_ar_invoices_with_cm cm
                        ON rcta.customer_trx_id = cm.orig_trx_id
                        LEFT JOIN hz_cust_accounts_all hcaa
                        ON rcta.sold_to_customer_id = hcaa.cust_account_id
                        LEFT JOIN hz_parties hp ON hcaa.party_id = hp.party_id
                        LEFT JOIN mtl_system_items_b msib
                        ON     rctla.warehouse_id = msib.organization_id
                            AND rctla.inventory_item_id = msib.inventory_item_id
                        LEFT JOIN oe_order_headers_all ooha
                        ON rcta.interface_header_attribute1 = ooha.order_number
                WHERE     1 = 1
                        AND rcta.cust_trx_type_id = 1002
                        AND cm.orig_trx_id IS NULL
                        AND rcta.trx_date = TRUNC (SYSDATE)
                        ORDER BY account_name";

       return $this->oracle->select($sql);
    
    }

    public function selectDashboardMonthlySummary(){
        
        $sql = "  SELECT replace(TO_CHAR(TO_DATE(month, 'MM'), 'Month'), ' ', '') month_name,
                            COUNT (CASE WHEN order_Type = 'Vehicle' THEN 1 ELSE NULL END) vehicle,
                            COUNT (CASE WHEN order_type != 'Vehicle' THEN 1 ELSE NULL END) fleet
                    FROM (SELECT EXTRACT (MONTH FROM rcta.trx_date) month,
                                    NVL (
                                    SUBSTR (ottt.description,
                                            0,
                                            INSTR (ottt.description, ' ') - 1),
                                    ottt.description)
                                    order_type
                            FROM ra_customer_trx_all rcta
                                    LEFT JOIN ipc_vehicle_cm cm
                                    ON rcta.customer_trx_id = cm.orig_trx_id
                                    LEFT JOIN oe_order_headers_all ooha
                                    ON rcta.interface_header_attribute1 = ooha.order_number
                                    LEFT JOIN oe_transaction_types_tl ottt
                                    ON ooha.order_type_id = ottt.transaction_type_id
                            WHERE     1 = 1
                                    AND rcta.cust_trx_type_id = 1002
                                    AND cm.orig_trx_id IS NULL
                                    AND EXTRACT (YEAR FROM rcta.trx_date) =
                                        EXTRACT (YEAR FROM SYSDATE))
                    GROUP BY month
                    ORDER BY month";

       return $this->oracle->select($sql);
    
    }
}
