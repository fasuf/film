@extends('templates.index')

@section('content')
<div class="faq" style="padding: 40px 0 80px;">
    <div class="container">
        <div class="agileits-single-top" style="margin-bottom: 25px;">
            <ol class="breadcrumb" style="background: transparent; padding-left: 0;">
                <li><a href="{{ route('home') }}" style="color: #ff8c1a;">Home</a></li>
                <li class="active">Verifikasi Perangkat Baru</li>
            </ol>
        </div>

        <div class="w3_login_module">
            <div class="module form-module" style="max-width: 440px; margin: 0 auto; box-shadow: 0 4px 20px rgba(0,0,0,0.12); border-radius: 6px; overflow: hidden; background: #ffffff; border-top: 4px solid #ff8c1a;">
                <div style="padding: 35px 30px;">
                    <div style="text-align: center; margin-bottom: 25px;">
                        <div style="width: 60px; height: 60px; background: rgba(255, 140, 26, 0.1); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 15px;">
                            <i class="fa fa-shield" style="font-size: 28px; color: #ff8c1a;"></i>
                        </div>
                        <h3 style="font-size: 22px; font-weight: 700; color: #212121; margin: 0 0 8px;">Verifikasi Kode OTP</h3>
                        <p style="font-size: 13px; color: #666; margin: 0; line-height: 1.5;">
                            Kami mendeteksi login dari perangkat baru. Kode OTP 6 digit telah dikirimkan ke email 
                            @if(isset($user))
                                <strong>{{ $user->email }}</strong>
                            @endif
                        </p>
                    </div>

                    @if(session('status'))
                        <div style="background: #e8f5e9; color: #2e7d32; border: 1px solid #c8e6c9; padding: 10px 14px; border-radius: 4px; margin-bottom: 20px; font-size: 13px; text-align: center;">
                            <i class="fa fa-check-circle"></i> {{ session('status') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div style="background: #ffebee; color: #c62828; border: 1px solid #ffcdd2; padding: 10px 14px; border-radius: 4px; margin-bottom: 20px; font-size: 13px; text-align: center;">
                            <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
                        </div>
                    @endif

                    @error('otp')
                        <div style="background: #ffebee; color: #c62828; border: 1px solid #ffcdd2; padding: 10px 14px; border-radius: 4px; margin-bottom: 20px; font-size: 13px; text-align: center;">
                            <i class="fa fa-exclamation-circle"></i> {{ $message }}
                        </div>
                    @enderror

                    <form action="{{ route('otp.verify') }}" method="POST">
                        @csrf
                        <div style="margin-bottom: 20px;">
                            <label for="otp" style="display: block; font-size: 13px; font-weight: 600; color: #444; margin-bottom: 8px; text-align: center;">
                                Masukkan 6 Digit Kode OTP:
                            </label>
                            <input type="text" 
                                   id="otp" 
                                   name="otp" 
                                   maxlength="6" 
                                   pattern="[0-9]{6}" 
                                   inputmode="numeric" 
                                   autocomplete="one-time-code" 
                                   placeholder="Contoh: 123456" 
                                   required 
                                   autofocus
                                   value="{{ old('otp') }}"
                                   style="width: 100%; text-align: center; font-size: 24px; font-weight: bold; letter-spacing: 6px; padding: 12px 15px; border: 2px solid #ddd; border-radius: 4px; outline: none; transition: border-color 0.2s;"
                                   onfocus="this.style.borderColor='#ff8c1a';"
                                   onblur="this.style.borderColor='#ddd';">
                            <small style="display: block; text-align: center; color: #888; font-size: 12px; margin-top: 6px;">
                                <i class="fa fa-clock-o"></i> Kode berlaku selama 10 menit
                            </small>
                        </div>

                        <div style="margin-top: 25px;">
                            <button type="submit" 
                                    style="width: 100%; background: #ff8c1a; color: #ffffff; border: none; padding: 12px 20px; font-size: 15px; font-weight: bold; text-transform: uppercase; border-radius: 4px; cursor: pointer; transition: background 0.2s;"
                                    onmouseover="this.style.background='#e07b14';" 
                                    onmouseout="this.style.background='#ff8c1a';">
                                <i class="fa fa-check"></i> Verifikasi & Lanjutkan
                            </button>
                        </div>
                    </form>

                    <div style="margin-top: 25px; text-align: center; border-top: 1px solid #eee; padding-top: 15px;">
                        <a href="{{ route('login') }}" style="color: #666; font-size: 13px; text-decoration: none;">
                            <i class="fa fa-arrow-left"></i> Kembali ke Halaman Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

