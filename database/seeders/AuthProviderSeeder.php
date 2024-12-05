<?php

namespace Database\Seeders;

use App\Models\AuthProvider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuthProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $authProviders = [
            [
                'name' => 'Custom',
                'slug' => 'custom',
                'is_active' => 1,
            ],
            [
                'name' => 'Google',
                'slug' => 'google',
                'is_active' => 1,
            ],
        ];
        foreach ($authProviders as $authProvider) {
            AuthProvider::firstOrCreate($authProvider);
        }
    }
}
