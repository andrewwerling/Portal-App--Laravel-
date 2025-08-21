<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BatteryInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class BatteryController extends Controller
{
    /**
     * Handle the incoming battery information request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * 
     * - 2024-07-22 - Andrew CEO Festival WiFi Guys - info+developer@festivalwifiguys.com
     */
    public function store(Request $request)
    {
        // Validate the request data based on the new JSON structure
        $validator = Validator::make($request->all(), [
            'unit_id' => 'required|string',
            'ip_address' => 'nullable|ip',
            'battery' => 'required|array',
            'battery.voltage' => 'required|numeric',
            'battery.current' => 'required|numeric',
            'battery.power' => 'required|numeric',
            'solar' => 'required|array',
            'solar.voltage' => 'required|numeric',
            'solar.current' => 'required|numeric',
            'solar.power' => 'required|numeric',
            'battery_percent' => 'required|numeric|min:0|max:100',
            'temperature_f' => 'required|numeric',
            'humidity_percent' => 'required|numeric|min:0|max:100',
            'relays' => 'required|array',
            'relays.R1' => 'required|string|in:ON,OFF',
            'relays.R2' => 'required|string|in:ON,OFF',
            'relays.R3' => 'required|string|in:ON,OFF',
            'relays.R4' => 'required|string|in:ON,OFF',
        ]);

        if ($validator->fails()) {
            // Log the validation errors
            Log::warning('Battery information validation failed', [
                'errors' => $validator->errors()->toArray(),
                'data' => $request->all(),
                'ip' => $request->ip()
            ]);

            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        // Log the received data
        Log::info('Battery information received', [
            'data' => $request->all(),
            'ip' => $request->ip()
        ]);

        try {
            // Store the data in the database using the new structure
            $batteryInfo = BatteryInformation::create([
                'unit_id' => $request->input('unit_id'),
                'ip_address' => $request->input('ip_address'),
                'battery_voltage' => $request->input('battery.voltage'),
                'battery_current' => $request->input('battery.current'),
                'battery_power' => $request->input('battery.power'),
                'solar_voltage' => $request->input('solar.voltage'),
                'solar_current' => $request->input('solar.current'),
                'solar_power' => $request->input('solar.power'),
                'battery_percent' => $request->input('battery_percent'),
                'temperature_f' => $request->input('temperature_f'),
                'humidity_percent' => $request->input('humidity_percent'),
                'relays' => $request->input('relays'), // Store the relays object/array as JSON
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Battery information stored successfully',
                'data' => $batteryInfo
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to store battery information', [
                'error' => $e->getMessage(),
                'data' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to store battery information',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
