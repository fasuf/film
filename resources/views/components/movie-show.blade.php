@extends('templates.index')

@push('style')
  <link rel="stylesheet" type="text/css" href="{{ asset('template/list-css/table-style.css') }}" />
  <link rel="stylesheet" type="text/css" href="{{ asset('template/list-css/basictable.css') }}" />
@endpush

@push('scripts')

<script type="text/javascript" src="{{ asset('template/list-js/jquery.basictable.min.js') }}"></script>
 <script type="text/javascript">
    $(document).ready(function() {
      $('#table').basictable();
      $('#table-breakpoint').basictable({
        breakpoint: 768
      });
    });
  </script>
@endpush

@section('content')

<div class="container">
  <br />
  <!-- /w3l-medile-movies-grids -->
    <div class="agileits-single-top">
      <ol class="breadcrumb">
        <li><a href="{{ route('home') }}">Home</a></li>
        <li><a href="{{ route('movies') }}">Movies</a></li>
        <li class="active">{{ $film->title }}</li>
      </ol>
    </div>
    <div class="single-page-agile-info">
                 <!-- /movie-browse-agile -->
                         <div class="show-top-grids-w3lagile">
      <div class="col-sm-8 single-left">
        <div class="song">
          <div class="single-right-grids">

            <div class="col-md-4 single-right-grid-left">
              <a href="{{ route('movies.show', $film->id) }}"><img src="/{{ $film->poster }}" alt="" /></a>
            </div>
            <div class="col-md-8 single-right-grid-right">
              <div class="song-info">
                <h2>{{ $film->title }}</h2>	
                <p class="author"><a href="#" class="author">{{ $film->year }}</a></p>
                <p class="views">{{ $film->sinopsis }}</p>
              </div>
              <div id="myTabContent" class="tab-content">
                <div role="tabpanel" class="tab-pane fade in active" id="home" aria-labelledby="home-tab">
                  <div class="agile-news-table">
                    <table id="table-breakpoint">
                      <thead>
                        <tr>
                        <th style="width: 50%;">Cast / Aktor</th>
                        <th style="width: 50%;">Peran</th>
                        </tr>
                      </thead>
                      <tbody>
                        @forelse ($perans as $peran)
                        <tr>
                          <td class="w3-list-info">
                            <span style="font-weight: 600; color: #212121;">
                              <i class="fa fa-user" style="color: #ff8d1b; margin-right: 6px;"></i>
                              {{ $peran->cast->name ?? 'Cast #' . $peran->cast_id }}
                            </span>
                            @if(!empty($peran->cast?->age))
                              <small style="color: #888; font-size: 11px;">({{ $peran->cast->age }} thn)</small>
                            @endif
                          </td>
                          <td class="w3-list-info">
                            {{ $peran->actor ?: '-' }}
                          </td>
                        </tr>
                        @empty
                        <tr>
                          <td colspan="2" class="w3-list-info" style="text-align: center; color: #888; padding: 20px;">
                            <em>Belum ada data cast</em>
                          </td>
                        </tr>
                        @endforelse
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <div class="clearfix"> </div>
          </div>
        </div>
        <div class="song-grid-right">
          <div class="share">
            <h5>Share this</h5>
            <div class="single-agile-shar-buttons">
            <ul>
              <li>
                <div class="fb-like" data-href="https://www.facebook.com/w3layouts" data-layout="button_count" data-action="like" data-size="small" data-show-faces="false" data-share="false"></div>
                <script>(function(d, s, id) {
                  var js, fjs = d.getElementsByTagName(s)[0];
                  if (d.getElementById(id)) return;
                  js = d.createElement(s); js.id = id;
                  js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.7";
                  fjs.parentNode.insertBefore(js, fjs);
                }(document, 'script', 'facebook-jssdk'));</script>
              </li>
              <li>
                <div class="fb-share-button" data-href="https://www.facebook.com/w3layouts" data-layout="button_count" data-size="small" data-mobile-iframe="true"><a class="fb-xfbml-parse-ignore" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fwww.facebook.com%2Fw3layouts&amp;src=sdkpreparse">Share</a></div>
              </li>
              <li class="news-twitter">
                <a href="https://twitter.com/w3layouts" class="twitter-follow-button" data-show-count="false">Follow @w3layouts</a><script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
              </li>
              <li>
                <a href="https://twitter.com/intent/tweet?screen_name=w3layouts" class="twitter-mention-button" data-show-count="false">Tweet to @w3layouts</a><script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
              </li>
              <li>
                <!-- Place this tag where you want the +1 button to render. -->
                <div class="g-plusone" data-size="medium"></div>

                <!-- Place this tag after the last +1 button tag. -->
                <script type="text/javascript">
                  (function() {
                  var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
                  po.src = 'https://apis.google.com/js/platform.js';
                  var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
                  })();
                </script>
              </li>
            </ul>
          </div>
          </div>
        </div>
        <div class="clearfix"> </div>
        <style>
          .all-comments { background: #ffffff !important; padding: 25px; border-radius: 8px; border: 1px solid #e5e7eb; margin-top: 30px; }
          .all-comments .media { margin: 18px 0 !important; padding-left: 0 !important; border-bottom: 1px solid #e9ecef; padding-bottom: 18px; }
          .all-comments .media:nth-child(3), .all-comments .media:nth-child(5) { padding-left: 0 !important; }
          .all-comments .media-body p { color: #1f2937 !important; font-size: 14.5px !important; line-height: 1.6 !important; margin: 6px 0 8px !important; }
          .all-comments .media h5 { color: #111827 !important; font-size: 15px !important; font-weight: 700 !important; margin: 0 !important; }
          .all-comments .media-body small { color: #6b7280 !important; font-size: 12px !important; }
        </style>
        <div class="all-comments" id="comments">
          <div id="komentar" style="position: relative; top: -20px;"></div>
          <div class="all-comments-info">
            <a href="#komentar" style="color: #ff8d1b; font-weight: bold; font-size: 18px;">
              <i class="fa fa-comments"></i> Ulasan & Komentar ({{ $comments->count() }})
            </a>

            {{-- Flash Notification inside comments --}}
            @if(session('success'))
              <div class="alert alert-success alert-dismissible" role="alert" style="margin-top: 15px; margin-bottom: 15px; border-radius: 4px; background-color: #d4edda; color: #155724; border-color: #c3e6cb; font-size: 14px;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <i class="fa fa-check-circle"></i> {{ session('success') }}
              </div>
            @endif
            @if(session('error'))
              <div class="alert alert-danger alert-dismissible" role="alert" style="margin-top: 15px; margin-bottom: 15px; border-radius: 4px; background-color: #f8d7da; color: #721c24; border-color: #f5c6cb; font-size: 14px;">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <i class="fa fa-exclamation-triangle"></i> {{ session('error') }}
              </div>
            @endif

            @auth
              {{-- Form komentar hanya untuk user yang sudah login --}}
              <div class="agile-info-wthree-box" style="margin-top: 15px; margin-bottom: 25px; background: #fbfbfb; border: 1px solid #eee; border-radius: 6px; padding: 18px;">
                <form action="{{ route('comments.store', $film->id) }}" method="POST">
                  @csrf
                  <div style="margin-bottom: 14px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px;">
                    <div style="color: #222; font-size: 14px; font-weight: 500;">
                      <i class="fa fa-user-circle" style="color: #ff8d1b; font-size: 16px;"></i> Berkomentar sebagai: <strong style="color: #111;">{{ Auth::user()->name }}</strong>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px;">
                      <label style="color: #222; font-weight: bold; margin: 0; font-size: 13px;"><i class="fa fa-star" style="color: #ff8d1b;"></i> Beri Rating:</label>
                      <select name="rating" required style="padding: 6px 12px; border-radius: 4px; border: 1px solid #ccc; background: #fff; color: #111; font-weight: bold; font-size: 13px;">
                        <option value="">-- Pilih Rating --</option>
                        @for($r = 10; $r >= 1; $r--)
                          <option value="{{ $r }}" {{ old('rating', 10) == $r ? 'selected' : '' }}>⭐ {{ $r }} / 10</option>
                        @endfor
                      </select>
                    </div>
                  </div>

                  <textarea name="comment" placeholder="Tuliskan ulasan atau tanggapan Anda tentang film ini..." required style="min-height: 90px; margin-bottom: 8px; width: 100%; border: 1px solid #ccc; border-radius: 4px; padding: 10px; font-size: 14px; color: #222; background: #fff; resize: vertical;">{{ old('comment') }}</textarea>
                  
                  @error('comment')
                    <span style="color:#d9534f; font-size:13px; font-weight:bold; display:block; margin-bottom:8px;">{{ $message }}</span>
                  @enderror
                  @error('rating')
                    <span style="color:#d9534f; font-size:13px; font-weight:bold; display:block; margin-bottom:8px;">{{ $message }}</span>
                  @enderror

                  <input type="submit" value="KIRIM KOMENTAR" style="background: #ff8d1b; color: #fff; font-weight: bold; border: none; padding: 9px 24px; border-radius: 4px; cursor: pointer;">
                  <div class="clearfix"> </div>
                </form>
              </div>
            @else
              {{-- Pesan & Tombol jika user belum login --}}
              <div style="background: #fff8f0; border: 1px solid #ffd8a8; border-radius: 6px; padding: 22px; text-align: center; margin: 20px 0 28px;">
                <i class="fa fa-lock" style="font-size: 30px; color: #ff8d1b; margin-bottom: 10px; display: block;"></i>
                <h4 style="color: #212529; font-size: 16px; margin-bottom: 8px; font-weight: bold;">Ingin Berkomentar?</h4>
                <p style="color: #495057; font-size: 14px; margin-bottom: 16px;">
                  Silakan login terlebih dahulu untuk dapat menulis ulasan dan memberikan rating pada film ini.
                </p>
                <a href="{{ route('login', ['redirect' => route('movies.show', $film->id) . '#komentar']) }}" 
                   class="btn btn-warning" 
                   style="background: #ff8d1b; border: none; font-weight: bold; padding: 9px 24px; border-radius: 4px; color: #fff; text-decoration: none; display: inline-block;">
                  <i class="fa fa-sign-in"></i> Login untuk berkomentar
                </a>
              </div>
            @endauth
          </div>

          <div class="media-grids">
            @forelse ($comments as $comment)
              <div class="media" style="border-bottom: 1px solid #e9ecef; padding-bottom: 16px; margin-bottom: 16px;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 6px;">
                  <h5 style="margin: 0; font-size: 15px; color: #111; font-weight: 700;">
                    <i class="fa fa-user-circle" style="color: #ff8d1b; margin-right: 4px;"></i> {{ !empty($comment->user?->name) ? $comment->user->name : 'Anonim' }}
                  </h5>
                  @if($comment->rating)
                    <span style="background: #fff8e1; color: #b78103; border: 1px solid #ffe082; padding: 2px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;">
                      <i class="fa fa-star" style="color: #ff8d1b;"></i> {{ $comment->rating }}/10
                    </span>
                  @endif
                </div>
                <div class="media-left">
                  <a href="#">
                    <img src="{{ asset('template/images/user.jpg') }}" title="{{ !empty($comment->user?->name) ? $comment->user->name : 'Anonim' }}" alt="avatar" style="width: 44px; height: 44px; border-radius: 50%; object-fit: cover; border: 2px solid #ff8d1b;" onerror="this.src='{{ asset('template/images/user.jpg') }}'" />
                  </a>
                </div>
                <div class="media-body">
                  <p style="color: #212529; line-height: 1.6; font-size: 14px; margin: 4px 0 6px; font-weight: 400;">
                    {{ $comment->comment }}
                  </p>
                  <small style="color: #6c757d; font-size: 12px; display: inline-block;">
                    <i class="fa fa-clock-o"></i> {{ $comment->created_at ? $comment->created_at->format('d M Y, H:i') . ' • ' . $comment->created_at->diffForHumans() : '-' }}
                  </small>
                </div>
              </div>
            @empty
              <div style="text-align: center; color: #6c757d; padding: 30px; font-size: 14px;">
                <i class="fa fa-comment-o" style="font-size: 28px; color: #ccc; margin-bottom: 8px; display: block;"></i>
                <em>Belum ada ulasan untuk film ini. Jadilah yang pertama memberikan ulasan!</em>
              </div>
            @endforelse
          </div>
        </div>
      </div>
      <div class="col-md-4 single-right">
        <h3>Similar Film By Genre</h3>
        @foreach ($filmByGenre as $fGenre)
          <div class="single-grid-right">
            <div class="single-right-grids">
              <div class="col-md-4 single-right-grid-left">
                <a href="{{ route('movies.show', $fGenre->id) }}"><img src="/{{ $fGenre->poster }}" alt="" /></a>
              </div>
              <div class="col-md-8 single-right-grid-right">
                <a href="{{ route('movies.show', $fGenre->id) }}" class="title">{{ $fGenre->title }}</a>
                <p class="author"><a href="#" class="author">{{ $fGenre->year }}</a></p>
                <p class="views" style="text-align: justify; margin-right: 10px">{{ $fGenre->sinopsis }}</p>
              </div>
              <div class="clearfix"> </div>
            </div>
          </div>
        @endforeach
      </div>
      <div class="clearfix"> </div>
    </div>
      <!-- //movie-browse-agile -->
      <!--body wrapper start-->
    <div class="w3_agile_banner_bottom_grid">
      <div id="owl-demo" class="owl-carousel owl-theme">
        @foreach ($filmByRelease as $filmRelease)
          <div class="item">
            <div class="w3l-movie-gride-agile w3l-movie-gride-agile1">
              <a href="{{ route('movies.show', $filmRelease->id) }}" class="hvr-shutter-out-horizontal"><img src="/{{ $filmRelease->poster }}" title="{{ $filmRelease->title }}" class="img-responsive" alt=" " />
                <div class="w3l-action-icon"><i class="fa fa-play-circle" aria-hidden="true"></i></div>
              </a>
              <div class="mid-1 agileits_w3layouts_mid_1_home">
                <div class="w3l-movie-text">
                  <h6><a href="{{ route('movies.show', $filmRelease->id) }}">{{ $filmRelease->title }}</a></h6>							
                </div>
                <div class="mid-2 agile_mid_2_home">
                  <p>{{ $filmRelease->year }}</p>
                  <div class="block-stars">
                    <ul class="w3l-ratings">
                      <li><a href="#"><i class="fa fa-star" aria-hidden="true"></i></a></li>
                      <li><a href="#"><i class="fa fa-star" aria-hidden="true"></i></a></li>
                      <li><a href="#"><i class="fa fa-star" aria-hidden="true"></i></a></li>
                      <li><a href="#"><i class="fa fa-star" aria-hidden="true"></i></a></li>
                      <li><a href="#"><i class="fa fa-star-half-o" aria-hidden="true"></i></a></li>
                    </ul>
                  </div>
                  <div class="clearfix"></div>
                </div>
              </div>
              @if ($filmRelease->year == now()->year)
                <div class="ribben">
                  <p>NEW</p>
                </div>
              @endif
            </div>
          </div>
        @endforeach
      </div>
    </div>
  <!--body wrapper end-->
          
             
      </div>
      <!-- //w3l-latest-movies-grids -->
    </div>	
  </div>
@endsection

@push('scripts')
<script src="js/simplePlayer.js"></script>
<script>
	$("document").ready(function() {
		$("#video").simplePlayer();
	});
</script>
@endpush