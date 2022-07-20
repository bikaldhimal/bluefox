<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('Settings')->insert([
            'name' => Str::random(10),
            'estd' => Str::random(10),
            'address' => Str::random(10),
            'zip' => Str::random(10),
            'mobile_number' => '977' . Str::random(10),
            'landline' => Str::random(10),
            'email' => Str::random(10) . '@gmail.com',
            'about_us' => Str::random(10),
            'social_account' => Str::random(10),
            'logo' => Str::random(10),
        ]);
    }
}
