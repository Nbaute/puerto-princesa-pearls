<?php

namespace App\Observers;
use App\Models\VanBookingPassenger;
use App\Models\VehicleHasSeat;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class VanBookingPassengerObserver
{
    public function updated(VanBookingPassenger $vanBookingPassenger)
    {
        if ($vanBookingPassenger->isDirty('status')) {
            $newValues = $vanBookingPassenger->getDirty();
            $newStatus = $newValues['status'];
            if ($vanBookingPassenger->getOriginal('status') !== $newStatus) {
                switch ($newStatus) {
                    case 'cancelled':
                        if ($vanBookingPassenger->payment_method?->slug !== 'cash') {
                            //TODO: initiate refund process

                            // free the seat
                            $vanBookingPassenger->seat->update(
                                ['occupied_at' => null, 'occupant_id' => null],
                            );

                            //TODO: log
                        }
                        break;
                }
            }
        }
    }

    public function creating(VanBookingPassenger $vanBookingPassenger)
    {
        $lockSeatKey = "seat_{$vanBookingPassenger->seat_id}";
        $cache = Cache::lock($lockSeatKey, 3);
        if (!$cache->get()) {
            throw new Exception("{$vanBookingPassenger->seat_code} is already occupied!");
        }
        $seat = $vanBookingPassenger->van_booking->vehicle->seats()->whereNull('occupied_at')->where('id', $vanBookingPassenger->seat_id)->firstOr(function () use ($vanBookingPassenger) {
            throw new Exception("{$vanBookingPassenger->seat_code} is already occupied!");
        });

        $seat->update(
            [
                'occupied_at' => now(),
                'occupant_id' => $vanBookingPassenger->id,
            ],
        );

        // TODO: log
    }
}
