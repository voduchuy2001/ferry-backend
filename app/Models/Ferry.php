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

    public function ferryTrips()
    {
        return $this->hasMany(FerryTrip::class);
    }

    public function seats()
    {
        return $this->belongsToMany(Seat::class, 'ferry_seat')
            ->withPivot('status');
    }
}
