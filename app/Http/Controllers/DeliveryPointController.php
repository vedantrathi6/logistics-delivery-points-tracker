<?php

namespace App\Http\Controllers;

use App\Models\DeliveryPoint;
use App\Services\WeatherService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeliveryPointController extends Controller
{
    protected $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $deliveryPoints = DeliveryPoint::all();
        
        $deliveryPoints->each(function ($point) {
            $weather = $this->weatherService->getWeatherForCity($point->city);
            $point->weather = $weather;
        });

        return response()->json($deliveryPoints);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'city' => 'required|string',
            'contact_person' => 'required|string',
            'contact_number' => 'nullable|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $deliveryPoint = DeliveryPoint::create($request->all());
        return response()->json($deliveryPoint, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $deliveryPoint = DeliveryPoint::findOrFail($id);
        $weather = $this->weatherService->getWeatherForCity($deliveryPoint->city);
        $deliveryPoint->weather = $weather;

        return response()->json($deliveryPoint);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
