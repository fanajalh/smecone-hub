<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - SMEconE Hub</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f3f4f6; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f3f4f6; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="background-color: #ffffff; border-radius: 24px; overflow: hidden; box-shadow: 0 4px 24px rgba(0,0,0,0.06);">
                    
                    {{-- HEADER --}}
                    <tr>
                        <td style="background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%); padding: 40px 40px 30px; text-align: center;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0">
                                <tr>
                                    <td align="center">
                                        <div style="width: 56px; height: 56px; background-color: rgba(255,255,255,0.2); border-radius: 16px; display: inline-block; line-height: 56px; font-size: 28px; font-weight: 900; color: #ffffff; margin-bottom: 16px;">
                                            S
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="color: #ffffff; font-size: 22px; font-weight: 800; letter-spacing: -0.5px; padding-bottom: 8px;">
                                        SMEconE Hub
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" style="color: rgba(255,255,255,0.8); font-size: 13px; font-weight: 600; letter-spacing: 2px; text-transform: uppercase;">
                                        Reset Password
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- BODY --}}
                    <tr>
                        <td style="padding: 40px;">
                            {{-- Lock Icon --}}
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 24px;">
                                <tr>
                                    <td align="center">
                                        <div style="width: 72px; height: 72px; background-color: #fef2f2; border-radius: 20px; display: inline-block; line-height: 72px; text-align: center;">
                                            <span style="font-size: 36px;">🔐</span>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            {{-- Greeting --}}
                            <p style="color: #111827; font-size: 20px; font-weight: 800; margin: 0 0 8px; text-align: center;">
                                Halo, {{ $userName }}!
                            </p>
                            <p style="color: #6b7280; font-size: 14px; font-weight: 500; margin: 0 0 32px; text-align: center; line-height: 1.6;">
                                Kami menerima permintaan untuk mereset password akun Anda di SMEconE Hub. Klik tombol di bawah ini untuk membuat password baru.
                            </p>

                            {{-- CTA Button --}}
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 32px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $resetLink }}" style="display: inline-block; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: #ffffff; text-decoration: none; font-size: 15px; font-weight: 700; padding: 16px 48px; border-radius: 16px; box-shadow: 0 8px 20px rgba(239,68,68,0.3); letter-spacing: 0.5px;">
                                            🔑 RESET PASSWORD SEKARANG
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            {{-- Divider --}}
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="margin-bottom: 24px;">
                                <tr>
                                    <td style="border-top: 1px solid #e5e7eb;"></td>
                                </tr>
                            </table>

                            {{-- Info --}}
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #fffbeb; border-radius: 16px; padding: 0; margin-bottom: 24px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="color: #92400e; font-size: 13px; font-weight: 600; margin: 0 0 8px;">
                                            ⏰ Link ini berlaku selama <strong>60 menit</strong>
                                        </p>
                                        <p style="color: #92400e; font-size: 13px; font-weight: 500; margin: 0; line-height: 1.5;">
                                            Jika Anda tidak merasa meminta reset password, abaikan email ini. Password Anda tidak akan berubah.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            {{-- Fallback Link --}}
                            <p style="color: #9ca3af; font-size: 12px; margin: 0; text-align: center; line-height: 1.6;">
                                Tombol tidak berfungsi? Copy dan paste link berikut ke browser:
                            </p>
                            <p style="color: #6b7280; font-size: 11px; margin: 8px 0 0; text-align: center; word-break: break-all; background-color: #f9fafb; padding: 12px 16px; border-radius: 12px; border: 1px solid #e5e7eb;">
                                {{ $resetLink }}
                            </p>
                        </td>
                    </tr>

                    {{-- FOOTER --}}
                    <tr>
                        <td style="background-color: #f9fafb; padding: 24px 40px; border-top: 1px solid #e5e7eb;">
                            <p style="color: #9ca3af; font-size: 12px; font-weight: 500; margin: 0; text-align: center; line-height: 1.6;">
                                Email ini dikirim otomatis oleh <strong style="color: #6b7280;">SMEconE Hub</strong>.<br>
                                Mohon jangan balas email ini.
                            </p>
                            <p style="color: #d1d5db; font-size: 11px; margin: 12px 0 0; text-align: center;">
                                &copy; {{ date('Y') }} SMEconE Hub. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
