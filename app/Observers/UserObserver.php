<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    public function created(User $user)
    {
        $request = request();

        if (in_array($request->role ?? 'buyer', ['admin', 'seller'])) {
            $user->is_active = false;
            $user->saveQuietly();
        }

        $user->syncRoles($request->role ?? 'buyer');
    }
}
