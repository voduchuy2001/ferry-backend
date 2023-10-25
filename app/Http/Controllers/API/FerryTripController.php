<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\FerrryTripRequest;
use App\Models\FerryTrip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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

    public function getFerryTrip(Request $request)
    {
        $validator = Validator::make($request->input(), [
            'routeId' => 'required|numeric',
            'departureDate' => 'required|date_format:Y-m-d',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        $ferryTrips = FerryTrip::where(function ($query) use ($data) {
            $query->where('ferry_route_id', $data['routeId'])
                ->where('trip_type', 'oneway')
                ->where('departure_date', $data['departureDate']);
        })
            ->with('ferryRoute', 'ferry')
            ->get();

        return response()->json([
            'data' => $ferryTrips,
            'message' => __('Success'),
        ]);
    }

    public function getFerryForRoundTrip(Request $request)
    {
        $validator = Validator::make($request->input(), [
            'departureDate' => 'required|date_format:Y-m-d',
            'departureStation' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $departureDate = $data['departureDate'];
        $departureStation = $data['departureStation'];

        $ferryForRoundTrips = FerryTrip::where('trip_type', 'twoway')
            ->where('departure_date', $departureDate)
            ->whereIn('ferry_route_id', function ($query) use ($departureStation) {
                $query->select('id')
                    ->from('ferry_routes')
                    ->where('departure_station', $departureStation);
            })
            ->with('ferryRoute', 'ferry')
            ->get();

        return response()->json([
            'data' => $ferryForRoundTrips,
            'message' => __('Success'),
        ]);
    }
}
