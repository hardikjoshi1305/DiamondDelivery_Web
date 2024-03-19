<!Doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin|Diamond Management</title>
    <meta name="description" content="Sufee Admin - HTML5 Admin Template">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="apple-touch-icon" href="apple-icon.png">
    <link rel="shortcut icon" href="favicon.ico">

    <link rel="stylesheet" href="{{ asset('public/Backend/vendors/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/Backend/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/Backend/vendors/themify-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('public/Backend/vendors/flag-icon-css/css/flag-icon.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/Backend/vendors/selectFX/css/cs-skin-elastic.css') }}">
    <link rel="stylesheet" href="{{ asset('public/Backend/vendors/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/Backend/vendors/assets/css/style.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ asset('public/Backend/vendors/assets/latest/css/toastr.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('public/Backend/vendors/datatables.net-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('public/Backend/vendors/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css') }}">
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800' rel='stylesheet' type='text/css'>
    <!-- Include SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.min.css">

</head>

<body>

    <aside id="left-panel" class="left-panel">
        <nav class="navbar navbar-expand-sm navbar-default">
            <!-- Logo light -->
            <div class="navbar-header">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-menu"
                    aria-controls="main-menu" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-bars"></i>
                </button>

                <a class="navbar-brand border-0" href="{{ route('admin.home') }}"><img
                        src="{{ asset('public/Backend/images/Diamond.png') }}" alt="Logo"></a>
                <a class="navbar-brand hidden" href="{{ route('admin.home') }}"><img
                        src="{{ asset('public/Backend/images/Diamond.png') }}" alt="Logo"></a>
            </div>
            {{-- !-- Logo Dark --> --}}

            <div id="main-menu" class="main-menu collapse navbar-collapse">
                <ul class="nav navbar-nav">
                    <li class="active">
                        <a href="{{ route('admin.home') }}"> <i class="menu-icon fa fa-dashboard"></i>Dashboard </a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('agent') }}" aria-haspopup="true" aria-expanded="false"> <i
                                class="menu-icon fa fa-home"></i>Add Agent</a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('user') }}" aria-haspopup="true" aria-expanded="false">  <i
                            class="menu-icon fa fa-male"></i> User</a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('party') }}" aria-haspopup="true" aria-expanded="false"> <i
                                class="menu-icon fa fa-plus-square"></i>Add Client</a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('shipment') }}" aria-haspopup="true" aria-expanded="false"> <i
                                class="menu-icon fa fa fa-briefcase"></i>Shipment Mode</a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('notification') }}" aria-haspopup="true" aria-expanded="false"> <i
                                class="menu-icon fa fas fa-comment"></i>Notification</a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('excel') }}" aria-haspopup="true" aria-expanded="false"> <i
                                class="menu-icon fa fa-print"></i>Upload Excel</a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('diamond.list') }}" aria-haspopup="true" aria-expanded="false"> <i
                                class="menu-icon fa fa-database"></i>Diamond List</a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('transfer.menu') }}" aria-haspopup="true" aria-expanded="false"> <i
                                class="menu-icon fa fa-rupee"></i>Transfer Money</a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('collection.order') }}" aria-haspopup="true" aria-expanded="false"> <i
                                class="menu-icon fa fa-eye"></i>Collection Order</a>
                    </li>
                    <h3 class="menu-title">Reports</h3>

                    <li class="side-nav-item">
                        <a href="{{ route('report') }}" aria-haspopup="true" aria-expanded="false"> <i
                                class="menu-icon fa fa-bookmark-o"></i>Diamond Wise Report</a>
                    </li>

                    <li class="side-nav-item">
                        <a href="{{ route('pending.delivery') }}" aria-haspopup="true" aria-expanded="false"> <i
                                class="menu-icon fa fa-bookmark-o"></i>Pending Delivery Report</a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('stock.reject') }}" aria-haspopup="true" aria-expanded="false"> <i
                                class="menu-icon fa fa-bookmark-o"></i>Stock Rejected Report</a>
                    </li>
                    <li class="side-nav-item">
                        <a href="{{ route('ledger') }}" aria-haspopup="true" aria-expanded="false"> <i
                                class="menu-icon fa fa-bookmark-o"></i>Ledger</a>
                    </li>

                    <li class="side-nav-item" style="display: none;">
                        <a href="{{ route('agent.report') }}" aria-haspopup="true" aria-expanded="false"> <i
                                class="menu-icon fa fa-bookmark-o"></i>Agent Wise Report</a>
                    </li>
                    <li class="side-nav-item" style="display: none;">
                        <a href="{{ route('sold.report') }}" aria-haspopup="true" aria-expanded="false"> <i
                                class="menu-icon fa fa-bookmark-o"></i>SoldBY Wise Report</a>
                    </li>

                    {{--  <li class="menu-item-has-children dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="menu-icon fa fa-table"></i>Reports</a>
                        <ul class="sub-menu children dropdown-menu">
                            <li><i class="fa fa-bookmark-o"></i><a href="{{ route('report') }}">Basic Table</a></li>
                            <li><i class="fa fa-bookmark-o"></i><a href="{{ route('agent.report') }}">Data Table</a></li>
                        </ul>
                    </li>  --}}
                </ul>
            </div><!-- /.navbar-collapse -->
        </nav>
    </aside>



    <div id="right-panel" class="right-panel">
        <!-- Header-->
        <header id="header" class="header">

            <div class="header-menu">

                <div class="col-sm-7">
                    <a id="menuToggle" class="menutoggle pull-left"><i class="fa fa fa-tasks"></i></a>
                    <div class="header-left">
                    </div>
                </div>
                <?php
                    $accept = DB::table('tbl_order_accept')
                            ->where('read_at', 0)
                            ->count();
                ?>
                <div class="col-sm-5">
                    <div class="user-area dropdown float-right">
                        <a href="{{ route('notification') }}" title="Message Box">
                            <i class="fa fa-bell" style="font-size:22px;margin-top:12px;"></i>

                            {{--  <i class="menu-icon fa fas fa-comment"style="font-size:27px;padding:2px; margin-top:8px;"></i>  --}}
                            @if ($accept > 0)
                                <span class="badge badge-danger" style="position: relative;
                                top: -8px;
                                /* padding: 4px; */
                                border-radius: 19px;
                                margin-left: -16px;">{{ $accept }}</span>
                            @endif
                        </a>

                        {{--  <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <span class="account-user-avatar">
                                <img class="user-avatar rounded-circle"
                                    src="{{ asset('public/Backend/images/avatar/2.jpg') }}" alt="User Avatar"
                                    class="rounded-circle">
                            </span>
                        </a>  --}}
                        <div class="user-menu dropdown-menu">
                            <a class="nav-link" href=""><i class="fa fa-power-off"></i> Logout</a>
                        </div>
                    </div>
                </div>

            </div>

        </header><!-- /header -->

        @yield('content')
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6 mb-5">©
                        <script>
                            document.write(new Date().getFullYear())
                        </script> POWERED BY WEBROID SOLUTIONS
                    </div>
                    <div class="col-md-6">
                        <div class="text-md-end footer-links d-none d-md-block">
                        </div>
                    </div>
                </div>
            </div>
        </footer>
    </div>



    <script src="{{ asset('public/Backend/vendors/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('public/Backend/vendors/popper.js/dist/umd/popper.min.js') }}"></script>
    <script src="{{ asset('public/Backend/vendors/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('public/Backend/vendors/assets/js/main.js') }}"></script>


    <script src="{{ asset('public/Backend/vendors/jqvmap/dist/jquery.vmap.min.js') }}"></script>
    <script src="{{ asset('public/Backend/vendors/jqvmap/examples/js/jquery.vmap.sampledata.js') }}"></script>
    <script src="{{ asset('public/Backend/vendors/jqvmap/dist/maps/jquery.vmap.world.js') }}"></script>

    <script src="{{ asset('public/Backend/vendors/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('public/Backend/vendors/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('public/Backend/vendors/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('public/Backend/vendors/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>

    <script src="{{ asset('public/Backend/vendors/jszip/dist/jszip.min.js') }}"></script>
    <script src="{{ asset('public/Backend/vendors/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('public/Backend/vendors/pdfmake/build/vfs_fonts.js') }}"></script>

    <script src="{{ asset('public/Backend/vendors/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('public/Backend/vendors/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('public/Backend/vendors/datatables.net-buttons/js/buttons.colVis.min.js') }}"></script>
    <script src="{{ asset('public/Backend/vendors/assets/js/init-scripts/data-table/datatables-init.js') }}"></script>

    <script src="https://code.jquery.com/jquery-3.7.0.js" integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM="
        crossorigin="anonymous"></script>
    <script src="{{ asset('public/Backend/vendors/assets/latest/js/toastr.min.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.19/dist/sweetalert2.all.min.js"></script>

    @yield('script')

</body>

</html>
