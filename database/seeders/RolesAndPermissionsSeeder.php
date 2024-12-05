<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();


        $buyer = Role::firstOrCreate(['name' => 'buyer']);
        $seller = Role::firstOrCreate(['name' => 'seller']);
        $admin = Role::firstOrCreate(['name' => 'admin']);
    }
}
