<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAccountInformation extends Model
{
    use HasFactory;

    protected $table = 'user_account_information';

    protected $fillable = [
        'user_id',
        'banking_type'
    ];

    public $timestamps = false;
}
