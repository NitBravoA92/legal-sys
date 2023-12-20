<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public function run()
    {
        //
        DB::table('settings')->insert([
            'id' => 1,
            'app_name' => 'Legal Sys.',
            'app_owner' => 'Modern Solutions Group',
            'app_address' => 'Not available',
            'app_phone' => 'Not available',
            'app_email' => 'Not available',
            'app_logo' => '',
            'about_us' => 'Not available',
            'language' => 'es',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }
}
