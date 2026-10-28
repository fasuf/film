@extends('templates.index')

@section('content')
<div class="faq" style="padding: 35px 0 70px;">
    <div class="container">
        <div class="agileits-single-top" style="margin-bottom: 25px;">
            <ol class="breadcrumb">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li class="active">Reset Password</li>
            </ol>
        </div>

        <div class="w3_login_module">
            <div class="module form-module" style="margin: 0 auto; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border-radius: 4px; overflow: hidden; background: #fff; max-width: 440px;">
                <div class="form" style="display: block; padding: 35px 30px;">
                    <h3 style="margin-bottom: 12px; font-weight: 700; color: #212121; text-align: center; font-size: 20px;">Reset Password</h3>
                    <p style="font-size: 13.5px; color: #666; margin-bottom: 22px; text-align: center; line-height: 1.5;">
                        Silakan buat password baru Anda (minimal 8 karakter).
                    </p>

                    @if($errors->has('email'))
                        <div style="background: #ff4444; color: #fff; padding: 12px 16px; border-radius: 4px; margin-bottom: 20px; font-size: 13.5px;">
                            <i class="fa fa-exclamation-circle" style="margin-right: 6px;"></i> {{ $errors->first('email') }}
                        </div>
                    @endif

                    @if($errors->has('token'))
                        <div style="background: #ff4444; color: #fff; padding: 12px 16px; border-radius: 4px; margin-bottom: 20px; font-size: 13.5px;">
                            <i class="fa fa-exclamation-circle" style="margin-right: 6px;"></i> {{ $errors->first('token') }}
                        </div>
                    @endif

                    <form action="{{ route('password.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <div style="margin-bottom: 14px;">
                            <label for="email" style="font-size: 13px; color: #444; margin-bottom: 6px; display: block; font-weight: 600;">Alamat Email</label>
                            <input type="email" id="email" name="email" required value="{{ old('email', $email) }}" style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; box-sizing: border-box;">
                        </div>

                        <div style="margin-bottom: 14px;">
                            <label for="password" style="font-size: 13px; color: #444; margin-bottom: 6px; display: block; font-weight: 600;">Password Baru (min. 8 karakter)</label>
                            <div class="password-wrapper" style="position: relative;">
                                <input type="password" id="password" name="password" placeholder="Minimal 8 karakter" required minlength="8" style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; box-sizing: border-box;">
                            </div>
                            @error('password')
                                <span style="color: #ff4444; font-size: 12px; display: block; margin-top: 5px; font-weight: bold;">{{ $message }}</span>
                            @enderror
                        </div>

                        <div style="margin-bottom: 14px;">
                            <label for="password_confirmation" style="font-size: 13px; color: #444; margin-bottom: 6px; display: block; font-weight: 600;">Konfirmasi Password Baru</label>
                            <div class="password-wrapper" style="position: relative;">
                                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Ulangi password baru" required minlength="8" style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; box-sizing: border-box;">
                            </div>
                        </div>

                        <div class="show-password-wrapper" style="display: flex; align-items: center; margin-top: -4px; margin-bottom: 18px;">
                            <label style="display: inline-flex; align-items: center; gap: 7px; font-size: 13px; color: #666; cursor: pointer; font-weight: normal; margin-bottom: 0; user-select: none;">
                                <input type="checkbox" class="show-password-checkbox" style="margin: 0; cursor: pointer; width: 14px; height: 14px; vertical-align: middle;">
                                <span>Tampilkan sandi</span>
                            </label>
                        </div>

                        <input type="submit" value="SIMPAN PASSWORD BARU" style="background: #ff8d1b; color: #fff; font-weight: bold; letter-spacing: 0.5px; border-radius: 4px; border: none; padding: 11px; width: 100%; cursor: pointer; font-size: 14px;">
                    </form>

                    <div style="text-align: center; margin-top: 22px; font-size: 13.5px;">
                        <a href="{{ route('login') }}" style="color: #ff8d1b; font-weight: 600; text-decoration: none;">
                            <i class="fa fa-arrow-left" style="margin-right: 5px;"></i> Batal dan Kembali ke Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Fitur Tampilkan Sandi (Show/Hide Password) dengan Vanilla JS
    (function () {
        var form = document.querySelector('.faq .form form');
        if (!form) return;

        var passwordInputs = form.querySelectorAll('input[type="password"], input[data-pwd="1"]');
        if (!passwordInputs.length) return;

        passwordInputs.forEach(function (inp) {
            inp.setAttribute('data-pwd', '1');
        });

        var eyeButtons = form.querySelectorAll('.toggle-password-btn');
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
    })();
</script>
@endsection
