<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Faker\Factory as Faker;

class UserSeeder2 extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Inisialisasi Faker dengan lokalisasi Indonesia
        $faker = Faker::create('id_ID');

        $users = [];
        $now = Carbon::now();

        // Lakukan perulangan sebanyak 200 kali
        for ($i = 0; $i < 200; $i++) {
            
            // Gabungan nama depan & belakang acak agar terdengar modern/Gen Z
            $firstName = $faker->firstName;
            $lastName = $faker->lastName;
            $name = $firstName . ' ' . $lastName;

            // Menyusun email tiruan yang rapi (contoh: dimas.saputra2841@gmail.com)
            $emailPrefix = strtolower($firstName . '.' . $lastName);
            $emailPrefix = preg_replace('/[^a-z.]/', '', $emailPrefix); // Memastikan hanya huruf dan titik
            $email = $emailPrefix . $faker->unique()->numerify('####') . '@gmail.com'; // Tambah 4 angka unik di belakang

            $users[] = [
                'name'              => $name,
                'email'             => $email,
                'email_verified_at' => $now,
                'password'          => Hash::make('aksesklikrental'), // Password default sesuai request
                'phone_number'      => $faker->numerify('08##########'), // 12 digit acak berawalan 08
                'nik'               => $faker->unique()->numerify('################'), 
                'address'           => $faker->address(), // Alamat acak wilayah Indonesia
                'ktp_image_url'     => null,
                'sim_image_url'     => null,
                'role'              => 'customer',
                'google_id'         => null,
                'remember_token'    => Str::random(10),
                'created_at'        => $now,
                'updated_at'        => $now,
            ];
        }

        // Menggunakan array_chunk (dibagi per 50 data) saat insert 
        // agar eksekusi query ke database lebih ringan dan cepat
        foreach (array_chunk($users, 50) as $chunk) {
            DB::table('users')->insert($chunk);
        }
    }
}