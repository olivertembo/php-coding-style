<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Http\Controllers\HelperController;
use Illuminate\Support\Facades\DB;

class AdvertisementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        for ($i=1; $i < 3; $i++) {
            DB::table('advertisements')->insert([
                'uuid' => HelperController::generateUniquePublicId('advertisements', 'uuid'),
                'title' => "Title $i",
                'description' => "description $i Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.",
                'photo_1'  => null,
                'photo_2'=> null, //https://picsum.photos/seed/picsum/200
                'photo_3'=> null,
                'user_id' => $i,
            ]);
        }
    }
}
