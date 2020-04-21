<?php

use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        setting(['delivery_fee.usd' => 299]);
        setting(['delivery_fee.eur' => 249]);
        setting()->save();
    }
}
