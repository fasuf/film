<div class="footer">
  <div class="container">
    <div class="w3ls_footer_grid">
      <div class="col-md-6 w3ls_footer_grid_left">
        <div class="w3ls_footer_grid_left1">
          <h2>Subscribe to us</h2>
          <div class="w3ls_footer_grid_left1_pos">
            <form action="{{ route('subscribe') }}" method="POST">
              @csrf
              <input type="email" name="email" value="{{ old('email', Auth::user()?->email) }}" placeholder="Your email..." required="">
              <input type="submit" value="Send">
            </form>
          </div>
          @error('email')
            <p style="color: #ff8c1a; font-size: 13px; margin-top: 8px; font-weight: 500;">
              <i class="fa fa-exclamation-circle"></i> {{ $message }}
            </p>
          @enderror
        </div>
      </div>
      <div class="col-md-6 w3ls_footer_grid_right">
        <a href="{{ route('home') }}"><h2>One<span>Movies</span></h2></a>
      </div>
      <div class="clearfix"> </div>
    </div>
    <div class="col-md-5 w3ls_footer_grid1_left">
      <p>&copy; 2016 One Movies. All rights reserved | Design by <a href="http://w3layouts.com/">W3layouts</a></p>
    </div>
    <div class="col-md-7 w3ls_footer_grid1_right">
      <ul>
        <li>
          <a href="{{ route('movies') }}">Movies</a>
        </li>
        <li>
          <a href="{{ route('genre', 'Action') }}">Action</a>
        </li>
        <li>
          <a href="{{ route('genre', 'Adventure') }}">Adventure</a>
        </li>
        <li>
          <a href="{{ route('genre', 'Comedy') }}">Comedy</a>
        </li>
        <li>
          <a href="{{ route('faq') }}">FAQ</a>
        </li>
      </ul>
    </div>
    <div class="clearfix"> </div>
  </div>
</div>
