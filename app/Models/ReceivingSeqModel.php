<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class ReceivingSeqModel extends Model
{

    
    public function createDelete()
    {
        $sequence = DB::getSequence();
        
        $sequence->drop('receiving_copy_seq');
        $sequence->create('receiving_copy_seq', $start = 1, $nocache = true);
    }

    public function nextVal(){
        
        $sql = "SELECT receiving_copy_seq.NEXTVAL nextval FROM DUAL";

        $data =  DB::select($sql);

        return $data ? $data[0]->nextval : null;
    }




    
}
