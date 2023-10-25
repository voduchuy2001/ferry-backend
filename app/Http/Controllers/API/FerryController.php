<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\FerryRequest;
use App\Models\Ferry;

class FerryController extends Controller
{
    public function index()
    {
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

    public function edit($id)
    {
    }

    public function delete($id)
    {
    }
}
