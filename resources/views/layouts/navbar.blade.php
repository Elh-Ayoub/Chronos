<div class="preloader flex-column justify-content-center align-items-center">
    <img class="animation__shake" src="{{asset('images/logo_transparent.png')}}" alt="AdminLTELogo" height="250" width="250">
</div>
<nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
    <div class="container">
        <a href="{{route('dashboard')}}" class="navbar-brand">
        <img src="{{ asset('images/logo_transparent.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{env('APP_NAME')}}</span>
        </a>
        <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse order-3" id="navbarCollapse">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item">
            <a href="{{route('dashboard')}}" class="nav-link">Home</a>
            </li>
            <li class="nav-item">
            <a href="#" class="nav-link">Contact</a>
            </li>
            <li class="nav-item dropdown">
                <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle">Dropdown</a>
                <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                    <li><a href="#" class="dropdown-item">Some action </a></li>
                    <li><a href="#" class="dropdown-item">Some other action</a></li>
                </ul>
            </li>
        </ul>
        <form class="form-inline ml-0 ml-md-3">
            <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-navbar" type="submit">
                <i class="fas fa-search"></i>
                </button>
            </div>
            </div>
        </form>
        </div>
        <!-- Right navbar links -->
        <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto align-items-center">
            @if(Auth::user())
            <li class="nav-item user-panel d-flex">
                <div class="mb-1">
                    <a href="{{route('user.profile')}}" class="nav-link">
                        <div class="image pr-2">
                            <img src="{{Auth::user()->profile_photo}}" class="img-fluid img-circle" alt="User-Image" style="border: 1px solid grey;">
                        </div>{{Auth::user()->username}}
                    </a>
                </div>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{route('auth.logout')}}">Log out</a>
            </li>
            @endif
            <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>
        </ul>
    </div>
</nav>