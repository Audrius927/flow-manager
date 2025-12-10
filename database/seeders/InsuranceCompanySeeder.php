<?php

namespace Database\Seeders;

use App\Models\InsuranceCompany;
use Illuminate\Database\Seeder;

class InsuranceCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $companies = [
            'COMPENSA',
            'BTA',
            'KITA',
        ];

        foreach ($companies as $company) {
            InsuranceCompany::firstOrCreate(
                ['title' => $company],
                ['title' => $company]
            );
        }
    }
}
