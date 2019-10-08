<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use DB;

class CustomerModel extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // $this->uat = DB::connection('oracle_uat');
        $this->oracle = DB::connection('oracle');
    }

    public function selectCustomers(){
        $sql = "SELECT DISTINCT
                    hcaa.cust_account_id customer_id,
                    hcaa.account_name,
                    hp.party_name customer_name
                FROM ra_customer_trx_all rcta
                LEFT JOIN ipc_vehicle_cm cm 
                    ON rcta.customer_trx_id = cm.orig_trx_id
                LEFT JOIN hz_cust_accounts_all hcaa
                    ON rcta.sold_to_customer_id = hcaa.cust_account_id
                LEFT JOIN hz_parties hp ON hcaa.party_id = hp.party_id
                WHERE 1 = 1
                    AND rcta.trx_date >= '2019-10-09'
                    AND rcta.cust_trx_type_id = 1002
                    AND cm.orig_trx_id IS NULL
                   AND rcta.attribute4 IS NULL
                ORDER BY hp.party_name";
        return  $this->oracle->select($sql);
    }

    public function selectCustomersInvoicePrint(){
        $sql = "SELECT DISTINCT
                    hcaa.cust_account_id customer_id,
                    hcaa.account_name,
                    hp.party_name customer_name
                FROM ra_customer_trx_all rcta
                LEFT JOIN ipc_vehicle_cm cm 
                    ON rcta.customer_trx_id = cm.orig_trx_id
                LEFT JOIN hz_cust_accounts_all hcaa
                    ON rcta.sold_to_customer_id = hcaa.cust_account_id
                LEFT JOIN hz_parties hp ON hcaa.party_id = hp.party_id
                WHERE 1 = 1
                    AND rcta.trx_date >= '2019-10-09'
                    AND rcta.cust_trx_type_id = 1002
                    AND cm.orig_trx_id IS NULL
                   AND rcta.attribute4 IS NOT NULL
                ORDER BY hp.party_name";
        return  $this->oracle->select($sql);
    }
}
