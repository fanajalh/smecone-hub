<!DOCTYPE html>
<html lang="id" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Pesanan Digital Berhasil - SMEconE Hub</title>
    <!--[if mso]>
    <noscript>
        <xml>
            <o:OfficeDocumentSettings>
                <o:PixelsPerInch>96</o:PixelsPerInch>
            </o:OfficeDocumentSettings>
        </xml>
    </noscript>
    <![endif]-->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap');
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f0f2f5; font-family: 'Plus Jakarta Sans', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale;">

    {{-- Preheader Text --}}
    <div style="display: none; max-height: 0px; overflow: hidden; mso-hide: all;">
        Pembayaran berhasil! Produk digital "{{ $transaction->marketplaceItem->item_name ?? 'Produk' }}" sudah siap diakses.
    </div>

    {{-- Outer Wrapper --}}
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f0f2f5; padding: 32px 16px;">
        <tr>
            <td align="center">

                {{-- Email Container --}}
                <table role="presentation" width="560" cellspacing="0" cellpadding="0" border="0" style="max-width: 560px; width: 100%; background-color: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 10px 30px -5px rgba(0,0,0,0.08);">

                    {{-- ============ HEADER ============ --}}
                    <tr>
                        <td style="background: linear-gradient(145deg, #064e3b 0%, #065f46 50%, #047857 100%); padding: 48px 40px 40px; text-align: center;">

                            {{-- Brand Logo --}}
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td align="center" style="padding-bottom: 20px;">
                                        <div style="display: inline-block; width: 56px; height: 56px; background: linear-gradient(135deg, #E21F26 0%, #B9151D 100%); border-radius: 16px; line-height: 56px; text-align: center; font-size: 26px; font-weight: 900; color: #ffffff; letter-spacing: -1px; box-shadow: 0 8px 24px rgba(226,31,38,0.35);">
                                            S
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="padding-bottom: 16px;">
                                        <span style="color: #ffffff; font-size: 22px; font-weight: 800; letter-spacing: -0.5px;">Smecone<span style="color: #34d399;">Hub</span></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="padding-bottom: 8px;">
                                        <span style="color: #ffffff; font-size: 28px; font-weight: 900; letter-spacing: -0.5px;">Yeay, Berhasil! 🎉</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <div style="display: inline-block; background-color: rgba(52,211,153,0.15); border: 1px solid rgba(52,211,153,0.3); border-radius: 99px; padding: 6px 16px;">
                                            <span style="color: #6ee7b7; font-size: 11px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase;">✅ Pembayaran Dikonfirmasi</span>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    {{-- ============ BODY ============ --}}
                    <tr>
                        <td style="padding: 40px 36px 16px;">

                            {{-- Greeting --}}
                            <p style="color: #111827; font-size: 18px; font-weight: 800; margin: 0 0 6px; letter-spacing: -0.3px;">
                                Selamat Datang, {{ $transaction->user->name ?? 'Pengguna' }}! 👋
                            </p>
                            <p style="color: #6b7280; font-size: 14px; font-weight: 500; margin: 0 0 28px; line-height: 1.7;">
                                Terima kasih telah berbelanja di <strong style="color: #374151;">SMEconE Hub</strong>. Produk digital Anda kini aktif dan siap diakses. Berikut rincian transaksinya:
                            </p>

                        </td>
                    </tr>

                    {{-- ============ DETAIL CARD ============ --}}
                    <tr>
                        <td style="padding: 0 36px 32px;">

                            {{-- Transaction Card --}}
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 16px; overflow: hidden;">

                                {{-- Product Name --}}
                                <tr>
                                    <td style="padding: 24px 24px 20px;">
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                                <td width="40" valign="top" style="padding-right: 14px;">
                                                    <div style="width: 40px; height: 40px; background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); border-radius: 12px; line-height: 40px; text-align: center; font-size: 18px;">📦</div>
                                                </td>
                                                <td valign="middle">
                                                    <p style="margin: 0; color: #9ca3af; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Produk</p>
                                                    <p style="margin: 4px 0 0; color: #111827; font-size: 15px; font-weight: 800; letter-spacing: -0.2px;">{{ $transaction->marketplaceItem->item_name ?? 'Produk Digital' }}</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                {{-- Divider --}}
                                <tr>
                                    <td style="padding: 0 24px;">
                                        <div style="border-top: 1px dashed #e5e7eb;"></div>
                                    </td>
                                </tr>

                                {{-- Detail Rows --}}
                                <tr>
                                    <td style="padding: 20px 24px;">
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                            {{-- Tanggal --}}
                                            <tr>
                                                <td style="padding-bottom: 14px;">
                                                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                                        <tr>
                                                            <td style="color: #9ca3af; font-size: 12px; font-weight: 600;">📅 Tanggal</td>
                                                            <td align="right" style="color: #374151; font-size: 13px; font-weight: 700;">{{ $transaction->created_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            {{-- Qty --}}
                                            <tr>
                                                <td style="padding-bottom: 14px;">
                                                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                                        <tr>
                                                            <td style="color: #9ca3af; font-size: 12px; font-weight: 600;">🔢 Jumlah</td>
                                                            <td align="right" style="color: #374151; font-size: 13px; font-weight: 700;">{{ $transaction->qty }}x</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            @if($transaction->variant_selected)
                                            {{-- Varian --}}
                                            <tr>
                                                <td style="padding-bottom: 14px;">
                                                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                                        <tr>
                                                            <td style="color: #9ca3af; font-size: 12px; font-weight: 600;">🏷️ Varian</td>
                                                            <td align="right" style="color: #374151; font-size: 13px; font-weight: 700;">{{ $transaction->variant_selected }}</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            @endif
                                            {{-- Metode --}}
                                            <tr>
                                                <td style="padding-bottom: 14px;">
                                                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                                        <tr>
                                                            <td style="color: #9ca3af; font-size: 12px; font-weight: 600;">💳 Metode</td>
                                                            <td align="right" style="color: #374151; font-size: 13px; font-weight: 700;">{{ $transaction->payment_method ?? 'Online' }}</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            {{-- Referensi --}}
                                            <tr>
                                                <td>
                                                    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                                        <tr>
                                                            <td style="color: #9ca3af; font-size: 12px; font-weight: 600;">📑 No. Referensi</td>
                                                            <td align="right" style="color: #9ca3af; font-size: 12px; font-weight: 600; font-family: 'Courier New', monospace;">#SMH-TRX-{{ str_pad($transaction->id, 5, '0', STR_PAD_LEFT) }}</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                                {{-- Divider --}}
                                <tr>
                                    <td style="padding: 0 24px;">
                                        <div style="border-top: 2px solid #e5e7eb;"></div>
                                    </td>
                                </tr>

                                {{-- Total --}}
                                <tr>
                                    <td style="padding: 20px 24px 24px;">
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                                <td style="color: #9ca3af; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;" valign="middle">Total Bayar</td>
                                                <td align="right" valign="middle">
                                                    <span style="color: #059669; font-size: 28px; font-weight: 900; letter-spacing: -1px;">Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>

                            </table>

                        </td>
                    </tr>

                    {{-- ============ CTA BUTTON ============ --}}
                    <tr>
                        <td style="padding: 0 36px 24px;">
                            @if($transaction->marketplaceItem && $transaction->marketplaceItem->digital_link)
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $transaction->marketplaceItem->digital_link }}" style="display: block; width: 100%; text-align: center; background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); color: #ffffff; text-decoration: none; font-size: 14px; font-weight: 800; padding: 18px 32px; border-radius: 14px; box-shadow: 0 8px 24px rgba(0,0,0,0.15); letter-spacing: 0.5px; text-transform: uppercase; box-sizing: border-box;">
                                            🚀&nbsp;&nbsp;Akses Produk Sekarang
                                        </a>
                                    </td>
                                </tr>
                            </table>
                            @endif
                        </td>
                    </tr>

                    {{-- ============ ALTERNATE LINK ============ --}}
                    @if($transaction->marketplaceItem && $transaction->marketplaceItem->digital_link)
                    <tr>
                        <td style="padding: 0 36px 32px;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f0f4ff; border: 1px solid #dbeafe; border-radius: 12px;">
                                <tr>
                                    <td style="padding: 16px 20px; text-align: center;">
                                        <p style="color: #6b7280; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; margin: 0 0 6px;">Tautan Alternatif</p>
                                        <a href="{{ $transaction->marketplaceItem->digital_link }}" style="color: #2563eb; font-size: 13px; font-weight: 600; text-decoration: none; word-break: break-all;">{{ $transaction->marketplaceItem->digital_link }}</a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    @endif

                    {{-- ============ SELLER INFO ============ --}}
                    @if($transaction->marketplaceItem && $transaction->marketplaceItem->user)
                    <tr>
                        <td style="padding: 0 36px 28px;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #fafafa; border: 1px solid #f3f4f6; border-radius: 12px;">
                                <tr>
                                    <td style="padding: 16px 20px;">
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                                <td width="36" valign="middle" style="padding-right: 12px;">
                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($transaction->marketplaceItem->user->name) }}&background=E21F26&color=fff&bold=true&size=36" width="36" height="36" style="border-radius: 10px; display: block;" alt="Penjual">
                                                </td>
                                                <td valign="middle">
                                                    <p style="margin: 0; color: #9ca3af; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Dijual oleh</p>
                                                    <p style="margin: 2px 0 0; color: #374151; font-size: 13px; font-weight: 800;">{{ $transaction->marketplaceItem->user->store_name ?? $transaction->marketplaceItem->user->name }}</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    @endif

                    {{-- ============ FOOTER ============ --}}
                    <tr>
                        <td style="background-color: #fafafa; border-top: 1px solid #f3f4f6; padding: 24px 36px;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td align="center" style="padding-bottom: 8px;">
                                        <span style="color: #9ca3af; font-size: 12px; font-weight: 500; line-height: 1.5;">
                                            Kendala aktivasi? Hubungi penjual atau tim <strong style="color: #6b7280;">SMEconE Hub</strong>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="padding-bottom: 4px;">
                                        <span style="color: #d1d5db; font-size: 11px; font-weight: 500;">
                                            Mohon jangan membalas email ini.
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <span style="color: #e5e7eb; font-size: 10px; font-weight: 500;">
                                            &copy; {{ date('Y') }} SMEconE Hub. All rights reserved.
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>