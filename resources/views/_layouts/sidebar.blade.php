<div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">

    <!-- Sidebar mobile toggler -->
    <div class="sidebar-mobile-toggler text-center">
        <a href="#" class="sidebar-mobile-main-toggle">
            <i class="icon-arrow-left8"></i>
        </a>
        Navigation
        <a href="#" class="sidebar-mobile-expand">
            <i class="icon-screen-full"></i>
            <i class="icon-screen-normal"></i>
        </a>
    </div>
    <!-- /sidebar mobile toggler -->

    <!-- Sidebar content -->
    <div class="sidebar-content">

        <!-- User menu -->
        <div class="sidebar-user-material">
            <div class="sidebar-user-material-body">
                <div class="card-body text-center">
                    {{-- <a href="#">
                        <img src="{{ url('public/images/140201.jpg') }}" class="img-fluid rounded-circle shadow-1 mb-3" width="80" height="80" alt="">
                    </a> --}}
                    <h6 class="mb-0 text-white text-shadow-dark">{{ session('user.fullname') }}</h6>
                    <span class="font-size-sm text-white text-shadow-dark">{{ session('user.section') }}</span>
                </div>
            </div>
        </div>
        <!-- /user menu -->

        <!-- Main navigation -->
        <div class="card card-sidebar-mobile">
            <ul class="nav nav-sidebar" data-nav-type="accordion">

                <!-- Main -->
                <li class="nav-item-header">
                    <div class="text-uppercase font-size-xs line-height-xs">
                        Main
                    </div> 
                    <i class="icon-menu" title="Main"></i>
                </li>
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ setActive('dashboard') }} ">
                        <i class="icon-home4"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item nav-item-submenu {{ setOpen('wb.*') }}">
                    <a href="#" class="nav-link"><i class="icon-book3"></i> <span>Warranty Booklet</span></a>

                    <ul class="nav nav-group-sub" data-submenu-title="Warranty Booklet">
                        <li class="nav-item">
                            <a href="{{ route('wb.entry') }}" class="nav-link {{ setActive('wb.entry') }}">
                                <span>Batch Entry</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('wb.allocate') }}" class="nav-link {{ setActive('wb.allocate') }}">
                                <span>Allocate</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('wb.inventory') }}" class="nav-link {{ setActive('wb.inventory') }}">
                                <span>Inventory</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item nav-item-submenu {{ setOpen('invoice.*') }}">
                    <a href="#" class="nav-link"><i class="icon-file-text2
                        "></i> <span>Invoice</span></a>

                    <ul class="nav nav-group-sub" data-submenu-title="Warranty Booklet">
                        <li class="nav-item">
                            <a href="{{ route('invoice.print') }}" class="nav-link {{ setActive('invoice.print') }}">
                                <span>Print</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <!-- /main -->

            </ul>
        </div>
        <!-- /main navigation -->

    </div>
    <!-- /sidebar content -->
    
</div>
