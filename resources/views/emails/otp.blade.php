<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Verifikasi OTP</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f4f7; font-family: 'Helvetica Neue', Arial, sans-serif; color: #333333; -webkit-font-smoothing: antialiased;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f4f4f7; padding: 30px 15px;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 520px; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.08);">
                    <!-- Header -->
                    <tr>
                        <td align="center" style="background-color: #212121; padding: 25px 20px; border-bottom: 3px solid #ff8c1a;">
                            <h1 style="margin: 0; font-size: 24px; font-weight: bold; color: #ffffff; letter-spacing: 1px;">
                                ONE<span style="color: #ff8c1a;">MOVIES</span>
                            </h1>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 35px 30px;">
                            <h2 style="margin: 0 0 15px; font-size: 20px; font-weight: 600; color: #212121;">
                                Halo, {{ $name }}!
                            </h2>
                            <p style="margin: 0 0 20px; font-size: 15px; line-height: 1.6; color: #555555;">
                                Kami mendeteksi adanya upaya login dari perangkat atau browser baru. Gunakan kode verifikasi (OTP) berikut untuk menyelesaikan proses login:
                            </p>
                            
                            <!-- OTP Box -->
                            <div style="background-color: #f9f9fb; border: 2px dashed #ff8c1a; border-radius: 8px; padding: 20px; text-align: center; margin: 25px 0;">
                                <span style="font-size: 34px; font-weight: bold; letter-spacing: 8px; color: #212121; display: inline-block;">
                                    {{ $otp }}
                                </span>
                            </div>
                            
                            <p style="margin: 0 0 15px; font-size: 14px; line-height: 1.6; color: #666666; text-align: center;">
                                <strong style="color: #d9534f;">Kode ini hanya berlaku selama 10 menit.</strong>
                            </p>
                            
                            <p style="margin: 0 0 10px; font-size: 13px; line-height: 1.5; color: #888888; border-top: 1px solid #eeeeee; padding-top: 20px;">
                                Demi keamanan akun Anda, <strong>jangan pernah membagikan kode OTP ini kepada siapa pun</strong>, termasuk pihak yang mengatasnamakan One Movies.
                            </p>
                            
                            <p style="margin: 0; font-size: 13px; line-height: 1.5; color: #888888;">
                                Jika Anda tidak merasa mencoba login, segera amankan akun Anda atau abaikan email ini.
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td align="center" style="background-color: #f9f9fb; padding: 20px; font-size: 12px; color: #999999; border-top: 1px solid #eeeeee;">
                            &copy; {{ date('Y') }} One Movies. Hak cipta dilindungi undang-undang.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

