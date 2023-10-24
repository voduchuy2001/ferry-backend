<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FerryTrip extends Model
{
    protected $table = 'ferry_trips';

    protected $fillable = [
        'name',
        'departure_date',
    ];
}
