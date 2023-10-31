<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    protected $table = 'seats';

    protected $fillable = [
        'name',
    ];

    public function ferries()
    {
        return $this->belongsToMany(Ferry::class, 'ferry_seat')
            ->withPivot('status');
    }
}
