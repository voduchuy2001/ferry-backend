<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ferry extends Model
{
    protected $table = 'ferries';

    protected $fillable = [
        'name',
        'number_of_seats',
        'year_of_production',
        'manufacturing_place',
        'status',
    ];
}
