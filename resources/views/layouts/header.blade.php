
<div class="navbar-header logobackground">
    <a class="navbar-brand" href="<?php echo URL::to('/'); ?>">
        <b>
		    @if(file_exists(public_path('assets/images/'.$app_setting->app_logo)) && !empty($app_setting->app_logo))
                <img src="{{asset('assets/images/').'/'.$app_setting->app_logo}}" alt="homepage" class="dark-logo" width="100%" id="logo_web">
            @else
                <img src="{{ asset('/images/logo2.png') }}" alt="homepage" class="dark-logo" width="100%" id="logo_web">
            @endif
            
            @if(file_exists(public_path('assets/images/'.$app_setting->app_logo_small)) && !empty($app_setting->app_logo_small))
                <img src="{{asset('assets/images/').'/'.$app_setting->app_logo_small}}" alt="homepage" class="light-logo">
            @else
                <img src="{{ asset('images/logo-small.png') }}" alt="homepage" class="light-logo">
            @endif
        </b>
    </a>
</div>
<div class="navbar-collapse">
    
    <ul class="navbar-nav mr-auto mt-md-0">
        <li class="nav-item"> <a class="nav-link nav-toggler hidden-md-up text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="mdi mdi-menu"></i></a> </li>
        <li class="nav-item m-l-10"> <a class="nav-link sidebartoggler hidden-sm-down text-muted waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
        <li class="nav-item">
        	<span class="nav-link text-muted waves-effect waves-dark"><?php echo $app_setting->admin_title; ?></span>
        </li>
    </ul>
    <!-- <div  class="language-list icon d-flex align-items-center text-light ml-2" id="language_dropdown_box">
        <div class="language-select">
            <i class="fa fa-globe"></i>
        </div>
        <div class="language-options">
            <select class="form-control changeLang text-dark" name="language_dropdown" id="language_dropdown">
            
            </select>
        </div>
    </div> -->
    <ul class="navbar-nav my-lg-0">
      
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img src="{{ Auth::user()->photo_path ? asset('assets/images/users'.'/'.Auth::user()->photo_path) : asset('/images/user.png') }}" alt="user" class="profile-pic">
            </a>
            <div class="dropdown-menu dropdown-menu-right scale-up">
                <ul class="dropdown-user">
                    <li>
                        <div class="dw-user-box">
                            <div class="u-img">
                                <img src="{{ Auth::user()->photo_path ? asset('assets/images/users'.'/'.Auth::user()->photo_path) : asset('/images/user.png') }}" alt="Profile Picture" style="max-width: 45px;">
                           
                            </div>
                            <div class="u-text">
                                <h4>{{ Auth::user()->name }}</h4>
                                @if (Auth::check() && Auth::user()->hasRole('admin'))
                                    <p class="text-muted">Super Administrator</p>
                                @elseif(Auth::check() && Auth::user()->hasRole('user'))
                                    <p class="text-muted">Administrator</p>
                                @endif
                            </div>
                        </div>
                    </li>
                    <li role="separator" class="divider"></li>
                    <li><a href="{{ route('users.profile') }}"><i class="ti-user"></i>  {!! trans('lang.user_profile') !!}</a></li>
                    <li role="separator" class="divider"></li>
                    <li>
                    	<a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                             <i class="fa fa-power-off"></i> {{ __('Logout') }}
                         </a>
					</li>
                    
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    	@csrf
                    </form>
                </ul>
            </div>
        </li>
    </ul>
</div>
