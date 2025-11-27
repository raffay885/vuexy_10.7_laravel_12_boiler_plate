<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::updateOrCreate([], [
            'syncro_subdomain' => 'Clientportal',
            'syncro_domain' => 'syncromsp.com',
            'syncro_api_key' => 'Te8b1e677e7b3d39ae-2862aada466bc665b6ce2921a9e2cb0f',
            'eset_base_url' => 'https://mspapi.eset.com/api',
            'eset_username' => 'esetsyncro@computeq.ie',
            'eset_password' => 'ePq49Kr826fW4',
        ]);
    }
}
