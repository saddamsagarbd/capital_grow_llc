<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAccount extends Model
{
    use HasFactory;
    protected $table = 'user_accounts';

    protected $fillable = [
        'user_id',
        'account_number',
        'account_title',
        'account_type',
        'account_status'
    ];

    public $timestamps = false;
}
