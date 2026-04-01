<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\ForumThread;
use App\Models\ForumReply;
use App\Models\Marketplace;
use App\Models\Transaction;
use App\Models\Event;
use App\Models\Prestasi;
use App\Models\LostAndFound;
use App\Models\Repository;
use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SmeconeHubSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        // ==========================================
        // 1. SEED USERS
        // ==========================================
        $users = [];
        
        // Admin
        $users[] = User::create([
            'name' => 'Admin Smecone',
            'email' => 'admin@smecone.id',
            'password' => Hash::make('password123'),
            'nis' => 'ADMIN001',
            'is_admin' => true,
            'is_teacher' => false,
            'reputation_points' => 1000,
        ]);

        // Guru
        $users[] = User::create([
            'name' => 'Bapak Budi Santoso, S.Kom',
            'email' => 'budi.guru@smecone.id',
            'password' => Hash::make('password123'),
            'nis' => 'GURU001',
            'is_admin' => false,
            'is_teacher' => true,
            'reputation_points' => 500,
        ]);

        // Siswa Biasa (Ada 5)
        for ($i = 1; $i <= 5; $i++) {
            $users[] = User::create([
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


        // ==========================================
        // 2. SEED FORUM (ForumThread & ForumReply)
        // ==========================================
        $threads = [];
        $threadTitles = [
            'Tanya Error Laravel 11 nih, ada yang bisa bantu?',
            'Tips buat persiapan LKS Web Technologies IT Software',
            'Cara pakai React Hooks yang benar gimana ya?',
            'Ada yang punya referensi UI/UX untuk aplikasi kasir?'
        ];

        foreach ($threadTitles as $index => $title) {
            $thread = ForumThread::create([
                'user_id' => $users[array_rand($users)]->id,
                'title' => $title,
                'content' => $faker->paragraph(3),
                'is_solved' => $index % 2 == 0,
                'is_private' => false,
                'created_at' => Carbon::now()->subDays(rand(1, 10))
            ]);
            $threads[] = $thread;

            // Buat 2-3 balasan per thread
            $numReplies = rand(2, 4);
            for ($r = 0; $r < $numReplies; $r++) {
                $isBestAnswer = ($index % 2 == 0 && $r == 0) ? true : false; // Thread genap udah solved, reply pertama jadi best answer
                
                ForumReply::create([
                    'forum_thread_id' => $thread->id,
                    'user_id' => $users[array_rand($users)]->id,
                    'content' => $faker->paragraph(2),
                    'is_best_answer' => $isBestAnswer,
                    'created_at' => Carbon::now()->subDays(rand(1, 10))->addHours($r + 1)
                ]);
            }
        }


        // ==========================================
        // 3. SEED MARKETPLACE & TRANSACTIONS
        // ==========================================
        $items = [];
        $itemNames = ['Jasa Pembuatan Web Company Profile', 'Template Admin Dashboard Tailwind', 'Source Code Sistem Kasir (POS)', 'E-Book Panduan Mahir Laravel', 'Jasa Desain Logo Estetik'];
        $categories = ['Jasa', 'Digital', 'Digital', 'Digital', 'Jasa'];

        foreach ($itemNames as $i => $name) {
            $item = Marketplace::create([
                'user_id' => $users[rand(2, 4)]->id, // Hanya siswa 1-3 yang punya toko
                'item_name' => $name,
                'description' => $faker->paragraph(2),
                'price' => rand(50000, 500000),
                'category' => $categories[$i],
                'type' => 'digital',
                'is_sold' => false,
                'views_count' => rand(10, 100),
                'stock' => rand(5, 50),
                'created_at' => Carbon::now()->subDays(rand(1, 15))
            ]);
            $items[] = $item;
        }

        // Buat beberapa transaksi marketplace
        foreach ($items as $index => $item) {
            if ($index % 2 == 0) continue; // Jangan semua item punya transaksi

            $buyer = $users[rand(5, 6)]->id; // Siswa yang beda
            
            Transaction::create([
                'user_id' => $buyer,
                'marketplace_item_id' => $item->id,
                'invoice_id' => 'INV-' . strtoupper(Str::random(6)),
                'amount' => $item->price,
                'status' => 'SELESAI',
                'whatsapp_number' => '08122334455',
                'payment_method' => 'QRIS',
                'qty' => 1,
                'created_at' => Carbon::now()->subDays(rand(1, 5))
            ]);
        }


        // ==========================================
        // 4. SEED EVENTS
        // ==========================================
        $events = [
            ['Workshop UI/UX Figma Dasar', 'Workshop pengenalan figma untuk pemula...', 'Lab Komputer 1', 'Workshop'],
            ['Lomba Koding Internal Smecone', 'Lomba untuk menyeleksi kandidat perwakilan sekolah...', 'Aula Smecone', 'Lomba'],
            ['Seminar Persiapan Masa Depan IT', 'Seminar bersama alumni yang sudah sukses di industri...', 'Aula Smecone', 'Seminar']
        ];

        foreach ($events as $e) {
            $event = Event::create([
                'judul' => $e[0],
                'deskripsi' => $e[1] . "\n\n" . $faker->paragraph(),
                'lokasi' => $e[2],
                'kategori' => $e[3],
                'tanggal_event' => Carbon::now()->addDays(rand(5, 30)),
            ]);

            // Add dummy comments & likes to Event
            $event->comments()->create([
                'user_id' => $users[array_rand($users)]->id,
                'content' => 'Wah wajib ikut nih, keren banget acaranya!',
            ]);
            $event->likes()->create(['user_id' => $users[array_rand($users)]->id]);
            $event->likes()->create(['user_id' => $users[array_rand($users)]->id]);
        }


        // ==========================================
        // 5. SEED PRESTASI
        // ==========================================
        $prestasis = [
            ['Juara 1 LKS Web Technologies Provinsi 2024', 'Fana Jalaludin', 'Juara 1', 'Provinsi'],
            ['Harapan 1 LKS IT Software 2024', 'Ahmad Rizky', 'Harapan 1', 'Nasional'],
            ['Medali Emas O2SN Futsal', 'Tim Futsal Smecone', 'Juara 1', 'Kabupaten']
        ];

        foreach ($prestasis as $p) {
            $prestasi = Prestasi::create([
                'user_id' => $users[rand(2, 6)]->id,
                'judul' => $p[0],
                'deskripsi' => $faker->paragraph(2),
                'nama_pemenang' => $p[1],
                'kategori_juara' => $p[2],
                'tingkat' => $p[3],
                'tanggal' => Carbon::now()->subMonths(rand(1, 12))
            ]);

            // Add dummy likes
            $prestasi->likes()->create(['user_id' => $users[array_rand($users)]->id]);
        }


        // ==========================================
        // 6. SEED LOST AND FOUND
        // ==========================================
        $lafItems = [
            ['Ditemukan Kunci Motor Honda', 'Ditemukan di parkiran dekat kantin, ada gantungan kunci beruang.', 'found', 'open'],
            ['Kehilangan Flashdisk Sandisk 32GB', 'Flashdisk warna merah hitam, terakhir dipakai di Lab Komputer 2.', 'lost', 'open'],
            ['Ditemukan Dompet Hitam', 'Berisi KTP, KTM dan beberapa kartu. Ditemukan di lapangan.', 'found', 'resolved']
        ];

        foreach ($lafItems as $laf) {
            LostAndFound::create([
                'user_id' => $users[array_rand($users)]->id,
                'item_name' => $laf[0],
                'description' => $laf[1],
                'type' => $laf[2],
                'status' => $laf[3],
                'resolved_by' => $laf[3] == 'resolved' ? $users[0]->id : null // Diselesaikan admin
            ]);
        }


        // ==========================================
        // 7. SEED REPOSITORY
        // ==========================================
        $repos = [
            ['Sistem Presensi QR Code', 'Sistem presensi siswa berbasis QR Code menggunakan Laravel dan Next.js', 'public'],
            ['Aplikasi Kasir Berbasis Web', 'Point of Sales ringan untuk warung dan minimarket', 'public'],
            ['Smecone Smart School Private API', 'Backend API untuk Smecone Mobile', 'private']
        ];

        foreach ($repos as $r) {
            Repository::create([
                'user_id' => $users[array_rand($users)]->id,
                'name' => $r[0],
                'description' => $r[1],
                'visibility' => $r[2],
                'major' => 'RPL',
                'downloads_count' => rand(5, 50),
                'created_at' => Carbon::now()->subDays(rand(10, 60))
            ]);
        }

        // Output message
        $this->command->info('✅ Smecone Hub Dummy Data Seeded Successfully!');
    }
}
