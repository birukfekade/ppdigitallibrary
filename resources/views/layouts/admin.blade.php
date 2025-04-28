<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/bootstrap.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="/assets/vendors/iconly/bold.css">

    <link rel="stylesheet" href="/assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="/assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/app.css">
    <link rel="shortcut icon" href="/assets/images/favicon.svg" type="image/x-icon">
</head>

<body>
    <div id="app">
        <div id="sidebar" class="active">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header">
                    <div class="d-flex justify-content-between">
                        <div class="logo">
                            <a href="/"><img src="/assets/images/logo/pplogo.jpg" style="width: 100px !important; height: auto !important; text-align: center;" alt="Logo" srcset=""></a>
                        </div>
                        <div class="toggler">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>
                <div class="sidebar-menu">
                    <ul class="menu">
                        <li class="sidebar-title">Menu</li>

                        <li class="sidebar-item {{ request()->is('dashboard') ? 'active' : '' }}">
                            <a href="{{ route('dashboard') }}" class='sidebar-link'>
                                <i class="bi bi-grid-fill"></i>
                                <span>ዳሽቦርድ</span>
                            </a>
                        </li>
                        <li class="sidebar-item has-sub {{ request()->is('documents*') ? 'active' : '' }}">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-cloud-arrow-up-fill"></i>
                                <span>ሰነዶች</span>
                            </a>
                            <ul class="submenu">
                                @if(auth()->user()->isAdmin())
                                <li class="submenu-item {{ request()->is('documents') ? 'active' : '' }}">
                                    <a href="{{ route('documents.index') }}">ሁሉንም ለማየት</a>
                                </li>
                                <li class="submenu-item">
                                    <a href="{{ route('documents.upload') }}">አዲስ ለመጨመር</a>
                                </li>
                                @else
                                <li class="submenu-item {{ request()->is('mydocuments') ? 'active' : '' }}">
                                    <a href="{{ route('mydocuments') }}">የኔ ሰነዶች</a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @if(auth()->user()->isAdmin())
                        <li class="sidebar-item has-sub {{ request()->is('document-categories*') ? 'active' : '' }}">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-folder"></i>
                                <span>የሰነድ ምድቦች</span>
                            </a>
                            <ul class="submenu">
                                <li class="submenu-item {{ request()->is('document-categories') ? 'active' : '' }}">
                                    <a href="{{ route('document-categories.index') }}">ሁሉንም ለማየት</a>
                                </li>
                                <li class="submenu-item">
                                    <a href="{{ route('document-categories.create') }}">አዲስ ለመጨመር</a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        @if(auth()->user()->isAdmin())
                        <li class="sidebar-item has-sub {{ request()->is('access-levels*') ? 'active' : '' }}">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-key-fill"></i>
                                <span>የሰነድ እርከኖች</span>
                            </a>
                            <ul class="submenu">
                                <li class="submenu-item {{ request()->is('access-levels') ? 'active' : '' }}">
                                    <a href="{{ route('access-levels.index') }}">ሁሉንም ለማየት</a>
                                </li>
                                <li class="submenu-item">
                                    <a href="{{ route('access-levels.create') }}">አዲስ ለመጨመር</a>
                                </li>
                            </ul>
                        </li>
                        <li class="sidebar-item has-sub {{ request()->is('departments*') ? 'active' : '' }}">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-building"></i>
                                <span>ክፍሎች</span>
                            </a>
                            <ul class="submenu">
                                <li class="submenu-item {{ request()->is('departments') ? 'active' : '' }}">
                                    <a href="{{ route('departments.index') }}">ሁሉንም ለማየት</a>
                                </li>
                                <li class="submenu-item">
                                    <a href="{{ route('departments.create') }}">አዲስ ለመጨመር</a>
                                </li>
                            </ul>
                        </li>
                        <li class="sidebar-item has-sub {{ request()->is('cities*') ? 'active' : '' }}">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-map"></i>
                                <span>ከተሞች</span>
                            </a>
                            <ul class="submenu">
                                <li class="submenu-item {{ request()->is('cities') ? 'active' : '' }}">
                                    <a href="{{ route('cities.index') }}">ሁሉንም ለማየት</a>
                                </li>
                                <li class="submenu-item">
                                    <a href="{{ route('cities.create') }}">አዲስ ለመጨመር</a>
                                </li>
                            </ul>
                        </li>
                        <li class="sidebar-item has-sub {{ request()->is('sub-cities*') ? 'active' : '' }}">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-map"></i>
                                <span>ክፍለ ከተሞች</span>
                            </a>
                            <ul class="submenu">
                                <li class="submenu-item {{ request()->is('sub-cities') ? 'active' : '' }}">
                                    <a href="{{ route('sub-cities.index') }}">ሁሉንም ለማየት</a>
                                </li>
                                <li class="submenu-item">
                                    <a href="{{ route('sub-cities.create') }}">አዲስ ለመጨመር</a>
                                </li>
                            </ul>
                        </li>
                        <li class="sidebar-item has-sub {{ request()->is('users*') ? 'active' : '' }}">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-people-fill"></i>
                                <span>ተጠቃሚዎች</span>
                            </a>
                            <ul class="submenu">
                                <li class="submenu-item {{ request()->is('users') ? 'active' : '' }}">
                                    <a href="{{ route('users.index') }}">ሁሉንም ለማየት</a>
                                </li>
                                <li class="submenu-item">
                                    <a href="{{ route('users.create') }}">አዲስ ለመጨመር</a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        <li class="sidebar-item has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-person-fill"></i>
                                <span>መለያ</span>
                            </a>
                            <ul class="submenu">
                                <li class="submenu-item {{ request()->is('profile') ? 'active' : '' }}">
                                    <a href="{{ route('profile.edit') }}">ግለ ገፅ</a>
                                </li>
                                <li class="submenu-item">
                                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">ዘግቶ ለመውጣት</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>


                    </ul>
                </div>
                <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
            </div>
        </div>
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            @yield('content')

            <footer>
                <div class="footer clearfix mb-0 text-muted">
                    <div class="float-start">
                        <p>{{ date ('Y') }} &copy; B & H</p>
                    </div>
                    <div class="float-end">
                        <p>በ B & H Partnership ለምቶ የተሰጠ ሲስተም<span class="text-danger"><i class="bi bi-heart"></i></span> <a
                                href="https://bandhtechsolutions.com/">B & H</a></p>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="/assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="/assets/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <script src="/assets/vendors/apexcharts/apexcharts.js"></script>
    <script src="/assets/js/pages/dashboard.js"></script>

    <script src="/assets/js/main.js"></script>
</body>

</html>