<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'tickets';

    protected $fillable = [
        'phone_number',
        'identity',
        'name',
        'date_of_birth',
        'place_of_birth',
        'nationality',
        'sex',
        'email',
        'address',
        'seat_id',
        'ferry_trip_id',
        'ferry_id',
    ];

    public function seat()
    {
        return $this->belongsTo(Seat::class, 'seat_id');
    }

    public function ferryTrip()
    {
        return $this->belongsTo(FerryTrip::class, 'ferry_trip_id');
    }
}
