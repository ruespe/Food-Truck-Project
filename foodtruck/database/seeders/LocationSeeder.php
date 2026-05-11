<?php

namespace Database\Seeders;

use App\Models\Location;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            [
                'name'       => 'Plaça de Catalunya, Barcelona',
                'latitude'   => 41.3870154,
                'longitude'  => 2.1700471,
                'date'       => Carbon::now()->subDays(14)->toDateString(),
                'start_time' => '19:00:00',
                'end_time'   => '02:00:00',
            ],
            [
                'name'       => 'Parc de la Ciutadella, Barcelona',
                'latitude'   => 41.3863200,
                'longitude'  => 2.1863400,
                'date'       => Carbon::now()->subDays(7)->toDateString(),
                'start_time' => '20:00:00',
                'end_time'   => '03:00:00',
            ],
            [
                'name'       => 'Barceloneta Beach, Barcelona',
                'latitude'   => 41.3780000,
                'longitude'  => 2.1920000,
                'date'       => Carbon::now()->subDays(3)->toDateString(),
                'start_time' => '19:30:00',
                'end_time'   => '01:00:00',
            ],
            [
                'name'       => 'Mercat de Sant Antoni, Barcelona',
                'latitude'   => 41.3793000,
                'longitude'  => 2.1620000,
                'date'       => Carbon::now()->toDateString(),
                'start_time' => '19:00:00',
                'end_time'   => '07:00:00',
            ],
            [
                'name'       => 'Parc del Turó, Barcelona',
                'latitude'   => 41.4010000,
                'longitude'  => 2.1390000,
                'date'       => Carbon::now()->addDays(3)->toDateString(),
                'start_time' => '20:00:00',
                'end_time'   => '04:00:00',
            ],
            [
                'name'       => 'Fira de Cornellà, Cornellà de Llobregat',
                'latitude'   => 41.3548000,
                'longitude'  => 2.0721000,
                'date'       => Carbon::now()->addDays(7)->toDateString(),
                'start_time' => '19:00:00',
                'end_time'   => '02:00:00',
            ],
            [
                'name'       => "Mercat de l'Hospitalet, L'Hospitalet de Llobregat",
                'latitude'   => 41.3598000,
                'longitude'  => 2.1003000,
                'date'       => Carbon::now()->addDays(14)->toDateString(),
                'start_time' => '21:00:00',
                'end_time'   => '05:00:00',
            ],
        ];

        foreach ($locations as $location) {
            Location::create($location);
        }
    }
}
