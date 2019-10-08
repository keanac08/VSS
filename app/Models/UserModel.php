<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use DB;

class UserModel extends Authenticatable
{
    protected $guard = 'oracle_portal_user';
    protected $connection = 'oracle';
    protected $table = 'ipc_portal.users';
    protected $primaryKey = 'user_id';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->oracle = DB::connection('oracle');
    }

    public function get($user_id)
    {
        $sql = 'SELECT u.user_id,
                        u.user_name,
                        ud.first_name,
                        ud.middle_name,
                        ud.last_name,
                        ud.division,
                        ud.department,
                        ud.section,
                        ud.email,
                        u.customer_id,
                        2 source_id,
                        ut.user_type_name,
                        ut.user_type_id
                    FROM ipc_portal.users u
                        LEFT JOIN ipc_portal.user_details ud ON u.user_id = ud.user_id
                        LEFT JOIN ipc_portal.user_system_access usa ON u.user_id = usa.user_id
                        LEFT JOIN ipc_portal.system_user_types ut
                            ON usa.user_type_id = ut.user_type_id
                        LEFT JOIN ipc_portal.systems sys ON usa.system_id = sys.system_id
                    WHERE 1 = 1 
                    AND u.status_id = 1 
                    AND ud.status_id = 1 
                    AND ud.user_id = :p_user_id
                    AND sys.system_id = :p_system_id';
            
            $params = [
                'p_user_id' => $user_id,
                'p_system_id' => 10
            ];
    
            return $this->oracle->select($sql, $params);
    }
}