<?php

namespace Database\Seeders;

use App\Models\RepairCompany;
use Illuminate\Database\Seeder;

class RepairCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = [
            'AUTOCENTRAS ŽIBINTAS',
            'AUTOMOBILIŲ PRIEŽIŪROS CENTRAS',
            'AUTIDA',
            'AKSTĖ SERVISAS',
            'VISODA AUTO',
            'AUREMAS',
            'KRETINGOS SMAGRATIS',
            'AUTOGEDAS',
            'GRANDSFERA',
        ];

        foreach ($companies as $company) {
            RepairCompany::firstOrCreate(
                ['title' => $company],
                ['title' => $company]
            );
        }
    }
}
