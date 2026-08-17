# Smecone Hub

Smecone Hub adalah platform Student Portal terintegrasi yang menyediakan berbagai fitur unggulan untuk ekosistem sekolah, termasuk Marketplace (Jajan), Forum (Diskusi & Channel), dan Repository (Bahan Ajar & Tugas).

## 🔐 Akun Default (Testing)

Untuk keperluan testing, Anda dapat login menggunakan kredensial berikut:

### 👑 Akun Admin
- **Email:** `admin@smecone.id`
- **Kata Sandi:** `password` (atau `12345678`)
- **Fungsi:** Memiliki akses penuh, termasuk mengelola channel, menghapus data, dan menyetujui request.

### 👨‍🏫 Akun Guru
- **Email:** `budi.guru@smecone.id`
- **Kata Sandi:** `password` (atau `12345678`)
- **Fungsi:** Memiliki akses ekstra (contoh: melihat repository private murid jika disubmit untuk tugas).

---

## 💳 Testing Sistem Pembayaran (Xendit Webhook)

Jika Anda ingin melakukan testing pembayaran (E-Wallet/QRIS) secara lokal (di web) tanpa perlu menunggu respon asli dari Payment Gateway atau melakukan pembayaran sungguhan, Anda bisa langsung memanggil *route* berikut:

**URL Simulasi Pembayaran:**  
👉 `http://smecone-hub.test/tes-bayar/{id_transaksi}`

**Langkah-langkah:**
1. Lakukan pemesanan barang seperti biasa hingga masuk ke halaman "Menunggu Pembayaran".
2. Catat ID Transaksi Anda (bisa dilihat di URL halaman pembayaran).
3. Buka tab baru di browser Anda dan kunjungi `/tes-bayar/{id}` (Ganti `{id}` dengan ID transaksi Anda).
4. Transaksi akan langsung otomatis dianggap LUNAS (PAID) dan notifikasi akan terpicu!

---

*Dokumentasi ini di-generate secara otomatis untuk mempermudah pengembangan (Development).*
