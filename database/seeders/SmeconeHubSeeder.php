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
        User::updateOrCreate(
            ['email' => 'admin@smecone.id'],
            [
                'name' => 'Admin Smecone',
                'password' => Hash::make('password123'),
                'nis' => 'ADMIN001',
                'is_admin' => true,
                'is_teacher' => false,
                'reputation_points' => 1000,
            ]
        );

        // Guru
        User::updateOrCreate(
            ['email' => 'budi.guru@smecone.id'],
            [
                'name' => 'Bapak Budi Santoso, S.Kom',
                'password' => Hash::make('password123'),
                'nis' => 'GURU001',
                'is_admin' => false,
                'is_teacher' => true,
                'reputation_points' => 500,
            ]
        );

        // Siswa (5 orang)
        for ($i = 1; $i <= 5; $i++) {
            User::updateOrCreate(
                ['email' => "siswa{$i}@smecone.id"],
                [
                    'name' => $faker->name,
                    'password' => Hash::make('password123'),
                    'nis' => '1000' . $i,
                    'is_admin' => false,
                    'is_teacher' => false,
                    'reputation_points' => rand(10, 100),
                    'store_name' => $i <= 3 ? "Toko " . $faker->firstName : null,
                    'whatsapp_number' => '08123456789' . $i,
                ]
            );
        }

        $this->command->info('✅ User accounts seeded successfully!');

        // ==========================================
        // SEED PRESTASI
        // ==========================================
        $prestasiData = [
            ['LKS Web Technologies 2026', 'Juara 1', 'Nasional', 'https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=800&q=80'],
            ['Lomba Basket Antar Sekolah', 'Juara 2', 'Provinsi', 'https://images.unsplash.com/photo-1546519638-68e109498ffc?w=800&q=80'],
            ['Olimpiade Matematika', 'Juara Harapan 1', 'Nasional', 'https://images.unsplash.com/photo-1503676260728-1c00da094a0b?w=800&q=80'],
            ['Hackathon Pelajar 2026', 'Juara 1', 'Nasional', 'https://images.unsplash.com/photo-1504384308090-c894fdcc538d?w=800&q=80'],
        ];

        foreach ($prestasiData as $i => $data) {
            \App\Models\Prestasi::create([
                'user_id' => rand(3, 7), // Random siswa
                'judul' => $data[0],
                'deskripsi' => $faker->paragraph,
                'nama_pemenang' => $faker->name,
                'kategori_juara' => $data[1],
                'tingkat' => $data[2],
                'tanggal' => $faker->dateTimeBetween('-1 year', 'now'),
                'gambar' => [$this->downloadImage($data[3], 'prestasi')],
            ]);
        }
        $this->command->info('✅ Prestasi seeded successfully!');

        // ==========================================
        // SEED EVENTS
        // ==========================================
        $eventData = [
            ['Pensi Smecone 2026', 'Seni & Budaya', 'Lapangan Utama', 'https://images.unsplash.com/photo-1459749411175-04bf5292ceea?w=800&q=80'],
            ['Job Fair SMK', 'Pendidikan', 'Aula Sekolah', 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800&q=80'],
            ['Bakti Sosial Smecone', 'Sosial', 'Lingkungan Sekitar', 'https://images.unsplash.com/photo-1593113580332-ce28892f11f5?w=800&q=80'],
        ];

        foreach ($eventData as $i => $data) {
            \App\Models\Event::create([
                'judul' => $data[0],
                'deskripsi' => $faker->paragraph,
                'kategori' => $data[1],
                'lokasi' => $data[2],
                'tanggal_event' => $faker->dateTimeBetween('now', '+3 months'),
                'gambar' => [$this->downloadImage($data[3], 'events')],
            ]);
        }
        $this->command->info('✅ Events seeded successfully!');

        // ==========================================
        // SEED MARKETPLACE
        // ==========================================
        $marketData = [
            ['Jasa Pembuatan Web Profile', 'Jasa', 'Digital', 150000, 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?w=800&q=80'],
            ['Buku Catatan Pemrograman', 'Alat Tulis', 'Fisik', 35000, 'https://images.unsplash.com/photo-1544947950-fa07a98d237f?w=800&q=80'],
            ['Template PPT Premium', 'Lainnya', 'Digital', 20000, 'https://images.unsplash.com/photo-1557804506-669a67965ba0?w=800&q=80'],
            ['Snack Makaroni Pedas', 'Makanan', 'Fisik', 5000, 'https://images.unsplash.com/photo-1621939514649-280e2ee25f60?w=800&q=80'],
            ['Jasa Desain Poster', 'Jasa', 'Digital', 50000, 'https://images.unsplash.com/photo-1626785774573-4b799315345d?w=800&q=80'],
        ];

        foreach ($marketData as $i => $data) {
            \App\Models\Marketplace::create([
                'user_id' => rand(3, 5), // User siswa yang punya toko
                'item_name' => $data[0],
                'description' => $faker->paragraph,
                'price' => $data[3],
                'image' => $this->downloadImage($data[4], 'marketplaces'),
                'category' => $data[1],
                'type' => 'Ready Stock',
                'location' => $data[2] === 'Digital' ? 'Online' : 'Kelas',
                'is_sold' => false,
                'stock' => $data[2] === 'Digital' ? 999 : rand(5, 50),
                'format' => $data[2],
                'digital_link' => $data[2] === 'Digital' ? 'https://google.com' : null,
                'views_count' => rand(10, 500),
            ]);
        }
        $this->command->info('✅ Marketplace items seeded successfully!');
    }

    private function downloadImage($url, $folder)
    {
        try {
            $contents = file_get_contents($url);
            if ($contents) {
                $filename = $folder . '/' . uniqid() . '.jpg';
                \Illuminate\Support\Facades\Storage::disk('public')->put($filename, $contents);
                return $filename;
            }
        } catch (\Exception $e) {
            // Fallback
        }
        return 'default.jpg';
    }
}
