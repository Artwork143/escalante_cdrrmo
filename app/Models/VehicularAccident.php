<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicularAccident extends Model
{
    use HasFactory;

    // Define the table associated with the model (optional if you follow Laravel's naming convention)
    protected $table = 'vehicular_accidents';

    // The attributes that are mass assignable
    protected $fillable = [
        'date',
        'rescue_team',
        'place_of_incident',
        'barangay',
        'no_of_patients',
        'cause_of_incident',
        'vehicles_involved',
        'facility_name',
        'is_approved',
        'city',
    ];

    /**
     * Get all accidents for a specific barangay.
     */
    public static function getAccidentsCountByBarangay($barangay)
    {
        return self::where('barangay', $barangay)
            ->where('is_approved', 1)
            ->count();
    }

    /**
     * Define the relationship between VehicularAccident and VehicleDetail.
     * Each accident can have multiple vehicles.
     */
    public function vehicles()
    {
        return $this->hasMany(VehicleDetail::class);
    }

    public function vehicleDetails()
    {
        return $this->hasMany(VehicleDetail::class, 'vehicular_accident_id');
    }
}