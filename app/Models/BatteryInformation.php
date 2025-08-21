<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatteryInformation extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'battery_information';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     * 
     * - 2024-07-22 - Andrew CEO Festival WiFi Guys - info+developer@festivalwifiguys.com
     */
    protected $fillable = [
        'unit_id',
        'ip_address',
        'battery_voltage',
        'battery_current',
        'battery_power',
        'solar_voltage',
        'solar_current',
        'solar_power',
        'battery_percent',
        'temperature_f',
        'humidity_percent',
        'relays',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'battery_voltage' => 'float',
        'battery_current' => 'float',
        'battery_power' => 'float',
        'solar_voltage' => 'float',
        'solar_current' => 'float',
        'solar_power' => 'float',
        'battery_percent' => 'float',
        'temperature_f' => 'float',
        'humidity_percent' => 'float',
        'relays' => 'array', // Cast the JSON column to an array
    ];
}
