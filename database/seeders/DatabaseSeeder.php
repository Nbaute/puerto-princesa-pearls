<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Throwable;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            AuthProviderSeeder::class,
        ]);
        $users = [
            [
                'name' => 'PPC Pearls',
                'email' => 'puertoprincesapearls@gmail.com',
                'phone' => '+639111111110',
                'password' => 'password',
            ],
            [
                'name' => 'Hannies',
                'email' => 'hannies@gmail.com',
                'phone' => '+639111111111',
                'password' => 'password',
            ],
            [
                'name' => 'Oden',
                'email' => 'oden@gmail.com',
                'phone' => '+639111111112',
                'password' => 'password',
            ],
            [
                'name' => 'Chamz',
                'email' => 'chamz@gmail.com',
                'phone' => '+639111111113',
                'password' => 'password',
            ],
        ];
        foreach ($users as $user) {
            try {

                User::factory()->create($user);
            } catch (Throwable $t) {
            }
        }
    }
}