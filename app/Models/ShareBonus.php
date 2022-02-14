<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShareBonus extends Model
{
    use HasFactory;

    public function getWeeklybonusElegibleUsersFromGB()
    {
        return DB::table('users')
                ->join('golden_board', 'users.id', '=', 'golden_board.user_id')
                // ->join('diamond_board', 'users.id', '=', 'diamond_board.user_id')
                ->where('golden_board.is_switched_to_diamond_board', 0)
                // ->orWhere('diamond_board.is_bonus_shared', 0) // 'diamond_board.user_id as db_uid'
                ->select('golden_board.user_id as gb_uid')
                ->get();
    }

    public function getWeeklybonusElegibleUsersFromDB()
    {
        return DB::table('diamond_board')->where('is_bonus_shared', 0)->select('user_id')->get();
    }
}
