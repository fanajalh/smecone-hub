<!DOCTYPE html>
<html lang="id" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Reset Password - SMEconE Hub</title>
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

    {{-- Preheader Text (disembunyikan, tapi muncul di preview inbox) --}}
    <div style="display: none; max-height: 0px; overflow: hidden; mso-hide: all;">
        Halo {{ $userName }}, kami menerima permintaan reset password untuk akun Anda di SMEconE Hub.
    </div>

    {{-- Outer Wrapper --}}
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f0f2f5; padding: 32px 16px;">
        <tr>
            <td align="center">

                {{-- Email Container --}}
                <table role="presentation" width="560" cellspacing="0" cellpadding="0" border="0" style="max-width: 560px; width: 100%; background-color: #ffffff; border-radius: 20px; overflow: hidden; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 10px 30px -5px rgba(0,0,0,0.08);">

                    {{-- ============ HEADER ============ --}}
                    <tr>
                        <td style="background: linear-gradient(145deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%); padding: 48px 40px 40px; text-align: center; position: relative;">

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
                                    <td align="center" style="padding-bottom: 6px;">
                                        <span style="color: #ffffff; font-size: 22px; font-weight: 800; letter-spacing: -0.5px;">Smecone<span style="color: #E21F26;">Hub</span></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center">
                                        <div style="display: inline-block; background-color: rgba(226,31,38,0.15); border: 1px solid rgba(226,31,38,0.25); border-radius: 99px; padding: 6px 16px;">
                                            <span style="color: #ff6b6b; font-size: 11px; font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase;">🔐 Reset Password</span>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    {{-- ============ BODY ============ --}}
                    <tr>
                        <td style="padding: 40px 36px 32px;">

                            {{-- Greeting --}}
                            <p style="color: #111827; font-size: 20px; font-weight: 800; margin: 0 0 6px; letter-spacing: -0.3px;">
                                Halo, {{ $userName }}! 👋
                            </p>
                            <p style="color: #6b7280; font-size: 14px; font-weight: 500; margin: 0 0 28px; line-height: 1.7;">
                                Kami menerima permintaan untuk mereset password akun Anda di <strong style="color: #374151;">SMEconE Hub</strong>. Klik tombol di bawah ini untuk membuat password baru.
                            </p>

                            {{-- CTA Button --}}
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-bottom: 28px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $resetLink }}" style="display: inline-block; background: linear-gradient(135deg, #E21F26 0%, #B9151D 100%); color: #ffffff; text-decoration: none; font-size: 14px; font-weight: 800; padding: 16px 40px; border-radius: 14px; box-shadow: 0 8px 24px rgba(226,31,38,0.3); letter-spacing: 0.3px; text-transform: uppercase;">
                                            🔑&nbsp;&nbsp;Reset Password Sekarang
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            {{-- Security Info Card --}}
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-bottom: 24px;">
                                <tr>
                                    <td style="background-color: #fef9f0; border: 1px solid #fde9c8; border-radius: 14px; padding: 20px 24px;">
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                                <td style="padding-bottom: 10px;">
                                                    <span style="color: #92400e; font-size: 13px; font-weight: 700;">⏰ Link berlaku selama <strong>60 menit</strong></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <span style="color: #a16207; font-size: 12px; font-weight: 500; line-height: 1.6;">Jika Anda tidak merasa meminta reset password, abaikan email ini. Password Anda tidak akan berubah.</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            {{-- Divider --}}
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="margin-bottom: 20px;">
                                <tr>
                                    <td style="border-top: 1px solid #f3f4f6;"></td>
                                </tr>
                            </table>

                            {{-- Fallback Link --}}
                            <p style="color: #9ca3af; font-size: 11px; font-weight: 600; margin: 0 0 8px; text-align: center;">
                                Tombol tidak berfungsi? Copy link berikut:
                            </p>
                            <div style="background-color: #f9fafb; border: 1px solid #e5e7eb; border-radius: 10px; padding: 12px 16px; text-align: center;">
                                <a href="{{ $resetLink }}" style="color: #6b7280; font-size: 11px; text-decoration: none; word-break: break-all; font-weight: 500; font-family: 'Courier New', monospace;">{{ $resetLink }}</a>
                            </div>

                        </td>
                    </tr>

                    {{-- ============ FOOTER ============ --}}
                    <tr>
                        <td style="background-color: #fafafa; border-top: 1px solid #f3f4f6; padding: 24px 36px;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td align="center" style="padding-bottom: 8px;">
                                        <span style="color: #9ca3af; font-size: 12px; font-weight: 600;">
                                            Email ini dikirim otomatis oleh <strong style="color: #6b7280;">SMEconE Hub</strong>
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
