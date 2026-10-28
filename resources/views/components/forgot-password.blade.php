@extends('templates.index')

@section('content')
<div class="faq" style="padding: 35px 0 70px;">
    <div class="container">
        <div class="agileits-single-top" style="margin-bottom: 25px;">
            <ol class="breadcrumb">
                <li><a href="{{ route('home') }}">Home</a></li>
                <li class="active">Lupa Password</li>
            </ol>
        </div>

        <div class="w3_login_module">
            <div class="module form-module" style="margin: 0 auto; box-shadow: 0 4px 15px rgba(0,0,0,0.08); border-radius: 4px; overflow: hidden; background: #fff; max-width: 440px;">
                <div class="form" style="display: block; padding: 35px 30px;">
                    <h3 style="margin-bottom: 12px; font-weight: 700; color: #212121; text-align: center; font-size: 20px;">Lupa Password</h3>
                    <p style="font-size: 13.5px; color: #666; margin-bottom: 22px; text-align: center; line-height: 1.5;">
                        Masukkan alamat email yang terdaftar. Kami akan mengirimkan tautan untuk mengatur ulang password Anda.
                    </p>

                    @if(session('status'))
                        <div style="background: #00C851; color: #fff; padding: 12px 16px; border-radius: 4px; margin-bottom: 20px; font-size: 13.5px;">
                            <i class="fa fa-check-circle" style="margin-right: 6px;"></i> {{ session('status') }}
                        </div>
                    @endif

                    @if($errors->has('email'))
                        <div style="background: #ff4444; color: #fff; padding: 12px 16px; border-radius: 4px; margin-bottom: 20px; font-size: 13.5px;">
                            <i class="fa fa-exclamation-circle" style="margin-right: 6px;"></i> {{ $errors->first('email') }}
                        </div>
                    @endif

                    <form action="{{ route('password.simple') }}" method="POST">
                        @csrf
                        <div style="margin-bottom: 16px;">
                            <label for="email" style="font-size: 13px; color: #444; margin-bottom: 6px; display: block; font-weight: 600;">Alamat Email</label>
                            <input type="email" id="email" name="email" placeholder="nama@email.com" required value="{{ old('email') }}" style="width: 100%; padding: 10px 12px; border: 1px solid #ddd; border-radius: 4px; font-size: 14px; box-sizing: border-box;">
                        </div>

                        <input type="submit" value="KIRIM LINK RESET" style="background: #ff8d1b; color: #fff; font-weight: bold; letter-spacing: 0.5px; border-radius: 4px; border: none; padding: 11px; width: 100%; cursor: pointer; font-size: 14px; margin-top: 5px;">
                    </form>

                    <div style="text-align: center; margin-top: 22px; font-size: 13.5px;">
                        <a href="{{ route('login') }}" style="color: #ff8d1b; font-weight: 600; text-decoration: none;">
                            <i class="fa fa-arrow-left" style="margin-right: 5px;"></i> Kembali ke Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
