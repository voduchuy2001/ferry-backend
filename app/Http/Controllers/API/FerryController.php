<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\FerryRequest;
use App\Models\Ferry;

class FerryController extends Controller
{
    public $itemPerPage = 10;

    public function index()
    {
        $ferries = Ferry::orderByDesc('created_at')
            ->with(['ferryTrips', 'seats'])
            ->paginate($this->itemPerPage);

        return response()->json([
            'data' => $ferries,
            'message' => __('Success'),
        ]);
    }

    public function getAll()
    {
        $ferries = Ferry::orderByDesc('created_at')
            ->paginate($this->itemPerPage);

        return response()->json([
            'data' => $ferries,
            'message' => __('Success'),
        ]);
    }

    public function create(FerryRequest $request)
    {
        $data = $request->validated();
        $ferry = Ferry::create($data);

        $seatIds = $data['seat_ids'];

        foreach ($seatIds as $seatId) {
            $ferry->seats()->attach($seatId);
        }

        return response()->json([
            'data' => $ferry,
            'message' => __('Success'),
        ]);
    }

    public function edit(FerryRequest $request, $id)
    {
        $data = $request->validated();
        $ferry = Ferry::findOrFail($id);

        $ferry->update($data);

        $seatIds = $data['seat_ids'];
        foreach ($seatIds as $seatId) {
            $ferry->seats()->sync($seatId);
        }

        return response()->json([
            'data' => $ferry,
            'message' => __('Success'),
        ]);
    }

    public function delete($id)
    {
        $ferry = Ferry::findOrFail($id);
        $ferry->seats()->detach();
        $ferry->delete();

        return response()->json([
            'message' => __('Success'),
        ]);
    }

    public function getFerryById($id)
    {
        $ferry = Ferry::with(['seats', 'ferryTrips'])
            ->findOrFail($id);

        return response()->json([
            'data' => $ferry,
            'message' => __('Success'),
        ]);
    }
}
