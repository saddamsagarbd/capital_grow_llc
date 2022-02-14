<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReferenceCount extends Model
{
    use HasFactory;

    public function getTotalReferal($ref_id)
    {
        return DB::table("refered_users_count")
                        ->where('user_id', $ref_id)
                        ->select('total_referal')
                        ->first();

    }

    public function incrementRefarals($ref_id)
    {
        $getReferals = $this->getTotalReferal($ref_id);        

        if(!isset($getReferals->total_referal))
        {
            DB::table('refered_users_count')->insert(
                [
                    'user_id' => $ref_id, 
                    'total_referal' => 1
                ]
            );
        }
        else{
            $count = (int) $getReferals->total_referal;
            $affected = DB::table('refered_users_count')
              ->where('user_id', $ref_id)
              ->update(['total_referal' => ($count + 1)]);
        }

        return true;
    }
}
