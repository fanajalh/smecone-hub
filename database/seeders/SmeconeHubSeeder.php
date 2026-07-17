<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Faker\Factory as Faker;

class SmeconeHubSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // ==========================================
        // SEED USERS ONLY
        // ==========================================

        // Admin
        User::create([
            'name' => 'Admin Smecone',
            'email' => 'admin@smecone.id',
            'password' => Hash::make('password123'),
            'nis' => 'ADMIN001',
            'is_admin' => true,
            'is_teacher' => false,
            'reputation_points' => 1000,
        ]);

        // Guru
        User::create([
            'name' => 'Bapak Budi Santoso, S.Kom',
            'email' => 'budi.guru@smecone.id',
            'password' => Hash::make('password123'),
            'nis' => 'GURU001',
            'is_admin' => false,
            'is_teacher' => true,
            'reputation_points' => 500,
        ]);

        // Siswa (5 orang)
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => $faker->name,
                'email' => "siswa{$i}@smecone.id",
                'password' => Hash::make('password123'),
                'nis' => '1000' . $i,
                'is_admin' => false,
                'is_teacher' => false,
                'reputation_points' => rand(10, 100),
                'store_name' => $i <= 3 ? "Toko " . $faker->firstName : null,
                'whatsapp_number' => '08123456789' . $i,
            ]);
        }

        $this->command->info('✅ User accounts seeded successfully!');
    }
}
