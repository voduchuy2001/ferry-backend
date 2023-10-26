<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SeatRequest;
use App\Models\Seat;

class SeatController extends Controller
{
    public $itemPerPage = 10;

    public function index()
    {
        $seats = Seat::orderByDesc('created_at')
            ->paginate($this->itemPerPage);

        return response()->json([
            'data' => $seats,
            'message' => __('Success'),
        ]);
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

    public function edit(SeatRequest $request, $id)
    {
        $data = $request->validated();
        $seat = Seat::findOrFail($id);

        $seat->update($data);
        return response()->json([
            'data' => $seat,
            'message' => __('Success'),
        ]);
    }

    public function delete($id)
    {
        $seat = Seat::findOrFail($id);
        $seat->delete();

        return response()->json([
            'message' => __('Success'),
        ]);
    }
}
