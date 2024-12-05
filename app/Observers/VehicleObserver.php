<?php

namespace App\Observers;
use App\Models\Vehicle;
use App\Models\VehicleHasSeat;

class VehicleObserver
{
    public function created(Vehicle $vehicle)
    {
        switch ($vehicle->seat_formation_code) {
            case 'WONDER_DEFAULT':
                $seat_count = 14;
                $prefix = "SEAT_";
                for ($i = 1; $i <= $seat_count; $i++) {
                    VehicleHasSeat::create([
                        'vehicle_id' => $vehicle->id,
                        'code' => $prefix . $i,
                        'description' => 'Seat #' . $i,
                        'is_active' => true,
                    ]);
                }
                break;
        }
    }
    public function updated(Vehicle $vehicle)
    {
        if (isset($vehicle->getDirty()['seat_formation_code'])) {
            $vehicle->seats()->delete();
            switch ($vehicle->seat_formation_code) {
                case 'WONDER_DEFAULT':
                    $seat_count = 14;
                    $prefix = "SEAT_";
                    for ($i = 1; $i <= $seat_count; $i++) {
                        VehicleHasSeat::create([
                            'vehicle_id' => $vehicle->id,
                            'code' => $prefix . $i,
                            'description' => 'Seat #' . $i,
                            'is_active' => true,
                        ]);
                    }
                    break;
            }
        }
    }
}
