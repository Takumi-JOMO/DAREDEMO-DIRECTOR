<?php

use Illuminate\Database\Seeder;

class AuthoritiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('authorities')->insert([
            ['name' => 'ディレクター'],
            ['name' => 'プログラマー／デザイナー'],
            ['name' => 'お客様'],
            ['name' => 'Admin'],
        ]

        );
    }
}
