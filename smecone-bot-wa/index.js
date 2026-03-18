const { Client, LocalAuth } = require('whatsapp-web.js');
const qrcode = require('qrcode-terminal');
const express = require('express');
const cors = require('cors');

// Inisialisasi Express API
const app = express();
app.use(cors());
app.use(express.json()); // Agar bisa membaca data JSON dari Laravel

// Inisialisasi Bot WA (Tanpa executablePath karena sudah pakai Chromium bawaan)
const client = new Client({
    authStrategy: new LocalAuth(), // Menyimpan sesi login agar tidak perlu scan QR terus
    puppeteer: {
        headless: true,
        args: ['--no-sandbox', '--disable-setuid-sandbox']
    }
});

// 1. Memunculkan QR Code
client.on('qr', (qr) => {
    console.log('\n=========================================');
    console.log('📱 SCAN QR CODE INI PAKAI WHATSAPP KAMU 📱');
    console.log('=========================================\n');
    qrcode.generate(qr, { small: true });
});

// 2. Saat Bot Berhasil Login & Siap
client.on('ready', async () => {
    console.log('\n✅ Bot WhatsApp Smecone sudah Aktif dan Siap!');

    // ==============================================================
    // 🔥 FITUR TEST KIRIM PESAN OTOMATIS KE GRUP SPESIFIK 🔥
    // ==============================================================
    const targetGroupId = '120363425273294200@g.us'; 
    const testMessage = 'Halo teman-teman! 🤖\nBot Smecone Hub telah berhasil terhubung ke grup ini dan siap meneruskan iklan lapak kalian! 🚀';

    try {
        await client.sendMessage(targetGroupId, testMessage);
        console.log(`\n✅ [TEST SUKSES] Pesan sapaan otomatis berhasil dikirim ke grup target.`);
    } catch (error) {
        console.log(`\n❌ [TEST GAGAL] Tidak bisa mengirim ke grup. Pastikan nomor bot sudah di-invite!`);
    }
    // ==============================================================
});

// ==========================================
// DAFTAR NOMOR ADMIN (Wajib format 628xxx@c.us)
// ==========================================
const ADMIN_NUMBERS = [
    '6285728150223@c.us', // Ganti dengan nomor WA kamu! (Pakai 62, bukan 0)
    // '6289876543210@c.us', // Buka komentar ini kalau mau nambah admin ke-2
];

client.on('message', async message => {
    // Ambil nomor WA si pengirim pesan
    const senderNumber = message.author || message.from; 
    const text = message.body.toLowerCase();

    // Cek apakah nomor pengirim ada di dalam daftar ADMIN_NUMBERS
    const isAdmin = ADMIN_NUMBERS.includes(senderNumber);

    // ==========================================
    // 🛡️ ZONA KHUSUS ADMIN 🛡️
    // ==========================================
    if (isAdmin) {
        
        // 1. Menampilkan Menu
        if (text === '!menu') {
            const menuText = `*🛠️ MENU ADMIN SMECONE 🛠️*\n\n` +
                             `Halo Admin! Silakan balas dengan angka:\n\n` +
                             `*1️⃣* Cek ID Grup Sini\n` +
                             `*2️⃣* Cek Status Bot\n` +
                             `*3️⃣* Test Tag Semua Member\n\n` +
                             `_(Pesan ini rahasia, hanya Admin yang bisa pakai)_`;
            
            message.reply(menuText);
        }
        
        // 2. Eksekusi Pilihan 1 (Cek ID Grup)
        else if (text === '1') {
            const chat = await message.getChat();
            if (chat.isGroup) {
                message.reply(`Nih ID Grupnya bos:\n*${chat.id._serialized}*`);
            } else {
                message.reply('Perintah ini cuma bisa dipakai di dalam grup bos.');
            }
        }

        // 3. Eksekusi Pilihan 2 (Cek Status)
        else if (text === '2') {
            message.reply('🤖 *Status:* NORMAL & AKTIF!\n🌐 *Server API:* Berjalan di Port 3000\n🚀 *Siap menerima iklan dari web!*');
        }

        // 4. Eksekusi Pilihan 3 (Tag All - Contoh fitur advance)
        else if (text === '3') {
            const chat = await message.getChat();
            if (chat.isGroup) {
                let textTag = "*Pengumuman dari Admin!*\n\n";
                let mentions = [];
                for (let participant of chat.participants) {
                    mentions.push(`${participant.id.user}@c.us`);
                    textTag += `@${participant.id.user} `;
                }
                await chat.sendMessage(textTag, { mentions });
            } else {
                message.reply('Cuma bisa tag all di grup bos.');
            }
        }
    } 
    // ==========================================
    // 🛑 ZONA MEMBER BIASA 🛑
    // ==========================================
    else {
        // Kalau member biasa iseng ngetik !menu atau angka 1,2,3
        if (text === '!menu' || text === '1' || text === '2' || text === '3') {
            message.reply('Maaf, kamu bukan Admin Smecone! 🛑 Jangan iseng ya!');
        }
    }
});

// 3. Membuat Endpoint API untuk dipanggil Laravel
app.post('/api/broadcast-iklan', async (req, res) => {
    const { groupId, pesan } = req.body;

    if (!groupId || !pesan) {
        return res.status(400).json({ success: false, message: 'Data groupId atau pesan kosong!' });
    }

    try {
        await client.sendMessage(groupId, pesan);
        console.log(`\n📢 Berhasil mengirim iklan ke grup: ${groupId}`);
        
        res.json({ success: true, message: 'Iklan berhasil terkirim ke WhatsApp!' });
    } catch (error) {
        console.error('\n❌ Gagal mengirim pesan:', error);
        res.status(500).json({ success: false, message: 'Gagal mengirim pesan via Bot' });
    }
});

// Menjalankan Bot WA
client.initialize();

// Menjalankan Server API di port 3000
const PORT = 3000;
app.listen(PORT, () => {
    console.log(`🚀 API Server Bot berjalan di http://localhost:${PORT}`);
});