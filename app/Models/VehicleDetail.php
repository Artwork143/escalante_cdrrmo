<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VehicleDetail extends Model
{
    protected $fillable = ['vehicular_accident_id', 'vehicle_type', 'vehicle_detail'];

    // Define the inverse relationship to VehicularAccident
    public function vehicularAccident()
    {
        return $this->belongsTo(VehicularAccident::class);
    }
}

?>