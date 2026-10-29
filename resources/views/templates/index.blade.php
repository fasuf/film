
<!--
author: W3layouts
author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE html>
<html lang="en">

@include('templates.component.head')
	
<body>
@if(session('success'))
<div class="top-alert-banner top-alert-success" role="alert">
    <div class="container">
        <div class="top-alert-content">
            <div class="top-alert-message">
                <i class="fa fa-check-circle top-alert-icon"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button type="button" class="top-alert-close" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
</div>
@endif

@if(session('warning'))
<div class="top-alert-banner top-alert-warning" role="alert">
    <div class="container">
        <div class="top-alert-content">
            <div class="top-alert-message">
                <i class="fa fa-exclamation-triangle top-alert-icon-warning"></i>
                <span>{{ session('warning') }}</span>
            </div>
            <button type="button" class="top-alert-close" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    </div>
</div>
@endif

@if(session('success') || session('warning'))
<style>
.top-alert-banner {
    background: #212121;
    color: #ffffff;
    padding: 12px 0;
    width: 100%;
    position: relative;
    z-index: 99999;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.35);
}
.top-alert-success {
    border-bottom: 2px solid #ff8c1a;
}
.top-alert-warning {
    border-bottom: 2px solid #ffaa00;
}
.top-alert-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 15px;
}
.top-alert-message {
    display: flex;
    align-items: center;
    font-size: 15px;
    font-weight: 500;
}
.top-alert-icon {
    color: #ff8c1a;
    font-size: 18px;
    margin-right: 10px;
}
.top-alert-icon-warning {
    color: #ffaa00;
    font-size: 18px;
    margin-right: 10px;
}
.top-alert-close {
    background: transparent;
    border: none;
    color: #ff8c1a;
    font-size: 22px;
    line-height: 1;
    cursor: pointer;
    padding: 0 6px;
    transition: color 0.2s, transform 0.2s;
    outline: none;
}
.top-alert-close:hover {
    color: #ffffff;
    transform: scale(1.15);
}
@media (max-width: 768px) {
    .top-alert-message {
        font-size: 13px;
    }
    .top-alert-banner {
        padding: 10px 0;
    }
}
</style>
@endif
<!-- header -->
@include('templates.component.header')
<!-- //header -->
<!-- bootstrap-pop-up -->
@include('templates.component.login')
<!-- //bootstrap-pop-up -->
<!-- nav -->
@include('templates.component.navbar')
<!-- //nav -->
<div class="general_social_icons">
	<nav class="social">
		<ul>
			<li class="w3_twitter"><a href="#">Twitter <i class="fa fa-twitter"></i></a></li>
			<li class="w3_facebook"><a href="#">Facebook <i class="fa fa-facebook"></i></a></li>
			<li class="w3_dribbble"><a href="#">Dribbble <i class="fa fa-dribbble"></i></a></li>
			<li class="w3_g_plus"><a href="#">Google+ <i class="fa fa-google-plus"></i></a></li>				  
		</ul>
  </nav>
</div>
<!-- Flash Messages -->
<div class="container" style="margin-top: 15px;">
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible" role="alert" style="border-radius: 4px;">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <i class="fa fa-exclamation-triangle"></i> {{ session('error') }}
        </div>
    @endif
</div>
@yield('content')
	<!-- //comedy-w3l-agileits -->
<!-- footer -->
	@include('templates/component/footer')
<!-- //footer -->
<!-- Bootstrap Core JavaScript -->

<script src="{{ asset('template/js/bootstrap.min.js') }}"></script>
<script>
	$(document).ready(function(){
		$(".dropdown").hover(            
			function() {
				$('.dropdown-menu', this).stop( true, true ).slideDown("fast");
				$(this).toggleClass('open');        
			},
        function() {
            $('.dropdown-menu', this).stop( true, true ).slideUp("fast");
            $(this).toggleClass('open');       
					}
    );
});
</script>
<!-- //Bootstrap Core JavaScript -->
<!-- here stars scrolling icon -->
	<script type="text/javascript">
		$(document).ready(function() {
			/*
				var defaults = {
				containerID: 'toTop', // fading element id
				containerHoverID: 'toTopHover', // fading element hover id
				scrollSpeed: 1200,
				easingType: 'linear' 
				};
			*/
			
			$().UItoTop({ easingType: 'easeOutQuart' });
			
			// Top success & warning alert dismiss & auto-hide
			var $topAlerts = $('.top-alert-banner');
			if ($topAlerts.length) {
				$topAlerts.on('click', '.top-alert-close', function() {
					$(this).closest('.top-alert-banner').slideUp(400, function() {
						$(this).remove();
					});
				});

				setTimeout(function() {
					$topAlerts.slideUp(500, function() {
						$(this).remove();
					});
				}, 4000);
			}
		});
		@stack('script')
		</script>
<!-- //here ends scrolling icon -->
</body>
</html>