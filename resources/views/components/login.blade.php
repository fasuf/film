@extends('templates.index')

@section('content')
<div class="faq" style="padding: 35px 0 70px;">
    <div class="container">
        <div class="agileits-single-top" style="margin-bottom: 25px;">
            <ol class="breadcrumb">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li class="active">{{ $isRegister ? 'Daftar Akun' : 'Login' }}</li>
            </ol>
        </div>

        {{-- Pesan notifikasi khusus halaman login --}}
        @if(!empty($notice) || session('notice'))
            <div class="alert alert-warning text-center" style="max-width: 440px; margin: 0 auto 20px; border-radius: 4px; background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; font-size: 14px; padding: 12px 18px; box-shadow: 0 1px 3px rgba(0,0,0,0.05);">
                <i class="fa fa-info-circle" style="margin-right: 5px;"></i> {{ $notice ?? session('notice') }}
            </div>
        @endif

@php
    $isOtp = session('active_tab') === 'otp' || session('register_pending');
@endphp

        <div class="w3_login_module">
            <div class="module form-module" style="margin: 0 auto; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border-radius: 4px; overflow: hidden; background: #fff;">
                <div class="toggle" title="Klik untuk beralih antara Login dan Daftar" style="cursor: pointer; {{ $isOtp ? 'display: none;' : '' }}">
                    <i class="fa {{ $isRegister ? 'fa-times' : 'fa-pencil' }}"></i>
                    <div class="tooltip" style="font-size: 11px;">{{ $isRegister ? 'LOGIN' : 'REGISTER' }}</div>
                </div>

                {{-- FORM LOGIN --}}
                <div class="form form-login-tab" style="{{ ($isRegister || $isOtp) ? 'display: none;' : 'display: block;' }}; padding: 30px;">
                    <h3 style="margin-bottom: 20px; font-weight: 700; color: #212121; text-align: center;">Login ke Akun Anda</h3>

                    @if($errors->has('login'))
                        <div style="background: #ff4444; color: #fff; padding: 10px 14px; border-radius: 4px; margin-bottom: 15px; font-size: 13px;">
                            <i class="fa fa-exclamation-circle"></i> {{ $errors->first('login') }}
                        </div>
                    @endif

                    @if(session('error') && session('active_tab') !== 'otp')
                        <div style="background: #ff4444; color: #fff; padding: 10px 14px; border-radius: 4px; margin-bottom: 15px; font-size: 13px;">
                            <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
                        </div>
                    @endif

                    @if(session('success'))
                        <div style="background: #00C851; color: #fff; padding: 10px 14px; border-radius: 4px; margin-bottom: 15px; font-size: 13px;">
                            <i class="fa fa-check-circle"></i> {{ session('success') }}
                        </div>
                    @endif

                    {{-- Google Login Button --}}
                    <a href="{{ route('google.login') }}" style="display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%; padding: 12px 0; background: #fff; color: #444; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; font-weight: 600; text-decoration: none; cursor: pointer; transition: all .2s; box-shadow: 0 1px 3px rgba(0,0,0,0.08); margin-bottom: 20px;" onmouseover="this.style.background='#f7f7f7';this.style.borderColor='#bbb';" onmouseout="this.style.background='#fff';this.style.borderColor='#ddd';">
                        <svg width="20" height="20" viewBox="0 0 48 48"><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/></svg>
                        Login dengan Google
                    </a>

                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                        <div style="flex: 1; height: 1px; background: #ddd;"></div>
                        <span style="font-size: 12px; color: #999; white-space: nowrap;">atau login dengan email</span>
                        <div style="flex: 1; height: 1px; background: #ddd;"></div>
                    </div>

                    <form action="{{ route('login.post') }}" method="POST">
                        @csrf
                        <input type="hidden" name="redirect_to" value="{{ $redirectTo }}">

                        <input type="email" name="email" placeholder="Email Address" required value="{{ old('email') }}" style="margin-bottom: 12px; width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                        @error('email')
                            <span style="color: #ff4444; font-size: 12px; display: block; margin: -6px 0 10px; font-weight: bold;">{{ $message }}</span>
                        @enderror

                        <div class="password-wrapper" style="position: relative; margin-bottom: 15px;">
                            <input type="password" name="password" placeholder="Password" required style="margin-bottom: 0; width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
                        </div>
                        @error('password')
                            <span style="color: #ff4444; font-size: 12px; display: block; margin: -6px 0 10px; font-weight: bold;">{{ $message }}</span>
                        @enderror

                        <div class="show-password-wrapper" style="display: flex; align-items: center; margin-top: -6px; margin-bottom: 14px;">
                            <label style="display: inline-flex; align-items: center; gap: 7px; font-size: 13px; color: #666; cursor: pointer; font-weight: normal; margin-bottom: 0; user-select: none;">
                                <input type="checkbox" class="show-password-checkbox" style="margin: 0; cursor: pointer; width: 14px; height: 14px; vertical-align: middle;">
                                <span>Tampilkan sandi</span>
                            </label>
                        </div>

                        <div style="display: flex; justify-content: flex-end; margin-bottom: 15px; margin-top: -5px;">
                            <a href="javascript:void(0)" class="btn-forgot-password" style="color: #ff8d1b; font-size: 13px; text-decoration: none;">Forgot your password?</a>
                        </div>

                        <input type="submit" value="LOGIN" style="background: #ff8d1b; color: #fff; font-weight: bold; letter-spacing: 0.5px; border-radius: 4px; border: none; padding: 10px; width: 100%; cursor: pointer;">
                    </form>

                    <div style="text-align: center; margin-top: 18px; font-size: 13.5px; color: #666;">
                        Belum punya akun? <a href="javascript:void(0)" class="btn-switch-register" style="color: #ff8d1b; font-weight: bold;">Daftar di sini</a>
                    </div>
                </div>

                {{-- FORM DAFTAR / REGISTER --}}
                <div class="form form-register-tab" style="{{ ($isRegister && !$isOtp) ? 'display: block;' : 'display: none;' }}; padding: 30px;">
                    <h3 style="margin-bottom: 20px; font-weight: 700; color: #212121; text-align: center;">Daftar Akun Baru</h3>

                    @if($errors->any() && !$errors->has('login') && session('active_tab') !== 'otp')
                        <div style="background: #ff4444; color: #fff; padding: 10px 14px; border-radius: 4px; margin-bottom: 15px; font-size: 13px;">
                            <ul style="margin: 0; padding-left: 18px;">
                                @foreach($errors->all() as $error)
                                    @if($error !== $errors->first('login'))
                                        <li>{{ $error }}</li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('error') && session('active_tab') !== 'otp')
                        <div style="background: #ff4444; color: #fff; padding: 10px 14px; border-radius: 4px; margin-bottom: 15px; font-size: 13px;">
                            <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
                        </div>
                    @endif

                    {{-- Primary: Google Registration Button --}}
                    <a href="{{ route('google.register') }}" style="display: flex; align-items: center; justify-content: center; gap: 10px; width: 100%; padding: 12px 0; background: #fff; color: #444; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; font-weight: 600; text-decoration: none; cursor: pointer; transition: all .2s; box-shadow: 0 1px 3px rgba(0,0,0,0.08); margin-bottom: 20px;" onmouseover="this.style.background='#f7f7f7';this.style.borderColor='#bbb';" onmouseout="this.style.background='#fff';this.style.borderColor='#ddd';">
                        <svg width="20" height="20" viewBox="0 0 48 48"><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/></svg>
                        Daftar dengan Google
                    </a>

                    <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px;">
                        <div style="flex: 1; height: 1px; background: #ddd;"></div>
                        <span style="font-size: 12px; color: #999; white-space: nowrap;">atau daftar manual</span>
                        <div style="flex: 1; height: 1px; background: #ddd;"></div>
                    </div>

                    {{-- Secondary: Manual Registration Form (gmail only) --}}
                    <form action="{{ route('register.post') }}" method="POST">
                        @csrf
                        <input type="hidden" name="redirect_to" value="{{ $redirectTo }}">

                        <input type="text" name="name" placeholder="Nama Lengkap" required value="{{ old('name') }}" maxlength="255" style="margin-bottom: 12px; width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                        @error('name')
                            <span style="color: #ff4444; font-size: 12px; display: block; margin: -6px 0 10px; font-weight: bold;">{{ $message }}</span>
                        @enderror

                        <input type="email" name="email" placeholder="Email (@gmail.com)" required value="{{ old('email') }}" style="margin-bottom: 12px; width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px;">
                        @error('email')
                            <span style="color: #ff4444; font-size: 12px; display: block; margin: -6px 0 10px; font-weight: bold;">{{ $message }}</span>
                        @enderror

                        <div class="password-wrapper" style="position: relative; margin-bottom: 12px;">
                            <input type="password" name="password" placeholder="Password (min. 8 karakter)" required minlength="8" style="margin-bottom: 0; width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
                        </div>
                        @error('password')
                            <span style="color: #ff4444; font-size: 12px; display: block; margin: -6px 0 10px; font-weight: bold;">{{ $message }}</span>
                        @enderror

                        <div class="password-wrapper" style="position: relative; margin-bottom: 15px;">
                            <input type="password" name="password_confirmation" placeholder="Ulangi Password" required minlength="8" style="margin-bottom: 0; width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box;">
                        </div>

                        <div class="show-password-wrapper" style="display: flex; align-items: center; margin-top: -6px; margin-bottom: 14px;">
                            <label style="display: inline-flex; align-items: center; gap: 7px; font-size: 13px; color: #666; cursor: pointer; font-weight: normal; margin-bottom: 0; user-select: none;">
                                <input type="checkbox" class="show-password-checkbox" style="margin: 0; cursor: pointer; width: 14px; height: 14px; vertical-align: middle;">
                                <span>Tampilkan sandi</span>
                            </label>
                        </div>

                        <input type="submit" value="DAFTAR SEKARANG" style="background: #ff8d1b; color: #fff; font-weight: bold; letter-spacing: 0.5px; border-radius: 4px; border: none; padding: 10px; width: 100%; cursor: pointer;">
                    </form>

                    <div style="text-align: center; margin-top: 18px; font-size: 13.5px; color: #666;">
                        Sudah punya akun? <a href="javascript:void(0)" class="btn-switch-login" style="color: #ff8d1b; font-weight: bold;">Login di sini</a>
                    </div>
                </div>

                {{-- FORM OTP VERIFICATION --}}
                <div class="form form-otp-tab" style="{{ $isOtp ? 'display: block;' : 'display: none;' }}; padding: 30px;">
                    <h3 style="margin-bottom: 20px; font-weight: 700; color: #212121; text-align: center;">Verifikasi Email</h3>
                    <p style="font-size: 13.5px; color: #666; margin-bottom: 18px; text-align: center; line-height: 1.5;">
                        Kode verifikasi telah dikirim ke <strong>{{ session('register_pending.email') }}</strong>.<br>Masukkan 6 digit kode OTP untuk menyelesaikan pendaftaran.
                    </p>

                    @if(session('error') && session('active_tab') === 'otp')
                        <div style="background: #ff4444; color: #fff; padding: 10px 14px; border-radius: 4px; margin-bottom: 15px; font-size: 13px;">
                            <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
                        </div>
                    @endif
                    @if(session('status') && session('active_tab') === 'otp')
                        <div style="background: #00C851; color: #fff; padding: 10px 14px; border-radius: 4px; margin-bottom: 15px; font-size: 13px;">
                            <i class="fa fa-check-circle"></i> {{ session('status') }}
                        </div>
                    @endif

                    <form action="{{ route('register.verify_otp') }}" method="POST">
                        @csrf
                        <input type="text" name="otp" placeholder="6 Digit Kode OTP" required maxlength="6" pattern="[0-9]{6}" style="text-align: center; letter-spacing: 5px; font-size: 20px; font-weight: bold; margin-bottom: 15px; width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 4px;" autocomplete="one-time-code">
                        @error('otp')
                            <span style="color: #ff4444; font-size: 12px; display: block; margin: -10px 0 10px; font-weight: bold;">{{ $message }}</span>
                        @enderror
                        <input type="submit" value="VERIFIKASI" style="background: #ff8d1b; color: #fff; font-weight: bold; letter-spacing: 0.5px; border-radius: 4px; border: none; padding: 12px; width: 100%; cursor: pointer;">
                    </form>

                    @php
                        $lastResendPage = session('register_pending.last_resend_at') ?? 0;
                        $elapsedPage = time() - $lastResendPage;
                        $cooldownPage = max(0, 60 - $elapsedPage);
                    @endphp

                    <div style="margin-top: 20px; display: flex; justify-content: space-between; align-items: center; font-size: 13px;">
                        <form action="{{ route('register.resend_otp') }}" method="POST" id="form-resend-otp-page" style="margin: 0;">
                            @csrf
                            <button type="submit" id="btn-resend-otp-page" style="background: none; border: none; color: #ff8d1b; font-weight: 600; cursor: pointer; padding: 0;" {{ $cooldownPage > 0 ? 'disabled' : '' }}>
                                <i class="fa fa-refresh"></i> Kirim ulang kode <span id="resend-timer-page">{{ $cooldownPage > 0 ? "({$cooldownPage}s)" : '' }}</span>
                            </button>
                        </form>
                        <form action="{{ route('register.cancel_otp') }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" style="background: none; border: none; color: #888; cursor: pointer; padding: 0;">
                                Kembali ke Register
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleForms() {
        var icon = $('.faq .toggle').children('i');
        icon.toggleClass('fa-pencil').toggleClass('fa-times');

        var tooltip = $('.faq .toggle').find('.tooltip');
        if (icon.hasClass('fa-pencil')) {
            tooltip.text('REGISTER');
        } else {
            tooltip.text('LOGIN');
        }

        $('.form-login-tab, .form-register-tab').animate({
            height: "toggle",
            'padding-top': 'toggle',
            'padding-bottom': 'toggle',
            opacity: "toggle"
        }, "slow");
    }

    $('.faq .toggle').click(function(){
        toggleForms();
    });

    $('.btn-switch-register').click(function(e){
        e.preventDefault();
        toggleForms();
    });

    $('.btn-switch-login').click(function(e){
        e.preventDefault();
        toggleForms();
    });

    // Fitur Tampilkan Sandi (Show/Hide Password) dengan Vanilla JS
    (function () {
        var forms = document.querySelectorAll('.faq .form form');
        forms.forEach(function (form) {
            var passwordInputs = form.querySelectorAll('input[type="password"], input[data-pwd="1"]');
            if (!passwordInputs.length) return;

            passwordInputs.forEach(function (inp) {
                inp.setAttribute('data-pwd', '1');
            });

            var checkbox = form.querySelector('.show-password-checkbox');

            if (checkbox) {
                checkbox.addEventListener('change', function () {
                    var isChecked = checkbox.checked;
                    var inputs = form.querySelectorAll('input[data-pwd="1"]');
                    inputs.forEach(function (input) {
                        input.type = isChecked ? 'text' : 'password';
                    });
                });
            }
        });
    })();

    // Countdown timer untuk tombol kirim ulang kode OTP
    (function () {
        var cooldown = {{ isset($cooldownPage) ? $cooldownPage : 0 }};
        if (cooldown > 0) {
            var btn = document.getElementById('btn-resend-otp-page');
            var timerText = document.getElementById('resend-timer-page');
            var interval = setInterval(function () {
                cooldown--;
                if (cooldown <= 0) {
                    clearInterval(interval);
                    if (btn) btn.removeAttribute('disabled');
                    if (timerText) timerText.innerText = '';
                } else {
                    if (timerText) timerText.innerText = '(' + cooldown + 's)';
                }
            }, 1000);
        }
    })();
</script>
@endsection
