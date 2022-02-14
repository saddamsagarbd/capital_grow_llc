<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IncomeStatement extends Model
{
    use HasFactory;

    public function makeTransaction($data)
    {
        return DB::table('income_statements')->insert($data);
    }
}
