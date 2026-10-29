<div class="header">
  <div class="container">
    <div class="w3layouts_logo">
      <a href="{{ route('home') }}"><h1>One<span>Movies</span></h1></a>
    </div>
    <div class="w3_search">
      <form action="{{ route('movies') }}" method="get">
        <input type="text" name="search" placeholder="Search movies..." required="">
        <input type="submit" value="Go">
      </form>
    </div>
    <div class="w3l_sign_in_register">
      <ul>
        @auth
          <li class="dropdown user-nav-dropdown">
            <a href="#" class="dropdown-toggle user-toggle-btn" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-user"></i>
              <span class="user-display-name">{{ strtoupper(Auth::user()->name) }}</span>
              <span class="user-display-role">({{ strtoupper(Auth::user()->role ? Auth::user()->role->name : (Auth::user()->isAdmin() ? 'ADMIN' : 'USER')) }})</span>
              <span class="caret"></span>
            </a>
            <ul class="dropdown-menu user-dropdown-menu">
              @if(Auth::user()->isAdmin())
                <li class="user-menu-item">
                  <a href="{{ route('admin.dashboard') }}" class="item-admin">
                    <i class="fa fa-tachometer"></i>
                    <span>DASHBOARD ADMIN</span>
                  </a>
                </li>
              @endif
              <li class="user-menu-item">
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="item-logout">
                  <i class="fa fa-sign-out"></i>
                  <span>Logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                  @csrf
                </form>
              </li>
            </ul>
          </li>

          <style>
            .user-nav-dropdown {
              position: relative !important;
              padding-right: 0 !important;
            }
            .user-toggle-btn {
              background: #ff8d1b !important;
              color: #ffffff !important;
              padding: 7px 16px !important;
              font-weight: bold !important;
              text-decoration: none !important;
              display: inline-flex !important;
              align-items: center !important;
              border-radius: 3px !important;
              cursor: pointer !important;
            }
            .user-toggle-btn:hover,
            .user-toggle-btn:focus,
            .open > .user-toggle-btn {
              background: #e07b14 !important;
              color: #ffffff !important;
            }
            .user-toggle-btn i.fa-user {
              color: #ffffff !important;
              margin-right: 6px !important;
              padding-right: 0 !important;
              font-size: 14px !important;
            }
            .user-display-name {
              color: #ffffff !important;
              font-weight: bold !important;
              text-transform: uppercase !important;
            }
            .user-display-role {
              color: rgba(0, 0, 0, 0.4) !important;
              font-size: 11px !important;
              font-weight: bold !important;
              margin-left: 4px !important;
              text-transform: uppercase !important;
            }
            .user-toggle-btn .caret {
              margin-left: 6px !important;
              border-top-color: #ffffff !important;
              vertical-align: middle !important;
            }
            .user-dropdown-menu {
              border-radius: 4px !important;
              padding: 0 !important;
              overflow: hidden !important;
              border: 1px solid rgba(0, 0, 0, 0.15) !important;
              box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15) !important;
              min-width: 195px !important;
              margin-top: 2px !important;
              left: 0 !important;
              right: auto !important;
            }
            .user-dropdown-menu .user-menu-item {
              display: block !important;
              width: 100% !important;
              padding: 0 !important;
              margin: 0 !important;
            }
            .user-dropdown-menu .user-menu-item a.item-admin {
              background: #212121 !important;
              color: #ff8d1b !important;
              font-weight: bold !important;
              font-size: 12px !important;
              letter-spacing: 0.5px !important;
              text-transform: uppercase !important;
              padding: 10px 16px !important;
              display: flex !important;
              align-items: center !important;
              text-decoration: none !important;
              transition: background 0.2s;
            }
            .user-dropdown-menu .user-menu-item a.item-admin:hover {
              background: #111111 !important;
              color: #ffa03a !important;
            }
            .user-dropdown-menu .user-menu-item a.item-admin i {
              color: #ff8d1b !important;
              font-size: 15px !important;
              margin-right: 10px !important;
              padding-right: 0 !important;
              width: 16px !important;
              text-align: center !important;
            }
            .user-dropdown-menu .user-menu-item a.item-logout {
              background: #ffffff !important;
              color: #333333 !important;
              font-weight: 500 !important;
              font-size: 14px !important;
              text-transform: none !important;
              padding: 10px 16px !important;
              display: flex !important;
              align-items: center !important;
              text-decoration: none !important;
              transition: background 0.2s, color 0.2s;
            }
            .user-dropdown-menu .user-menu-item a.item-logout:hover {
              background: #f5f5f5 !important;
              color: #000000 !important;
            }
            .user-dropdown-menu .user-menu-item a.item-logout i {
              color: #333333 !important;
              font-size: 15px !important;
              margin-right: 10px !important;
              padding-right: 0 !important;
              width: 16px !important;
              text-align: center !important;
            }
          </style>
        @else
          <li><a href="#" data-toggle="modal" data-target="#myModal">Login / Register</a></li>
        @endauth
      </ul>
    </div>
    <div class="clearfix"> </div>
  </div>
</div>