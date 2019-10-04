<?php

namespace App\Models;
use DB;
use Illuminate\Foundation\Auth\User as Authenticatable;

class OracleUserModel extends Authenticatable
{
    protected $guard = 'oracle_user';
    protected $connection = 'oracle';
    protected $table = 'fnd_user';
    protected $primaryKey = 'user_id';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->oracle = DB::connection('oracle');
    }

    public function get($user_id)
    {
        $sql = 'SELECT usr.user_id,
                        usr.user_name,
                        ppf.first_name,
                        ppf.middle_names  middle_name,
                        ppf.last_name,
                        ppf.attribute2    division,
                        ppf.attribute3    department,
                        ppf.attribute4    section,
                        usr.email_address email,
                        NULL customer_id,
                        1 source_id,
                        ut.user_type_name,
                        ut.user_type_id
                FROM fnd_user usr
                        LEFT JOIN per_all_people_f ppf ON usr.employee_id = ppf.person_id
                        LEFT JOIN ipc_portal.user_system_access usa
                        ON usr.user_id = usa.user_id
                        LEFT JOIN ipc_portal.system_user_types ut
                        ON usa.user_type_id = ut.user_type_id
                        LEFT JOIN ipc_portal.systems sys ON usa.system_id = sys.system_id
                WHERE     1 = 1
                        AND sys.system_id = :p_system_id
                        AND usr.user_id = :p_user_id
                        AND usr.end_date IS NULL';
        
        $params = [
            'p_user_id' => $user_id,
            'p_system_id' => 10
        ];

        return  DB::connection('oracle')->select($sql, $params);
    }

}
