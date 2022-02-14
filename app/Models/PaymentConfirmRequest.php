<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentConfirmRequest extends Model
{
    use HasFactory;

    protected $table = 'payment_confirm_requests';

    protected $fillable = [
        'user_id',
        'username',
        'transaction_no',
        'paid_amount',
        'payment_date',
        'pay_to_ac',
        'pay_to'
    ];
}
