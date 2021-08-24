<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\HelperController;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\User::factory()->create([
            'name' => 'User 1',
            'email' => 'user_1@example.com',
            'password' => Hash::make('password_1'),
        ]);

        \App\Models\User::factory()->create([
            'name' => 'User 2',
            'email' => 'user_2@example.com',
            'password' => Hash::make('password_2'),
        ]);


        //These are arranged according to how they need to be created
        //This is because I want to have consistency with foreign keys
        $this->call([
            AdvertisementSeeder::class,
        ]);
    }
}
