<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanceWithdrawalRequest extends Model
{
    use HasFactory;
    protected $table = 'balance_withdrawal_requests';

    protected $fillable = [
        'user_id',
        'withdrawal_amount'
    ];

    public $timestamps = false;
}
