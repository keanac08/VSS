<!-- Main navbar -->
<div class="navbar navbar-expand-md navbar-light navbar-static">

    <!-- Header with logos -->
    <div class="navbar-header navbar-dark d-none d-md-flex align-items-md-center">
        <div class="navbar-brand navbar-brand-md">
            <a href="{{ route('dashboard') }}" class="d-inline-block">
                <img src="{{ url('public/images/logo_white.png') }}" alt="">
            </a>
        </div>
        
        <div class="navbar-brand navbar-brand-xs">
            <a href="../full/index.html" class="d-inline-block">

            </a>
        </div>
    </div>
    <!-- /header with logos -->


    <!-- Mobile controls -->
    <div class="d-flex flex-1 d-md-none">
        <div class="navbar-brand mr-auto">
            <a href="../full/index.html" class="d-inline-block">
                <!-- <img src="../../../../global_assets/images/logo_dark.png" alt=""> -->
            </a>
        </div>  

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
            <i class="icon-tree5"></i>
        </button>

        <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
            <i class="icon-paragraph-justify3"></i>
        </button>
    </div>
    <!-- /mobile controls -->


    <!-- Navbar content -->
    <div class="collapse navbar-collapse" id="navbar-mobile">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
                    <i class="icon-paragraph-justify3"></i>
                </a>
            </li>
        </ul>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown dropdown-user">
                <a href="#" class="navbar-nav-link d-flex align-items-center dropdown-toggle" data-toggle="dropdown">
                    {{-- <img src="{{ url('public/images/140201.jpg') }}" class="rounded-circle mr-2" height="34" alt=""> --}}
                    <span>{{ session('user.first_name') }}</span>
                </a>

                <div class="dropdown-menu dropdown-menu-right">
                    {{-- <a href="#" class="dropdown-item"><i class="icon-user-plus"></i> My profile</a> --}}
                    <a href="{{ route('logout') }}" class="dropdown-item"><i class="icon-switch2"></i> Logout</a>
                </div>
            </li>
        </ul>
    </div>
    <!-- /navbar content -->

</div>
<!-- /main navbar -->