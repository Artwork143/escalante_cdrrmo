<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalCase extends Model
{
    use HasFactory;

    // Define the table name if it's different from 'medical_cases'
    protected $table = 'medical_cases';

    // Specify the fillable fields
    protected $fillable = [
        'date',
        'rescue_team',
        'place_of_incident',
        'barangay',
        'no_of_patients',
        'chief_complaints',
        'facility_name',
        'is_approved',
        'city',  // Add 'city' to the fillable array
    ];

    /**
     * Get all accidents for a specific barangay.
     */
    public static function getMedicalsCountByBarangay($barangay)
    {
        return self::where('barangay', $barangay)
        ->where('is_approved', 1)
        ->count();
    }
}
