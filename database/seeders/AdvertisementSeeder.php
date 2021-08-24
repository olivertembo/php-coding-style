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

        for ($i=0; $i < 2; $i++) {
            DB::table('advertisements')->insert([
                'uuid' => HelperController::generateUniquePublicId('advertisements', 'uuid'),
                'title' => "Title $i",
                'description' => "Title $i",
                'photo_1'  => "https://picsum.photos/100/300",
                'photo_2'=> "https://picsum.photos/100/300",
                'photo_3'=> "https://picsum.photos/100/300",
                'user_id' => $i,
            ]);
        }
    }
}
