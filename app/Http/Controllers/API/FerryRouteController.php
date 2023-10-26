<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\FerryRouteRequest;
use App\Models\FerryRoute;

class FerryRouteController extends Controller
{
    public function index()
    {
        $ferryRoutes = FerryRoute::orderByDesc('created_at')
            ->get();

        return response()->json([
            'data' => $ferryRoutes,
            'message' => __('Success'),
        ]);
    }

    public function create(FerryRouteRequest $request)
    {
        $data = $request->validated();
        $ferryRoute = FerryRoute::create($data);

        return response()->json([
            'data' => $ferryRoute,
            'message' => __('Success'),
        ]);
    }

    public function edit(FerryRouteRequest $request, $id)
    {
        $data = $request->validated();
        $ferryRoute = FerryRoute::findOrFail($id);

        $ferryRoute->update($data);
        return response()->json([
            'data' => $ferryRoute,
            'message' => __('Success'),
        ]);
    }

    public function delete($id)
    {
        $ferryRoute = FerryRoute::findOrFail($id);
        $ferryRoute->delete();

        return response()->json([
            'message' => __('Success'),
        ]);
    }
}
