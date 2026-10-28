<style>
  /* Sembunyikan ikon mata bawaan browser (misal: Microsoft Edge ::-ms-reveal) */
  input::-ms-reveal,
  input::-ms-clear {
    display: none !important;
  }
  .form-module .password-wrapper {
    position: relative;
    margin: 0 0 20px;
  }
  .form-module .password-wrapper input[type="password"],
  .form-module .password-wrapper input[type="text"] {
    margin: 0 !important;
  }
  .form-module .show-password-wrapper {
    display: flex;
    align-items: center;
    margin-top: -8px;
    margin-bottom: 18px;
  }
  .form-module .show-password-wrapper label {
    display: inline-flex;
    align-items: center;
    gap: 7px;
    font-size: 13px;
    color: #666;
    cursor: pointer;
    font-weight: normal;
    margin-bottom: 0;
    user-select: none;
  }
  .form-module .show-password-wrapper input[type="checkbox"] {
    margin: 0 !important;
    cursor: pointer;
    width: 14px !important;
    height: 14px !important;
    display: inline-block !important;
    vertical-align: middle;
  }
</style>
<div class="modal video-modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModal">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        Sign In & Sign Up
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <section>
        <div class="modal-body">
          <div class="w3_login_module">
            <div class="module form-module">
              <div class="toggle"><i class="fa fa-pencil"></i>
              <div class="tooltip">REGISTER</div>
              </div>
              <div class="form form-login">
              <h3>Login to your account</h3>
              {{-- Display login errors / alerts --}}
              @if($errors->has('login'))
                <div style="background:#ff4444;color:#fff;padding:8px 12px;border-radius:4px;margin-bottom:10px;font-size:13px;">
                  {{ $errors->first('login') }}
                </div>
              @endif
              @if(session('error'))
                <div style="background:#ff4444;color:#fff;padding:8px 12px;border-radius:4px;margin-bottom:10px;font-size:13px;">
                  <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
                </div>
              @endif
              @if(session('success'))
                <div style="background:#00C851;color:#fff;padding:8px 12px;border-radius:4px;margin-bottom:10px;font-size:13px;">
                  <i class="fa fa-check-circle"></i> {{ session('success') }}
                </div>
              @endif

              {{-- Google Login Button --}}
              <a href="{{ route('google.login') }}" style="display:flex;align-items:center;justify-content:center;gap:10px;width:100%;padding:11px 0;background:#fff;color:#444;border:1px solid #ddd;border-radius:4px;font-size:14px;font-weight:600;text-decoration:none;cursor:pointer;transition:all .2s;box-shadow:0 1px 3px rgba(0,0,0,0.08);margin-bottom:18px;" onmouseover="this.style.background='#f7f7f7';this.style.borderColor='#bbb';" onmouseout="this.style.background='#fff';this.style.borderColor='#ddd';">
                <svg width="20" height="20" viewBox="0 0 48 48"><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/></svg>
                Login dengan Google
              </a>

              <div style="display:flex;align-items:center;gap:12px;margin-bottom:18px;">
                <div style="flex:1;height:1px;background:#ddd;"></div>
                <span style="font-size:12px;color:#999;white-space:nowrap;">atau login dengan email</span>
                <div style="flex:1;height:1px;background:#ddd;"></div>
              </div>

              <form action="{{ route('login') }}" method="post">
                @csrf
                <input type="hidden" name="redirect_to" value="{{ session('url.intended', request('redirect', url()->current())) }}">
                <input type="email" name="email" placeholder="Email" required="" value="{{ session('active_tab') === 'forgot' ? '' : old('email') }}">
                @if(session('active_tab') !== 'forgot')
                  @error('email')
                    <span style="color:#ff4444;font-size:12px;display:block;margin:-8px 0 8px;">{{ $message }}</span>
                  @enderror
                @endif
                <div class="password-wrapper">
                  <input type="password" name="password" placeholder="Password" required="">
                </div>
                @error('password')
                  <span style="color:#ff4444;font-size:12px;display:block;margin:-10px 0 12px;">{{ $message }}</span>
                @enderror
                <div class="show-password-wrapper">
                  <label>
                    <input type="checkbox" class="show-password-checkbox">
                    <span>Tampilkan sandi</span>
                  </label>
                </div>
                <input type="submit" value="Login">
              </form>
              </div>
              <div class="form form-register">
              <h3>Create an account</h3>
              {{-- Display register errors --}}
              @if($errors->any() && !$errors->has('login') && session('active_tab') !== 'forgot')
                <div style="background:#ff4444;color:#fff;padding:8px 12px;border-radius:4px;margin-bottom:10px;font-size:13px;">
                  <ul style="margin:0;padding-left:16px;">
                    @foreach($errors->all() as $error)
                      @if($error !== $errors->first('login'))
                        <li>{{ $error }}</li>
                      @endif
                    @endforeach
                  </ul>
                </div>
              @endif
              @if(session('error') && session('active_tab') === 'register')
                <div style="background:#ff4444;color:#fff;padding:8px 12px;border-radius:4px;margin-bottom:10px;font-size:13px;">
                  <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
                </div>
              @endif

              {{-- Primary: Google Registration Button --}}
              <a href="{{ route('google.register') }}" style="display:flex;align-items:center;justify-content:center;gap:10px;width:100%;padding:11px 0;background:#fff;color:#444;border:1px solid #ddd;border-radius:4px;font-size:14px;font-weight:600;text-decoration:none;cursor:pointer;transition:all .2s;box-shadow:0 1px 3px rgba(0,0,0,0.08);margin-bottom:18px;" onmouseover="this.style.background='#f7f7f7';this.style.borderColor='#bbb';" onmouseout="this.style.background='#fff';this.style.borderColor='#ddd';">
                <svg width="20" height="20" viewBox="0 0 48 48"><path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/><path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/><path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/><path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/></svg>
                Daftar dengan Google
              </a>

              <div style="display:flex;align-items:center;gap:12px;margin-bottom:18px;">
                <div style="flex:1;height:1px;background:#ddd;"></div>
                <span style="font-size:12px;color:#999;white-space:nowrap;">atau daftar manual</span>
                <div style="flex:1;height:1px;background:#ddd;"></div>
              </div>

              {{-- Secondary: Manual Registration Form (gmail only) --}}
              <form action="{{ route('register') }}" method="post">
                @csrf
                <input type="hidden" name="redirect_to" value="{{ session('url.intended', request('redirect', url()->current())) }}">
                <input type="text" name="name" placeholder="Nama Lengkap" required="" value="{{ old('name') }}" maxlength="255">
                @error('name')
                  <span style="color:#ff4444;font-size:12px;display:block;margin:-8px 0 8px;">{{ $message }}</span>
                @enderror
                <input type="email" name="email" placeholder="Email (@gmail.com)" required="" value="{{ session('active_tab') === 'forgot' ? '' : old('email') }}">
                @if(session('active_tab') !== 'forgot')
                  @error('email')
                    <span style="color:#ff4444;font-size:12px;display:block;margin:-8px 0 8px;">{{ $message }}</span>
                  @enderror
                @endif
                <div class="password-wrapper">
                  <input type="password" name="password" placeholder="Password (min 8 karakter)" required="" minlength="8">
                </div>
                @error('password')
                  <span style="color:#ff4444;font-size:12px;display:block;margin:-10px 0 12px;">{{ $message }}</span>
                @enderror
                <div class="password-wrapper">
                  <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" required="" minlength="8">
                </div>
                <div class="show-password-wrapper">
                  <label>
                    <input type="checkbox" class="show-password-checkbox">
                    <span>Tampilkan sandi</span>
                  </label>
                </div>
                <input type="submit" value="Register">
              </form>
              </div>
              {{-- OTP Verification Form --}}
              <div class="form form-otp" style="display:none;">
              <h3>Verifikasi Email</h3>
              <p style="font-size:13px;color:#666;margin-bottom:15px;line-height:1.5;">
                Kode verifikasi telah dikirim ke <strong>{{ session('register_pending.email') }}</strong>. Masukkan 6 digit kode OTP untuk menyelesaikan pendaftaran.
              </p>
              @if(session('error') && session('active_tab') === 'otp')
                <div style="background:#ff4444;color:#fff;padding:8px 12px;border-radius:4px;margin-bottom:12px;font-size:13px;">
                  <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
                </div>
              @endif
              @if(session('status') && session('active_tab') === 'otp')
                <div style="background:#00C851;color:#fff;padding:8px 12px;border-radius:4px;margin-bottom:12px;font-size:13px;">
                  <i class="fa fa-check-circle"></i> {{ session('status') }}
                </div>
              @endif
              <form action="{{ route('register.verify_otp') }}" method="post">
                @csrf
                <input type="text" name="otp" placeholder="6 Digit Kode OTP" required="" maxlength="6" pattern="[0-9]{6}" style="text-align:center;letter-spacing:4px;font-size:18px;font-weight:bold;" autocomplete="one-time-code">
                @error('otp')
                  <span style="color:#ff4444;font-size:12px;display:block;margin:-8px 0 8px;">{{ $message }}</span>
                @enderror
                <input type="submit" value="Verifikasi">
              </form>

              @php
                $lastResend = session('register_pending.last_resend_at') ?? 0;
                $elapsed = time() - $lastResend;
                $cooldown = max(0, 60 - $elapsed);
              @endphp

              <div style="margin-top:15px;display:flex;justify-content:space-between;align-items:center;">
                <form action="{{ route('register.resend_otp') }}" method="post" id="form-resend-otp" style="margin:0;">
                  @csrf
                  <button type="submit" id="btn-resend-otp" style="background:none;border:none;color:#ff8d1b;font-size:13px;font-weight:600;cursor:pointer;padding:0;text-decoration:none;" {{ $cooldown > 0 ? 'disabled' : '' }}>
                    <i class="fa fa-refresh"></i> Kirim ulang kode <span id="resend-timer-text">{{ $cooldown > 0 ? "({$cooldown}s)" : '' }}</span>
                  </button>
                </form>
                <form action="{{ route('register.cancel_otp') }}" method="post" style="margin:0;">
                  @csrf
                  <button type="submit" style="background:none;border:none;color:#888;font-size:13px;cursor:pointer;padding:0;text-decoration:none;">
                    Kembali ke Register
                  </button>
                </form>
              </div>
              </div>

              {{-- Simple Reset Password Form (no email token) --}}
              <div class="form form-forgot">
              <h3>Reset Password</h3>
              <p style="font-size:13px;color:#666;margin-bottom:15px;line-height:1.5;">Masukkan email, password baru, dan konfirmasi password baru.</p>
              @if(session('active_tab') === 'forgot' && $errors->has('email'))
                <div style="background:#ff4444;color:#fff;padding:8px 12px;border-radius:4px;margin-bottom:10px;font-size:13px;">
                  <i class="fa fa-exclamation-circle"></i> {{ $errors->first('email') }}
                </div>
              @endif
              @if(session('active_tab') === 'forgot' && $errors->has('password'))
                <div style="background:#ff4444;color:#fff;padding:8px 12px;border-radius:4px;margin-bottom:10px;font-size:13px;">
                  <i class="fa fa-exclamation-circle"></i> {{ $errors->first('password') }}
                </div>
              @endif
              <form action="{{ route('password.simple') }}" method="post">
                @csrf
                <input type="email" name="email" placeholder="Email Address" required="" value="{{ session('active_tab') === 'forgot' ? old('email') : '' }}">
                <div class="password-wrapper">
                  <input type="password" name="password" placeholder="Password Baru (min 8 karakter)" required="" minlength="8">
                </div>
                <div class="password-wrapper">
                  <input type="password" name="password_confirmation" placeholder="Konfirmasi Password Baru" required="" minlength="8">
                </div>
                <div class="show-password-wrapper">
                  <label>
                    <input type="checkbox" class="show-password-checkbox">
                    <span>Tampilkan sandi</span>
                  </label>
                </div>
                <input type="submit" value="Reset Password">
              </form>
              <div style="text-align:center;margin-top:15px;">
                <a href="javascript:void(0)" class="btn-back-to-login" style="color:#ff8d1b;font-size:13px;text-decoration:none;font-weight:600;">
                  <i class="fa fa-arrow-left"></i> Kembali ke Login
                </a>
              </div>
              </div>
              <div class="cta"><a href="javascript:void(0)" class="btn-forgot-password">Forgot your password?</a></div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</div>
<script>
  function showLoginForm() {
    $('#myModal .form-login').show();
    $('#myModal .form-register').hide();
    $('#myModal .form-forgot').hide();
    $('#myModal .form-otp').hide();
    $('#myModal .toggle').show();
    $('#myModal .toggle').children('i').removeClass('fa-times').addClass('fa-pencil');
    $('#myModal .toggle').find('.tooltip').text('REGISTER');
    $('#myModal .cta').show();
  }

  function showRegisterForm() {
    $('#myModal .form-login').hide();
    $('#myModal .form-register').show();
    $('#myModal .form-forgot').hide();
    $('#myModal .form-otp').hide();
    $('#myModal .toggle').show();
    $('#myModal .toggle').children('i').removeClass('fa-pencil').addClass('fa-times');
    $('#myModal .toggle').find('.tooltip').text('LOGIN');
    $('#myModal .cta').hide();
  }

  function showForgotForm() {
    $('#myModal .form-login').hide();
    $('#myModal .form-register').hide();
    $('#myModal .form-forgot').show();
    $('#myModal .form-otp').hide();
    $('#myModal .toggle').hide();
    $('#myModal .cta').hide();
  }

  function showOtpForm() {
    $('#myModal .form-login').hide();
    $('#myModal .form-register').hide();
    $('#myModal .form-forgot').hide();
    $('#myModal .form-otp').show();
    $('#myModal .toggle').hide();
    $('#myModal .cta').hide();
  }

  $('#myModal .toggle').click(function(){
    if ($('#myModal .form-login').is(':visible')) {
      showRegisterForm();
    } else {
      showLoginForm();
    }
  });

  $('#myModal .btn-forgot-password').click(function(e){
    e.preventDefault();
    $('#myModal').modal('show');
    showForgotForm();
  });

  $('#myModal .btn-back-to-login').click(function(e){
    e.preventDefault();
    showLoginForm();
  });

  $('#myModal').on('show.bs.modal', function(){
    @if(session('active_tab') === 'otp' || session('register_pending'))
      showOtpForm();
    @elseif(session('active_tab') === 'forgot')
      showForgotForm();
    @elseif(session('active_tab') === 'register' || ($errors->any() && !$errors->has('login') && session('active_tab') !== 'forgot'))
      showRegisterForm();
    @else
      showLoginForm();
    @endif
  });

  $('#myModal').on('hidden.bs.modal', function(){
    showLoginForm();
  });
</script>

<script>
  // Fitur Tampilkan Sandi (Show/Hide Password) dengan Vanilla JS
  (function () {
    function setupPasswordToggles() {
      var modal = document.getElementById('myModal');
      if (!modal) return;

      var forms = modal.querySelectorAll('.form form');
      forms.forEach(function (form) {
        var passwordInputs = form.querySelectorAll('input[type="password"], input[data-pwd="1"]');
        if (!passwordInputs.length) return;

        // Tandai input agar tetap dapat dikenali saat bertipe "text"
        passwordInputs.forEach(function (inp) {
          inp.setAttribute('data-pwd', '1');
        });

        var checkbox = form.querySelector('.show-password-checkbox');

        // Checkbox mengontrol input di form-nya sendiri
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
    }

    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', setupPasswordToggles);
    } else {
      setupPasswordToggles();
    }
  })();
</script>
{{-- Auto-open modal if there are validation errors or showLoginModal flag --}}
@if(!request()->routeIs('login', 'register') && ($errors->any() || session('showLoginModal') || session('status') || session('active_tab') || session('register_pending')))
<script>
  $(document).ready(function(){
    $('#myModal').modal('show');
    @if(session('active_tab') === 'otp' || session('register_pending'))
      showOtpForm();
    @elseif(session('active_tab') === 'forgot')
      showForgotForm();
    @elseif(session('active_tab') === 'register' || ($errors->any() && !$errors->has('login') && session('active_tab') !== 'forgot'))
      showRegisterForm();
    @else
      showLoginForm();
    @endif
  });
</script>
@endif

<script>
  // Countdown timer untuk tombol kirim ulang kode OTP
  (function () {
    var cooldown = {{ isset($cooldown) ? $cooldown : 0 }};
    if (cooldown > 0) {
      var btn = document.getElementById('btn-resend-otp');
      var timerText = document.getElementById('resend-timer-text');
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