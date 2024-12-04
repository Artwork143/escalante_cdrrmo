<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disaster extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'disasters';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'date',
        'rescue_team',
        'place_of_incident',
        'city',
        'barangay',
        'type',
        'affected_infrastructure',
        'casualties',
        'is_approved',
        'current_water_level', // Flood
        'water_level_trend',   // Flood (Rising/Falling)
        'intensity_level',     // Earthquake
        'aftershocks',         // Earthquake
        'eruption_type',       // Volcanic Eruption
        'eruption_intensity',  // Volcanic Eruption
        'involved_parties',    // Rebel Encounter
        'triggering_event',    // Rebel Encounter
        'nature_of_encounter', // Rebel Encounter
        'duration',            // Rebel Encounter
    ];

    /**
     * Custom attribute logic based on the disaster type.
     */
    public function getDynamicAttributes()
    {
        $dynamicAttributes = [];
        
        switch ($this->type) {
            case 'Flood':
                $dynamicAttributes = [
                    'current_water_level' => $this->current_water_level,
                    'water_level_trend' => $this->water_level_trend,
                ];
                break;

            case 'Earthquake':
                $dynamicAttributes = [
                    'intensity_level' => $this->intensity_level,
                    'aftershocks' => $this->aftershocks,
                ];
                break;

            case 'Volcanic Eruption':
                $dynamicAttributes = [
                    'eruption_type' => $this->eruption_type,
                    'eruption_intensity' => $this->eruption_intensity,
                ];
                break;

            case 'Rebel Encounter':
                $dynamicAttributes = [
                    'involved_parties' => $this->involved_parties,
                    'triggering_event' => $this->triggering_event,
                    'nature_of_encounter' => $this->nature_of_encounter,
                    'duration' => $this->duration,
                ];
                break;

            default:
                $dynamicAttributes = [];
                break;
        }

        return $dynamicAttributes;
    }
}
