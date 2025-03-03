<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeliveryPoint extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'city',
        'contact_person',
        'contact_number'
    ];
}
