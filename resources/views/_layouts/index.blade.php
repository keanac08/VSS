<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="base-url" content="{{ url('/') }}">

        <title>@yield('page_title', 'IPC')</title>
        
        <link href="{{ asset('public/css/plugins.bundle.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('public/css/app.css') }}" rel="stylesheet" type="text/css">
    </head>
    <body>
        
        @include('_layouts.navbar')
        
        <!-- Page content -->
        <div class="page-content" id="app">
        
            <!-- Main sidebar -->    
            @include('_layouts.sidebar')
            <!-- /main sidebar -->

            <!-- Main content -->
            <div class="content-wrapper">
                
                <!-- Header -->
                @include('_layouts.header')
                <!-- /header -->

                <!-- Content area -->
                @yield('content')
                <!-- /content area -->
                
                <!-- Footer -->
                @include('_layouts.footer')
                <!-- /footer -->
            
            </div>
            <!-- /main content -->
        
        </div>
        <!-- /page content -->
        
        <script src="{{ asset('public/js/app.js') }}"></script>
        <script src="{{ asset('public/js/plugins.bundle.js') }}"></script>
        <script>
            $.extend( true, $.fn.dataTable.defaults, {
                order: [],
                dom: '<"datatable-header"fl><"datatable-scroll"t><"datatable-footer"ip>',
                language: {
                    search: '<span>Filter:</span> _INPUT_',
                    searchPlaceholder: 'Type to filter...',
                    lengthMenu: '<span>Show:</span> _MENU_',
                    paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
                }
            });
        </script>
 
        @stack('scripts')
    
    </body>
</html>
