@extends('templates.index')

@section('content')
<div class="general-agileits-w3l">
  <div class="w3l-medile-movies-grids">
    <!-- /movie-browse-agile -->
      <div class="movie-browse-agile">
          <!--/browse-agile-w3ls -->
          <div class="browse-agile-w3ls general-w3ls">
            <div class="tittle-head">
              @if ($genreFilm !== null)
                <h4 class="latest-text">Movies Of Genre {{ $genreFilm->name }}</h4>
              @else
                <h4 class="latest-text">List Of Movies</h4>
              @endif
              <div class="container">
                <div class="agileits-single-top">
                  <ol class="breadcrumb">
                    <li><a href="{{ route('home') }}">Home</a></li>
                    <li><a href="{{ route('movies') }}">Movies</a></li>
                    @if ($genreFilm !== null)
                      <li class="active">Genre {{ $genreFilm->name }}</li>
                    @else
                      <li class="active">All Movies</li>
                    @endif
                  </ol>
                </div>
              </div>
            </div>
            <div class="container">
              <div class="browse-inner">
              @forelse ($films as $film)
                <div class="col-md-2 w3l-movie-gride-agile">
                  <a href="{{ route('movies.show', $film->id) }}" class="hvr-shutter-out-horizontal">
                    <img src="{{ asset($film->poster) }}" title="{{ $film->title }}" alt="{{ $film->title }}" />
                    <div class="w3l-action-icon">
                      <i class="fa fa-play-circle" aria-hidden="true"></i>
                    </div>
                  </a>
                  <div class="mid-1">
                    <div class="w3l-movie-text">
                      <h6><a href="{{ route('movies.show', $film->id) }}">{{ $film->title }}</a></h6>							
                    </div>
                    <div class="mid-2">
                      <p>{{ $film->year }}</p>
                      <div class="block-stars">
                        <ul class="w3l-ratings">
                          <li><a href="#"><i class="fa fa-star" aria-hidden="true"></i></a></li>
                          <li><a href="#"><i class="fa fa-star" aria-hidden="true"></i></a></li>
                          <li><a href="#"><i class="fa fa-star" aria-hidden="true"></i></a></li>
                          <li><a href="#"><i class="fa fa-star-half-o" aria-hidden="true"></i></a></li>
                          <li><a href="#"><i class="fa fa-star-o" aria-hidden="true"></i></a></li>
                        </ul>
                      </div>
                      <div class="clearfix"></div>
                    </div>
                  </div>
                  @if ($film->year == now()->year)
                  <div class="ribben two">
                    <p>NEW</p>
                  </div>
                  @endif
                </div>
              @empty
                <div class="col-md-12 text-center" style="padding: 60px 15px; margin: 20px 0;">
                  <div style="background: #f8f9fa; border: 1px dashed #dcdcdc; border-radius: 8px; padding: 40px 20px;">
                    <i class="fa fa-film" style="font-size: 48px; color: #ff8d1b; margin-bottom: 15px; display: block;"></i>
                    <h4 style="color: #333; font-weight: 600; margin-bottom: 10px;">
                      @if ($genreFilm !== null)
                        Belum ada film di genre ini
                      @else
                        Belum ada film yang tersedia
                      @endif
                    </h4>
                    <p style="color: #777; font-size: 14px;">
                      @if ($genreFilm !== null)
                        Saat ini belum ada koleksi film untuk genre <strong>{{ $genreFilm->name }}</strong>.
                      @else
                        Saat ini belum ada data film yang dapat ditampilkan.
                      @endif
                    </p>
                    <a href="{{ route('movies') }}" class="btn btn-warning" style="margin-top: 15px; background: #ff8d1b; border-color: #ff8d1b; font-weight: 500;">
                      <i class="fa fa-arrow-left"></i> Lihat Semua Film
                    </a>
                  </div>
                </div>
              @endforelse
              <div class="clearfix"> </div>
            </div>
          </div>
        </div>
      <!--//browse-agile-w3ls -->
          <div class="blog-pagenat-wthree">
            @if ($films->hasPages())
                <nav>
                    <ul class="pagination">
                        {{-- Previous Page Link --}}
                        @if ($films->onFirstPage())
                            <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.previous')">
                                <span class="frist" aria-hidden="true">&lsaquo; Prev</span>
                            </li>
                        @else
                            <li>
                                <a class="frist" href="{{ $films->previousPageUrl() }}" rel="prev" aria-label="@lang('pagination.previous')">&lsaquo; Prev</a>
                            </li>
                        @endif
                        {{-- <Number Page --}}
                        @for ($numberPage = 1; $numberPage <= $films->lastPage(); $numberPage++)
                          @if ($numberPage == $films->currentPage())
                            <li class="disabled" aria-disabled="true" >
                              <span class="frist" aria-hidden="true">{{ $numberPage }}</span>
                            </li>
                          @else
                            <li><a href="{{ $films->url($numberPage) }}" class="frist">{{ $numberPage }}</a></li>
                          @endif
                        @endfor
                        {{-- Number Page/> --}}
                        {{-- Next Page Link --}}
                        @if ($films->hasMorePages())
                            <li>
                                <a class="frist" href="{{ $films->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">Next &rsaquo;</a>
                            </li>
                        @else
                            <li class="disabled" aria-disabled="true" aria-label="@lang('pagination.next')">
                                <span class="frist" aria-hidden="true">Next &rsaquo;</span>
                            </li>
                        @endif
                    </ul>
                </nav>
            @endif
          </div>
        </div>
    <!-- //movie-browse-agile -->
    </div>	
  </div>
<!-- //w3l-medile-movies-grids -->
</div>
@endsection
