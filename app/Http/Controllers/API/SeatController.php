<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SeatRequest;
use App\Models\Seat;

class SeatController extends Controller
{
    public function index()
    {
    }

    public function create(SeatRequest $request)
    {
        $data = $request->validated();

        $seat = Seat::create($data);


        return response()->json([
            'data' => $seat,
            'message' => __('Success'),
        ]);
    }

    public function edit($id)
    {
    }

    public function delete($id)
    {
    }
}
