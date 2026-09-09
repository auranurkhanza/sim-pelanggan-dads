<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_code',
        'name',
        'phone_number',
        'address',
        'region',
        'ktp_path',
        'bast_path',
        'customer_photo_path',
        'status',
        'validation_notes',
    ];
}