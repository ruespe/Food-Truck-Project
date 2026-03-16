<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        Location::create([
            'name'       => 'Plaza Mayor',
            'latitude'   => 40.4153400,
            'longitude'  => -3.7074100,
            'date'       => now()->toDateString(),
            'start_time' => '12:00:00',
            'end_time'   => '22:00:00',
        ]);
    }
}
