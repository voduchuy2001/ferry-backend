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
        return response()->json([
            'data' => $ferry,
            'message' => __('Success'),
        ]);
    }

    public function delete($id)
    {
        $ferry = Ferry::findOrFail($id);
        $ferry->delete();

        return response()->json([
            'message' => __('Success'),
        ]);
    }
}
