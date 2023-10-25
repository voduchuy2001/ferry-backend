<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\FerrryTripRequest;
use App\Models\FerryTrip;

class FerryTripController extends Controller
{
    public int $itemPerPage = 10;

    public function index()
    {
        $ferryTrips = FerryTrip::orderByDesc('created_at')
            ->paginate($this->itemPerPage);

        return response()->json([
            'data' => $ferryTrips,
            'message' => __('Success'),
        ]);
    }

    public function create(FerrryTripRequest $request)
    {
        $data = $request->validated();

        $ferryTrip = FerryTrip::create($data);


        return response()->json([
            'data' => $ferryTrip,
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
