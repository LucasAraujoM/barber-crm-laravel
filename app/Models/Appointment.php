<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'client_id',
        'staff_id',
        'date',
        'start_time',
        'end_time',
        'notes',
    ];
}
