const { Client, LocalAuth, MessageMedia } = require('whatsapp-web.js');
const qrcode = require('qrcode-terminal');
const express = require('express');
const cors = require('cors');
const readline = require('readline');
const sharp = require('sharp');
const fs = require('fs');
const path = require('path');

// Inisialisasi Express API
const app = express();
app.use(cors());
app.use(express.json());

let ADMIN_NUMBERS = [];
let tempScanList = []; 

// ==============================================================
// 🛠️ SYSTEM LOGGER (Mewarnai & Mencatat Waktu di Terminal)
// ==============================================================
function sysLog(type, message) {
    const now = new Date();
    const timeStr = now.toLocaleTimeString('id-ID', { hour12: false }); 
    const dateStr = now.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' }); 

    let color = '\x1b[0m'; // Reset
    let typeLabel = `[${type}]`;

    switch(type) {
        case 'SYSTEM': color = '\x1b[36m'; break;
        case 'API': color = '\x1b[35m'; break;    
        case 'CMD': color = '\x1b[32m'; break;    
        case 'CHAT': color = '\x1b[33m'; break;   
        case 'ERROR': color = '\x1b[31m'; break;  
        case 'WARN': color = '\x1b[33m'; break;   
        case 'INFO': color = '\x1b[34m'; break;   
    }

    console.log(`${color}[${dateStr} ${timeStr}] ${typeLabel.padEnd(8)} ${message}\x1b[0m`);
}

// ==============================================================
// 🎨 HELPER: Generate AUTO SCALED Text Sticker
// ==============================================================
async function generateDesignedSticker(text) {
    const WIDTH = 512;
    const HEIGHT = 512;
    const PADDING = 50;

    const escXml = (s) => s.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;').replace(/'/g, '&apos;');

    let rawLines = text.split('\n');
    let lines = [];

    // Jika teksnya pendek banget, kita paksa potong lebih awal biar fontnya bisa RAKSASA
    let maxChars = text.length <= 15 ? 7 : 12;

    for (let line of rawLines) {
        let words = line.split(' ');
        let currentLine = '';
        for (let word of words) {
            if ((currentLine + word).length > maxChars) {
                if (currentLine) lines.push(currentLine.trim());
                currentLine = word + ' ';
            } else {
                currentLine += word + ' ';
            }
        }
        if (currentLine) lines.push(currentLine.trim());
    }

    if (lines.length === 0) lines.push(text);

    // Cari baris dengan huruf terbanyak
    let maxLen = Math.max(...lines.map(l => l.length));
    if (maxLen === 0) maxLen = 1;

    // 🔥 LOGIKA AUTO SCALE (Dinamis mengisi kotak 512x512) 🔥
    const usableWidth = WIDTH - (PADDING * 2);
    const usableHeight = HEIGHT - (PADDING * 2);
    let fontSizeW = usableWidth / (maxLen * 0.55);
    let fontSizeH = usableHeight / (lines.length * 1.35);
    let fontSize = Math.floor(Math.min(fontSizeW, fontSizeH));

    // Batas maksimal dan minimal agar tidak terlalu over
    if (fontSize > 160) fontSize = 160; 
    if (fontSize < 35) fontSize = 35;

    const lineHeight = fontSize * 1.35;
    const totalHeight = lines.length * lineHeight;
    
    // Perhitungan Start Y agar rata tengah vertikal
    const startY = (HEIGHT - totalHeight) / 2 + (fontSize * 0.35);

    const tspans = lines.map((line, i) =>
        `<tspan x="${PADDING}" dy="${i === 0 ? 0 : lineHeight}">${escXml(line)}</tspan>`
    ).join('');

    const svgText = `
    <svg width="${WIDTH}" height="${HEIGHT}" xmlns="http://www.w3.org/2000/svg">
        <rect width="100%" height="100%" fill="#ffffff" rx="32" /> 
        <text x="${PADDING}" y="${startY}" font-family="'Helvetica Neue', Helvetica, Arial, sans-serif" font-size="${fontSize}" font-weight="300" fill="#1a1a1a" text-anchor="start" dominant-baseline="middle" letter-spacing="-0.5">
            ${tspans}
        </text>
    </svg>`;

    return await sharp(Buffer.from(svgText)).png().toBuffer();
}

// ==============================================================
// ⌨️ HELPER: Simulasi Mengetik Sebelum Kirim Pesan (Anti Ban)
// ==============================================================
async function sendWithTyping(client, chatId, content, options = {}) {
    try {
        const chat = await client.getChatById(chatId);
        await chat.sendStateTyping();

        // Delay dinamis berdasarkan panjang teks biar keliatan natural
        let delay;
        if (typeof content === 'string') {
            // Semakin panjang teks, semakin lama "ngetik"
            delay = Math.min(Math.max(content.length * 40, 1000), 4000);
        } else {
            // Media/Stiker: delay tetap
            delay = 1500;
        }

        // Tambah randomness biar ga kaku kayak robot
        delay += Math.floor(Math.random() * 1500);

        await new Promise(resolve => setTimeout(resolve, delay));
        await chat.clearState();
    } catch (err) {
        // Kalau gagal simulasi typing, lanjut aja kirim
        sysLog('WARN', `Typing simulation gagal: ${err.message}`);
    }

    return await client.sendMessage(chatId, content, options);
}

// ==============================================================
// 🔥 TAHAP 1: INPUT NOMOR ADMIN DARI TERMINAL
// ==============================================================
const rl = readline.createInterface({ input: process.stdin, output: process.stdout });

console.log('\n=========================================');
console.log('🚀 SETUP BOT SMECONE HUB (SERVER LOG MODE) 🚀');
console.log('=========================================');

rl.question('👉 Masukkan Nomor WA Admin (Bisa lebih dari 1, pisahkan koma): ', (answer) => {
    let rawNumbers = answer.split(',');
    rawNumbers.forEach(num => {
        let adminNum = num.replace(/[^0-9]/g, ''); 
        if (adminNum) { 
            if (adminNum.startsWith('0')) adminNum = '62' + adminNum.slice(1);
            ADMIN_NUMBERS.push(`${adminNum}@c.us`);
        }
    });

    sysLog('SYSTEM', `Total ${ADMIN_NUMBERS.length} Nomor Admin didaftarkan.`);
    sysLog('SYSTEM', 'Memulai engine WhatsApp... Harap tunggu.');
    
    rl.close();
    startBot(); 
});

// ==============================================================
// 🔥 TAHAP 2: FUNGSI UTAMA BOT WHATSAPP
// ==============================================================
function startBot() {
    const client = new Client({
        authStrategy: new LocalAuth(), 
        puppeteer: {
            headless: true,
            args: [
                '--no-sandbox', 
                '--disable-setuid-sandbox',
                '--disable-dev-shm-usage', 
                '--disable-accelerated-2d-canvas', 
                '--disable-gpu',
                '--no-first-run',
                '--no-zygote'
            ]
        }
    });

    client.on('qr', (qr) => {
        console.log('\n=========================================');
        console.log('📱 SCAN QR CODE INI PAKAI WHATSAPP KAMU 📱');
        console.log('=========================================\n');
        qrcode.generate(qr, { small: true });
        sysLog('SYSTEM', 'QR Code generated, menunggu scan...');
    });

    client.on('auth_failure', (msg) => {
        sysLog('ERROR', `Autentikasi GAGAL: ${msg}. Hapus folder .wwebjs_auth!`);
    });

    client.on('ready', async () => {
        sysLog('SYSTEM', 'Bot Smecone Hub ONLINE & TERKONEKSI!');

        const myBotNumber = client.info.wid._serialized;
        if (!ADMIN_NUMBERS.includes(myBotNumber)) {
            ADMIN_NUMBERS.push(myBotNumber);
            sysLog('SYSTEM', `Nomor Bot (+${myBotNumber.replace('@c.us','')}) diangkat jadi Admin Utama.`);
        }
    });

    client.on('message_create', async message => {
        if (!message.body || message.body.trim() === '') return;

        // 🔥 LOGIKA TARGET CHAT MUTLAK 🔥
        // Mendeteksi secara akurat di mana pesan itu diketik (Grup atau Private Chat)
        let targetChatId;
        if (message.from.endsWith('@g.us')) {
            targetChatId = message.from; // Orang lain ngetik di grup
        } else if (message.to.endsWith('@g.us')) {
            targetChatId = message.to;   // Kamu (bot itu sendiri) ngetik di grup
        } else {
            targetChatId = message.fromMe ? message.to : message.from; // Pesan Pribadi (PC)
        }

        const myBotNumber = client.info.wid._serialized;
        let senderNumber = message.fromMe ? myBotNumber : (message.author || message.from); 
        senderNumber = senderNumber.replace(/:\d+@/, '@'); 
        
        const text = message.body.toLowerCase().trim();
        const isAdmin = ADMIN_NUMBERS.includes(senderNumber);
        const isGroup = targetChatId.endsWith('@g.us');
        
        let senderLabel = senderNumber.split('@')[0];
        let chatLabel = isGroup ? `[GRUP] ${senderLabel}` : `[PRIBADI] ${senderLabel}`;

        const silentCommands = ['!scan', '!listgrup'];
        const isAddCommand = text.startsWith('!add');
        const isSendCommand = text.startsWith('!send');
        const isTextSticker = text.startsWith('!text ');
        
        const isCommand = text.startsWith('!') || ['1','2','3','4','menu'].includes(text);

        // 📝 LOGGING SEMUA INTERAKSI
        if (isCommand) {
            if (!silentCommands.includes(text) && !isAddCommand && !isSendCommand) {
                sysLog('CMD', `${chatLabel} -> Perintah: ${text}`);
            }
        } else {
            let shortText = message.body.replace(/\n/g, ' '); 
            if (shortText.length > 50) shortText = shortText.substring(0, 50) + '...'; 
            sysLog('CHAT', `${chatLabel} -> Teks: ${shortText}`);
        }

        if (!isCommand) return; 

        // ==============================================================
        // 🕵️‍♂️ PERINTAH SUPER ADMIN (SENYAP)
        // ==============================================================
        if (text === '!scan' && isAdmin) {
            sysLog('CMD', `${senderLabel} menjalankan !scan (Senyap)`);
            if (isGroup) {
                const chat = await message.getChat();
                tempScanList = chat.participants.map(p => p.id._serialized.replace(/:\d+@/, '@'));

                console.log('\n=========================================');
                console.log(`🕵️‍♂️ [SILENT SCAN] - Grup: ${chat.name}`);
                console.log(`Total Anggota: ${tempScanList.length} orang\n`);
                tempScanList.forEach((num, index) => {
                    const status = ADMIN_NUMBERS.includes(num) ? '(Sudah Admin)' : '';
                    console.log(`[${index + 1}] +${num.replace('@c.us', '')} ${status}`);
                });
                console.log('=========================================\n');
            }
            return; 
        }

        if (isAddCommand && isAdmin) {
            const parts = text.split(' ');
            if (parts.length > 1) {
                const index = parseInt(parts[1]) - 1; 
                if (!isNaN(index) && tempScanList[index]) {
                    const targetNum = tempScanList[index];
                    if (!ADMIN_NUMBERS.includes(targetNum)) {
                        ADMIN_NUMBERS.push(targetNum);
                        sysLog('SYSTEM', `Nomor +${targetNum.replace('@c.us', '')} ditambahkan jadi Admin!`);
                    }
                }
            }
            return; 
        }

        if (text === '!listgrup' && isAdmin) {
            sysLog('CMD', `${senderLabel} meminta list grup.`);
            const chats = await client.getChats();
            const groups = chats.filter(c => c.isGroup);

            console.log('\n=========================================');
            console.log(`🏢 [DAFTAR GRUP BOT] - Total: ${groups.length} Grup`);
            groups.forEach((g, i) => {
                console.log(`[${i + 1}] Nama : ${g.name} | ID: ${g.id._serialized}`);
            });
            console.log('=========================================\n');
            await sendWithTyping(client, targetChatId, `✅ Cek Terminal laptopmu bos!`);
            return; 
        }

        if (isSendCommand && isAdmin) {
            const parts = message.body.split(' ');
            if (parts.length >= 3) {
                const targetId = parts[1]; 
                const msgToSend = parts.slice(2).join(' '); 
                try {
                    await sendWithTyping(client, targetId, msgToSend);
                    sysLog('SYSTEM', `Pesan ditembak ke ${targetId}`);
                    await sendWithTyping(client, targetChatId, `✅ *Sukses!* Pesan berhasil ditembakkan.`);
                } catch (error) {
                    sysLog('ERROR', `Gagal nembak ke ${targetId}.`);
                    await sendWithTyping(client, targetChatId, `❌ *Gagal!* Pastikan ID Grup valid.`);
                }
            }
            return;
        }

        // ==============================================================
        // 🌟 FITUR PUBLIK & STIKER
        // ==============================================================
        if (text === '!stiker' || text === '!sticker') {
            if (message.hasMedia) {
                try {
                    const media = await message.downloadMedia();
                    if (media && (media.mimetype.includes('image') || media.mimetype.includes('video'))) {
                        sysLog('INFO', `Memproses stiker media untuk ${senderLabel}`);
                        // Paksa kirim ke targetChatId!
                        await sendWithTyping(client, targetChatId, media, { sendMediaAsSticker: true, stickerName: 'Smecone Hub', stickerAuthor: 'Bot Smecone' });
                    }
                } catch (err) {
                    sysLog('ERROR', `Gagal bikin stiker media: ${err.message}`);
                }
            } else {
                await sendWithTyping(client, targetChatId, '💡 *Cara bikin stiker:*\nKirim foto/video, kasih caption `!stiker`.\nAtau ketik `!text Isinya apa` buat stiker teks.');
            }
            return;
        }

        // 🟢 STIKER TEKS TIPOGRAFI SIMPLE AUTO SCALED 🟢
        if (isTextSticker) {
            const isiStiker = message.body.slice(6).trim(); 
            
            if (!isiStiker) {
                await sendWithTyping(client, targetChatId, '⚠️ Teksnya kosong bos!\nCara pakai:\n`!text suka suka aku lah`');
                return;
            }

            try {
                sysLog('INFO', `Melukis stiker teks: "${isiStiker}"`);
                
                // Paksa kirim notif ke targetChatId
                await sendWithTyping(client, targetChatId, '⏳ Pesan stiker sedang dibuat...');

                // Buat gambar teks
                const pngBuffer = await generateDesignedSticker(isiStiker);
                
                // Ubah jadi stiker
                const base64 = pngBuffer.toString('base64');
                const media = new MessageMedia('image/png', base64, 'stiker.png');
                
                // Paksa kirim stiker ke targetChatId!
                await sendWithTyping(client, targetChatId, media, { 
                    sendMediaAsSticker: true, 
                    stickerName: 'Smecone Teks', 
                    stickerAuthor: 'Bot Smecone Hub' 
                });
                
                sysLog('SYSTEM', `✅ Stiker teks berhasil dikirim ke ${senderLabel}`);
            } catch (err) {
                sysLog('ERROR', `Gagal bikin stiker teks: ${err.message}`);
                await sendWithTyping(client, targetChatId, '❌ Gagal membuat stiker teks. Pastikan teks tidak terlalu panjang.');
            }
            return;
        }

        if (text === '!ping') return sendWithTyping(client, targetChatId, '🏓 Pong! Bot Smecone nyala.');
        if (text === '!info') return sendWithTyping(client, targetChatId, '🛒 *SMECONE HUB*\nPlatform warga Smecone.\n• `!stiker`\n• `!text <isi>`');

        // ==============================================================
        // 👑 MENU ADMIN
        // ==============================================================
        if (isAdmin) {
            if (text === '!menu' || text === 'menu') {
                const menuText = `*👑 MENU ADMIN 👑*\n\n*1️⃣* Cek ID Grup Sini\n*2️⃣* Cek Status Server\n*3️⃣* Hidetag Member\n*4️⃣* Clear Chat\n\n*(KHUSUS)*\n• \`!listgrup\`\n• \`!send <ID> <Pesan>\`\n• \`!scan\` & \`!add <angka>\`\n`;
                await sendWithTyping(client, targetChatId, menuText);
            }
            else if (text === '1') {
                if (isGroup) {
                    await sendWithTyping(client, targetChatId, `📝 *ID Grup Target:*\n*${targetChatId}*`);
                } else {
                    await sendWithTyping(client, targetChatId, '❌ Cuma bisa di dalam Grup ya bos.');
                }
            }
            else if (text === '2') {
                const memMB = (process.memoryUsage().heapUsed / 1024 / 1024).toFixed(1);
                await sendWithTyping(client, targetChatId, `🟢 *STATUS: TURBO*\nPort: 3000\nRAM: ${memMB} MB`);
            }
            else if (text === '3') {
                if (isGroup) {
                    const chat = await message.getChat();
                    let textTag = "📢 *PENGUMUMAN* 📢\n\n";
                    let mentions = chat.participants.map(p => `${p.id.user}@c.us`);
                    mentions.forEach(m => textTag += `@${m.split('@')[0]} `);
                    await sendWithTyping(client, targetChatId, textTag, { mentions });
                }
            }
            else if (text === '4') {
                await (await message.getChat()).clearMessages();
                await sendWithTyping(client, targetChatId, '🧹 Bersih bos!');
            }
        } else {
            const adminCommands = ['1', '2', '3', '4', '!menu'];
            if (adminCommands.includes(text)) await sendWithTyping(client, targetChatId, '🛑 Eits, kamu bukan Admin!');
        }
    });

    // ==============================================================
    // 🔥 ENDPOINT API LARAVEL
    // ==============================================================
    app.post('/api/broadcast-iklan', (req, res) => { 
        const { groupId, pesan, imageUrl } = req.body;
        if (!groupId || !pesan) {
            sysLog('ERROR', 'API Broadcast gagal: Data kosong');
            return res.status(400).json({ success: false });
        }

        sysLog('API', `Menerima request Broadcast Iklan ke: ${groupId}`);
        res.json({ success: true, message: 'Iklan diproses' });

        (async () => {
            try {
                await new Promise(resolve => setTimeout(resolve, 1000));
                if (imageUrl) {
                    const media = await MessageMedia.fromUrl(imageUrl, { unsafeMime: true });
                    await sendWithTyping(client, groupId, media, { caption: pesan });
                } else {
                    await sendWithTyping(client, groupId, pesan);
                }
                sysLog('SYSTEM', `✅ Iklan sukses dikirim ke grup ${groupId}`);
            } catch (error) {
                sysLog('ERROR', `Gagal broadcast ke ${groupId}: ${error.message}`);
            }
        })();
    });

    app.post('/send-message', async (req, res) => {
        const { number, message } = req.body;
        if (!number || !message) {
            sysLog('ERROR', 'API Notifikasi gagal: Nomor/Pesan kosong');
            return res.status(400).json({ success: false });
        }

        let formattedNumber = number.includes('@c.us') ? number : `${number}@c.us`;
        sysLog('API', `Kirim notifikasi personal ke: ${formattedNumber}`);
        
        try {
            await sendWithTyping(client, formattedNumber, message);
            sysLog('SYSTEM', `✅ Notifikasi sukses ke ${formattedNumber}`);
            res.json({ success: true });
        } catch (error) {
            sysLog('ERROR', `Gagal kirim notif ke ${formattedNumber}: ${error.message}`);
            res.status(500).json({ success: false });
        }
    });

    client.initialize().catch(err => sysLog('ERROR', `Gagal init WA: ${err.message}`));
    
    const PORT = 3000;
    app.listen(PORT, () => {
        sysLog('SYSTEM', `🌐 Web Server & API Bridge menyala di Port ${PORT}`);
    });
}