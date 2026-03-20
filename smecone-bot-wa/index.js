const { Client, LocalAuth, MessageMedia } = require('whatsapp-web.js');
const qrcode = require('qrcode-terminal');
const express = require('express');
const cors = require('cors');

// Inisialisasi Express API
const app = express();
app.use(cors());
app.use(express.json()); // Agar bisa membaca data JSON dari Laravel

// Inisialisasi Bot WA
const client = new Client({
    authStrategy: new LocalAuth(), // Menyimpan sesi login
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
    '6285728150223@c.us', // Ganti dengan nomor WA kamu!
];

client.on('message', async message => {
    const senderNumber = message.author || message.from; 
    const text = message.body.toLowerCase();
    const isAdmin = ADMIN_NUMBERS.includes(senderNumber);

    if (isAdmin) {
        if (text === '!menu') {
            const menuText = `*🛠️ MENU ADMIN SMECONE 🛠️*\n\n` +
                             `Halo Admin! Silakan balas dengan angka:\n\n` +
                             `*1️⃣* Cek ID Grup Sini\n` +
                             `*2️⃣* Cek Status Bot\n` +
                             `*3️⃣* Test Tag Semua Member\n\n` +
                             `_(Pesan ini rahasia, hanya Admin yang bisa pakai)_`;
            message.reply(menuText);
        }
        else if (text === '1') {
            const chat = await message.getChat();
            if (chat.isGroup) {
                message.reply(`Nih ID Grupnya bos:\n*${chat.id._serialized}*`);
            } else {
                message.reply('Perintah ini cuma bisa dipakai di dalam grup bos.');
            }
        }
        else if (text === '2') {
            message.reply('🤖 *Status:* NORMAL & AKTIF!\n🌐 *Server API:* Berjalan di Port 3000\n🚀 *Siap menerima iklan dari web!*');
        }
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
    } else {
        if (text === '!menu' || text === '1' || text === '2' || text === '3') {
            message.reply('Maaf, kamu bukan Admin Smecone! 🛑 Jangan iseng ya!');
        }
    }
});

// 3. Endpoint API untuk Broadcast dari Laravel
app.post('/api/broadcast-iklan', (req, res) => { 
    const { groupId, pesan, imageUrl } = req.body;

    if (!groupId || !pesan) {
        return res.status(400).json({ success: false, message: 'Data groupId atau pesan kosong!' });
    }

    // 1. LANGSUNG balas ke Laravel agar web tidak loading lama / terkena Timeout
    res.json({ success: true, message: 'Iklan sedang diproses dan dikirim oleh Bot!' });

    // 2. Jalankan proses pengiriman WhatsApp di latar belakang (Background Process)
    (async () => {
        try {
            if (imageUrl) {
                // Download gambar dan kirim
                const media = await MessageMedia.fromUrl(imageUrl);
                await client.sendMessage(groupId, media, { caption: pesan });
                console.log(`\n📢 Berhasil mengirim IKLAN + GAMBAR ke grup: ${groupId}`);
            } else {
                // Kirim teks saja
                await client.sendMessage(groupId, pesan);
                console.log(`\n📢 Berhasil mengirim IKLAN TEKS ke grup: ${groupId}`);
            }
        } catch (error) {
            console.error('\n❌ Gagal mengirim pesan di background:', error);
        }
    })();
});

// ==============================================================
// 4. Endpoint API untuk Kirim Notif Personal (Ke Pembeli / Penjual)
// ==============================================================
app.post('/send-message', async (req, res) => {
    console.log('\n📥 [INCOMING WEBHOOK] Menerima request di /send-message');
    console.log('📦 Data Payload dari Laravel:', req.body);

    const { number, message } = req.body;

    if (!number || !message) {
        console.log('⚠️ Peringatan: Data nomor atau pesan kosong!');
        return res.status(400).json({ success: false, message: 'Data nomor atau pesan kosong!' });
    }

    // Nomor dari Laravel udah diformat "62...", tinggal tambahin "@c.us" khas WA
    let formattedNumber = number;
    if (!formattedNumber.includes('@c.us')) {
        formattedNumber = `${formattedNumber}@c.us`;
    }

    try {
        await client.sendMessage(formattedNumber, message);
        console.log(`\n✅ [NOTIFIKASI] Berhasil mengirim pesan otomatis ke WA: ${formattedNumber}`);
        res.json({ success: true, message: 'Pesan berhasil dikirim!' });
    } catch (error) {
        console.error(`\n❌ [NOTIFIKASI GAGAL] Tidak bisa mengirim ke ${formattedNumber}:`, error.message);
        res.status(500).json({ success: false, message: 'Gagal mengirim pesan' });
    }
});

client.initialize();

const PORT = 3000;
app.listen(PORT, () => {
    console.log(`🚀 API Server Bot berjalan di http://localhost:${PORT}`);
});