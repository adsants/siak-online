<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;


class SoalAngkaHilangsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');
        for($i = 1; $i <= 250; $i++){
            // insert data ke table siswa menggunakan Faker
            \DB::table('soal_angka_hilangs')->insert([
                'angka_satu' => $faker->numberBetween(1,9),
                'angka_dua' => $faker->numberBetween(1,9),
                'angka_tiga' => $faker->numberBetween(1,9),
                'angka_empat' => $faker->numberBetween(1,9),
                'angka_benar' => $faker->numberBetween(1,9)
            ]);
        }
        //
    }
}
