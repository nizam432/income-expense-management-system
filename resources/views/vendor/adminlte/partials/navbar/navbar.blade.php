<nav class="main-header navbar navbar-expand navbar-white navbar-light">

    {{-- LEFT navbar --}}
    <ul class="navbar-nav">
        {{-- Sidebar toggle --}}
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
                <span class="sr-only">Toggle navigation</span>
            </a>
        </li>
    </ul>

    {{-- RIGHT navbar --}}
    <ul class="navbar-nav ml-auto">

        {{-- Search --}}
        <li class="nav-item">
            <a class="nav-link" data-widget="navbar-search" href="#" role="button">
                <i class="fas fa-search"></i>
            </a>

            <div class="navbar-search-block">
                <form class="form-inline" action="#" method="get">
                    @csrf
                    <div class="input-group">
                        <input class="form-control form-control-navbar" type="search" name="adminlteSearch" placeholder="Search" aria-label="Search">
                        <div class="input-group-append">
                            <button class="btn btn-navbar" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                            <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </li>

        {{-- Fullscreen --}}
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
        
        <li> 
 
            <script>
                /* function changeLanguage() {
                    var locale = document.getElementById('language').value;
                    window.location.href = '/locale/' + locale;
                } */
            </script>
        </li>
        {{-- LANGUAGE DROPDOWN --}}
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fas fa-globe"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
			   <a href="{{ route('change.language','en') }}" class="dropdown-item">{{ __('messages.english') }}</a>
				<a href="{{ route('change.language','bn') }}" class="dropdown-item">{{ __('messages.bangla') }}</a>
            </div>
        </li>
        
        {{-- USER PROFILE DROPDOWN --}}
        <li class="nav-item dropdown user-menu">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                <img src="{{ Auth::user()->profile_photo_url ?? asset('default-avatar.png') }}"
                     class="user-image img-circle elevation-2" alt="{{ Auth::user()->name }}">
                <span class="d-none d-md-inline">
                    {{ Auth::user()->name ?? 'User' }}
                </span>
            </a>

            <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                {{-- User header --}}
                <li class="user-header bg-primary">
                    <img src="{{ Auth::user()->profile_photo_url ?? asset('default-avatar.png') }}"
                         class="img-circle elevation-2" alt="{{ Auth::user()->name }}">
                    <p>
                        {{ Auth::user()->name ?? 'User' }}
                        <small>{{ Auth::user()->email ?? '' }}</small>
                    </p>
                </li>

                {{-- User footer --}} {{--route('profile.show')--}}
                <li class="user-footer">
                    <a class="btn btn-default btn-flat float-left" href="">
                        Profile
                    </a>
                    <a class="btn btn-default btn-flat float-right" href="#"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa fa-fw fa-power-off text-red"></i> Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            </ul>
        </li>




    </ul>

</nav>
