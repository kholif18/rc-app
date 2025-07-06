<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AppSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AppSetting::set('app_name', 'RC App');
        AppSetting::set('app_version', '1.1.0');
        AppSetting::set('update_url', null); 
    }
}
