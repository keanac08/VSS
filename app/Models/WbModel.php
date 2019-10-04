<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;
use Carbon\Carbon;

class WbModel extends Model
{
    protected $table = 'IPC.IPC_VEHICLE_WB_MASTER';
    protected $connection = 'oracle';

    // public $timestamps = false;
        
    protected $primaryKey = null;
    public $incrementing = false;

    const CREATED_AT = 'UPLOADED_DATE';
    const UPDATED_AT = 'LAST_UPDATE';

    protected $guarded = [];

    protected $oracle;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->oracle = DB::connection('oracle');
    }

    public function insertWbs($check_params, $data_params)
    {
        return $this->firstOrCreate($check_params, $data_params);
    }

    public function selectWbs()
    {
        return $this->orderBy('id')->get();
    }

    public function selectAvailableWb()
    {
        $sql = "SELECT id,
                        prefix,
                        wb_number,
                        uploaded_by,
                        to_char(uploaded_date,'MM/DD/YYYY') uploaded_date,
                        batch_name
                FROM IPC.IPC_VEHICLE_WB_MASTER
                WHERE cs_number IS NULL";

       return $this->oracle->select($sql);
    }

    public function selectRequiredWbs($count)
    {
        
        $sql = "SELECT tbl.id, CONCAT (tbl.prefix, tbl.wb_number) wb_number
                    FROM ( SELECT *
                            FROM ipc.ipc_vehicle_wb_master
                            WHERE cs_number IS NULL
                        ORDER BY id) tbl
                WHERE ROWNUM <= :p_count";

        $params = [
            'p_count' => $count
        ];

		return $this->oracle->select($sql, $params);
    }
    
    public function selectCheckAvailability($params)
    {
        return $this->whereIn('id',$params)
                    ->where('cs_number', null)
                    ->count();
    }

    public function updateCsNumber($wb_id, $cs_number)
    {
        return $this->where('id', $wb_id)
             ->update([
                    'cs_number' => $cs_number,
                    'tagged_by' => session('user.shortname'),
                    'tagged_date' => Carbon::now()
                    ]);
    }
}
