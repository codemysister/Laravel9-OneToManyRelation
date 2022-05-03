<?php

namespace Database\Seeders;

use App\Models\Jurusan;
use App\Models\Mahasiswa;
use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        Jurusan::create(
            [
                'nama' => 'Ilmu Komputer',
                'kepala_jurusan' => 'Dr. Syahrial, M.Kom',
                'daya_tampung' => 120,
            ]
        );

        Jurusan::create(
            [
                'nama' => 'Teknik Informatika',
                'kepala_jurusan' => 'Prof. Mulyono',
                'daya_tampung' => 250,
            ]
        );

        Jurusan::create(
            [
                'nama' => 'Sistem Informasi',
                'kepala_jurusan' => 'Dr. Umar Agustinus, M.Sc.',
                'daya_tampung' => 90,
            ]
        );

        Jurusan::create(
            [
                'nama' => 'Teknik Komputer',
                'kepala_jurusan' => 'Prof. Gunarto, M.T',
                'daya_tampung' => 60,
            ]
        );

        $faker = Factory::create('id_ID');
        $faker->seed(123);

        for ($i = 0; $i < 10; $i++) {
            Mahasiswa::create(
                [
                    'nim' => $faker->unique()->numerify('10######'),
                    'nama' => $faker->firstName . " " . $faker->lastName,
                    'jurusan_id' => $faker->numberBetween(1, 3),
                ]
            );
        }
    }
}
