<!DOCTYPE html>
<html lang="en" data-bs-theme="light" data-menu-color="brand" data-topbar-color="light">

<head>
    <meta charset="utf-8" />
    <title>@yield('title', 'Vstep Website')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description" />
    <meta content="DangMinhDat" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- <meta http-equiv="refresh" content="2"> --}}
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('students\assets\images\logo-white.png') }}">

    <link href="{{ asset('admin/assets/libs/morris.js/morris.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin/assets/libs/sweetalert2/sweetalert2.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App css -->
    <link href="{{ asset('admin/assets/css/test.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/css/style.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/css/style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">
    <script src="{{ asset('admin/assets/js/config.js') }}"></script>
    
    <link href="{{ asset('admin/assets/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('admin/assets/libs/datatables.net-responsive-bs5/css/responsive.bootstrap5.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin/assets/libs/datatables.net-buttons-bs5/css/buttons.bootstrap5.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin/assets/libs/datatables.net-select-bs5/css/select.bootstrap5.min.css') }}"
        rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin/assets/libs/dropzone/min/dropzone.min.css" rel="stylesheet') }}" type="text/css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>
<!-- Begin page -->
<div class="layout-wrapper">
    <!-- ========== Left Sidebar ========== -->
    <div class="main-menu">
        <!-- Brand Logo -->
        <div class="logo-box">
            <!-- Brand Logo Light -->
            <a href="index.html" class="logo-light">
                <img src="{{ asset('students\assets\images\big-logo.png') }}" alt="logo" class="logo-lg"
                    height="70">
                <img src="{{ asset('students\assets\images\logo-white.png') }}" alt="small logo" class="logo-sm"
                    height="70">
            </a>

            {{-- <!-- Brand Logo Dark -->
            <a href="index.html" class="logo-dark">
                <img src="{{ asset('admin/assets/images/logo-dark.png') }}" alt="dark logo" class="logo-lg"
                    height="28">
                <img src="{{ asset('admin/assets/images/logo-sm.png') }}" alt="small logo" class="logo-sm"
                    height="28">
            </a> --}}
        </div>

        <!--- Menu -->
        <div data-simplebar>
            <ul class="app-menu">

                <li class="menu-title">Menu</li>

                <li class="menu-item">
                    <a href="index.html" class="menu-link waves-effect waves-light">
                        <span class="menu-icon"><i class="bx bx-home-smile"></i></span>
                        <span class="menu-text"> Dashboards </span>
                        <span class="badge bg-primary rounded ms-auto">01</span>
                    </a>
                </li>

                <li class="menu-title">Custom</li>

                <li class="menu-item">
                    <a href="apps-calendar.html" class="menu-link waves-effect waves-light">
                        <span class="menu-icon"><i class="bx bx-calendar"></i></span>
                        <span class="menu-text"> Calendar </span>
                    </a>
                </li>

                <li class="menu-item">
                    <a href="#menuExpages" data-bs-toggle="collapse" class="menu-link waves-effect waves-light">
                        <span class="menu-icon"><i class="bx bx-file"></i></span>
                        <span class="menu-text"> Extra Pages </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="menuExpages">
                        <ul class="sub-menu">
                            <li class="menu-item">
                                <a href="pages-starter.html" class="menu-link">
                                    <span class="menu-text">Starter</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="pages-invoice.html" class="menu-link">
                                    <span class="menu-text">Invoice</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="pages-login.html" class="menu-link">
                                    <span class="menu-text">Log In</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="pages-register.html" class="menu-link">
                                    <span class="menu-text">Register</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="pages-recoverpw.html" class="menu-link">
                                    <span class="menu-text">Recover Password</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="pages-lock-screen.html" class="menu-link">
                                    <span class="menu-text">Lock Screen</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="pages-404.html" class="menu-link">
                                    <span class="menu-text">Error 404</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="pages-500.html" class="menu-link">
                                    <span class="menu-text">Error 500</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="menu-title">Components</li>

                <li class="menu-item">
                    <a href="#menuComponentsui" data-bs-toggle="collapse" class="menu-link waves-effect waves-light">
                        <span class="menu-icon"><i class="bx bx-cookie"></i></span>
                        <span class="menu-text"> UI Elements </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="menuComponentsui">
                        <ul class="sub-menu">
                            <li class="menu-item">
                                <a href="ui-alerts.html" class="menu-link">
                                    <span class="menu-text">Alerts</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="ui-buttons.html" class="menu-link">
                                    <span class="menu-text">Buttons</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="ui-cards.html" class="menu-link">
                                    <span class="menu-text">Cards</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="ui-carousel.html" class="menu-link">
                                    <span class="menu-text">Carousel</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="ui-dropdowns.html" class="menu-link">
                                    <span class="menu-text">Dropdowns</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="ui-video.html" class="menu-link">
                                    <span class="menu-text">Embed Video</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="ui-general.html" class="menu-link">
                                    <span class="menu-text">General UI</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="ui-grid.html" class="menu-link">
                                    <span class="menu-text">Grid</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="ui-images.html" class="menu-link">
                                    <span class="menu-text">Images</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="ui-list-group.html" class="menu-link">
                                    <span class="menu-text">List Group</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="ui-modals.html" class="menu-link">
                                    <span class="menu-text">Modals</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="ui-offcanvas.html" class="menu-link">
                                    <span class="menu-text">Offcanvas</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="ui-placeholders.html" class="menu-link">
                                    <span class="menu-text">Placeholders</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="ui-progress.html" class="menu-link">
                                    <span class="menu-text">Progress</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="ui-spinners.html" class="menu-link">
                                    <span class="menu-text">Spinners</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="ui-tabs-accordions.html" class="menu-link">
                                    <span class="menu-text">Tabs & Accordions</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="ui-tooltips-popovers.html" class="menu-link">
                                    <span class="menu-text">Tooltips & Popovers</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="ui-typography.html" class="menu-link">
                                    <span class="menu-text">Typography</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="menu-item">
                    <a href="#menuForms" data-bs-toggle="collapse" class="menu-link waves-effect waves-light">
                        <span class="menu-icon"><i class="bx bxs-eraser"></i></span>
                        <span class="menu-text"> Forms </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="menuForms">
                        <ul class="sub-menu">
                            <li class="menu-item">
                                <a href="{{ route('createInstructor.create') }}" class="menu-link">
                                    <span class="menu-text">Manage Lecturers</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="{{ route('createStudent.create') }}" class="menu-link">
                                    <span class="menu-text">Manage Students</span>
                                </a>
                            </li>
                            {{-- <li class="menu-item">
                                <a href="{{ route('test.create') }}" class="menu-link">
                                    <span class="menu-text">Manage Test</span>
                                </a>
                            </li> --}}
                            <li class="menu-item">
                                <a href="{{ route('create.skill.part') }}" class="menu-link">
                                    <span class="menu-text">Manage Skill Part</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="menu-item">
                    <a href="#menuTables" data-bs-toggle="collapse" class="menu-link waves-effect waves-light">
                        <span class="menu-icon"><i class="bx bx-table"></i></span>
                        <span class="menu-text"> Tables </span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="menuTables">
                        <ul class="sub-menu">
                            <li class="menu-item">
                                <a href="{{ route('tableLecturer.index') }}" class="menu-link">
                                    <span class="menu-text">Lecturer List</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="{{ route('tableStudent.index') }}" class="menu-link">
                                    <span class="menu-text">Student List</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="{{ route('tableTest.index') }}" class="menu-link">
                                    <span class="menu-text">Test List</span>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="{{ route('questionBank.index') }}" class="menu-link">
                                    <span class="menu-text">Question Bank</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>



    <!-- ============================================================== -->
    <!-- Start Page Content here -->
    <!-- ============================================================== -->

    <div class="page-content">

        <!-- ========== Topbar Start ========== -->
        <div class="navbar-custom">
            <div class="topbar">
                <div class="topbar-menu d-flex align-items-center gap-lg-2 gap-1">

                    <!-- Brand Logo -->
                    <div class="logo-box">
                        <!-- Brand Logo Light -->
                        <a href="index.html" class="logo-light">
                            <img src="{{ asset('students\assets\images\big-logo.png') }}" alt="logo"
                                class="logo-lg" height="70">
                            <img src="{{ asset('students\assets\images\logo-white.png') }}" alt="small logo"
                                class="logo-sm" height="70">
                        </a>
                    </div>

                    <!-- Sidebar Menu Toggle Button -->
                    <button class="button-toggle-menu">
                        <i class="mdi mdi-menu"></i>
                    </button>
                </div>

                <ul class="topbar-menu d-flex align-items-center gap-4">

                    <li class="d-none d-md-inline-block">
                        <a class="nav-link" href="" data-bs-toggle="fullscreen">
                            <i class="mdi mdi-fullscreen font-size-24"></i>
                        </a>
                    </li>

                    <li class="nav-link" id="theme-mode">
                        <i class="bx bx-moon font-size-24"></i>
                    </li>

                    <li class="dropdown">
                        <a class="nav-link dropdown-toggle nav-user me-0 waves-effect waves-light"
                            data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false"
                            aria-expanded="false">
                            {{-- <img src="{{ asset('admin/assets/images/users/avatar-4.jpg') }}" alt="user-image"
                                class="rounded-circle"> --}}
                            <span class="ms-1 d-none d-md-inline-block">
                                {{ session('user_name') }} <i class="mdi mdi-chevron-down"></i>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end profile-dropdown ">
                            <!-- item-->
                            <div class="dropdown-header noti-title">
                                <h6 class="text-overflow m-0">Welcome !</h6>
                            </div>

                            <div class="dropdown-divider"></div>

                            <!-- item-->
                            <a href="javascript:void(0);" class="dropdown-item notify-item"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fe-log-out"></i>
                                <span>Logout</span>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <!-- ========== Topbar End ========== -->
        <div class="px-3">
            <!-- Start Content-->
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
        <!-- Footer Start -->
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-6">
                        <div>
                            <script>
                                document.write(new Date().getFullYear())
                            </script> © Copyright by Greenwich Vietnam Cantho Campus
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-none d-md-flex gap-4 align-item-center justify-content-md-end">
                            <p class="mb-0">Develop by <a href="#" target="_blank">Dang Minh Dat</a> </p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>

    <!-- App js -->
    <script src="{{ asset('admin/assets/js/vendor.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/app.js') }}"></script>

    <!-- Knob charts js -->
    <script src="{{ asset('admin/assets/libs/jquery-knob/jquery.knob.min.js') }}"></script>

    <!-- Sparkline Js-->
    <script src="{{ asset('admin/assets/libs/jquery-sparkline/jquery.sparkline.min.js') }}"></script>

    <script src="{{ asset('admin/assets/libs/morris.js/morris.min.js') }}"></script>

    <script src="{{ asset('admin/assets/libs/raphael/raphael.min.js') }}"></script>

    <!-- Dashboard init-->
    <script src="{{ asset('admin/assets/js/pages/dashboard.js') }}"></script>

    <!-- third party js -->
    <script src="{{ asset('admin/assets/libs/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/assets/libs/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('admin/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('admin/assets/libs/datatables.net-responsive-bs5/js/responsive.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('admin/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('admin/assets/libs/datatables.net-buttons-bs5/js/buttons.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('admin/assets/libs/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('admin/assets/libs/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('admin/assets/libs/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('admin/assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js') }}"></script>
    <script src="{{ asset('admin/assets/libs/datatables.net-select/js/dataTables.select.min.js') }}"></script>
    <script src="{{ asset('admin/assets/libs/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('admin/assets/libs/pdfmake/build/vfs_fonts.js') }}"></script>
    <!-- third party js ends -->
    <!-- Sweet Alerts js -->
    <script src="{{ asset('admin/assets/libs/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('admin/assets/libs/dropzone/min/dropzone.min.js') }}"></script>
    <!-- Demo js-->
    <script src="{{ asset('admin/assets/js/pages/form-fileuploads.js') }}"></script>
    <!-- Sweet alert Demo js-->
    {{-- <script src="{{ asset('admin/assets/js/pages/sweet-alerts.js') }}"></script> --}}
    <!-- Datatables js -->
    <script src="{{ asset('admin/assets/js/pages/datatables.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if there is a success message in the session
            @if (session('success'))
                Swal.fire({
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    showClass: {
                        popup: `
                    animate__animated
                    animate__fadeInUp
                    animate__faster
                    `
                    },
                    hideClass: {
                        popup: `
                    animate__animated
                    animate__fadeOutDown
                    animate__faster
                    `
                    }
                });
            @endif
        });
    </script>
</div>
