<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FerryTrip extends Model
{
    protected $table = 'ferry_trips';

    protected $fillable = [
        'departure_date',
        'departure_time',
        'ferry_id',
        'ferry_route_id',
    ];

    public function ferryRoute()
    {
        return $this->belongsTo(FerryRoute::class, 'ferry_route_id');
    }

    public function ferry()
    {
        return $this->belongsTo(Ferry::class, 'ferry_id');
    }
}
