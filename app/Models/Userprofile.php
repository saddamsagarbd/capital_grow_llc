<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Userprofile extends Model
{
    use HasFactory;

    protected $table = 'userprofiles';

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'gender',
        'identification_no',
        'dob',
        'contact_number',
        'reference_id',
        'placement_id',
        'profile_img'
    ];

    public $timestamps = false;
}
