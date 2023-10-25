<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FerryRoute extends Model
{
    protected $table = 'ferry_routes';

    protected $fillable = [
        'name',
        'departure_station',
        'destination_station',
        'status',
    ];

    public function ferryTrips()
    {
        return $this->hasMany(FerryTrip::class);
    }
}
