<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{route('dashboard')}}" class="brand-link">
        <img src="{{asset('images/logo.png')}}" alt="Logo" class="brand-image img-size-50 img-circle">
        <span class="brand-text font-weight-light">Chronos</span>
    </a>
    <div class="sidebar">
        @if(Auth::user())
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{Auth::user()->profile_photo}}" class="img-circle elevation-2" alt="User-Image">
            </div>
            <div class="info">
                <a href="{{route('user.profile')}}" class="d-block">{{Auth::user()->username}}</a>
            </div>
        </div>
        @endif
        <div class="form-inline mt-4">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-sidebar">
                    <i class="fas fa-search fa-fw"></i>
                </button>
            </div>
            </div>
        </div>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
                <a href="{{route('dashboard')}}" class="nav-link">
                    <i class="fa fa-home"></i>
                    <p>Home</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('user.calendars')}}" class="nav-link">
                    <i class="far fa-calendar-alt"></i>
                    <p>My Calendars</p>
                </a>
            </li>
            </ul>
        </nav>
    </div>
</aside>