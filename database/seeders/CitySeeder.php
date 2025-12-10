<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cities = [
            'VILNIUS',
            'KAUNAS',
            'KLAIPĖDA',
            'ŠIAULIAI',
            'PANEVĖŽYS',
            'ALYTUS',
            'UKMERGĖ',
            'MOLĖTAI',
            'KRETINGA',
            'UTENA',
        ];

        foreach ($cities as $city) {
            City::firstOrCreate(
                ['title' => $city],
                ['title' => $city]
            );
        }
    }
}
