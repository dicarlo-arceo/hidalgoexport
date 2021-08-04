{{-- @extends('layouts.app') --}}
<link rel="stylesheet" href="{{ URL::asset('css/login.css') }}">
<link rel="stylesheet" href="{{ URL::asset('css/app.css') }}">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
<div class="wrapper">
    <!-- Sidebar  -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <p class="centrado">
                <a href="{{ route('home') }}">
                    <img src="{{ URL::asset('img/logo.png') }}" alt="logo" class="logo">
                </a>
            </p>
        </div>

        <ul class="navbar-nav left-side-nav" id="accordion">
            @foreach ($secciones as $seccion)

                    <li class="nav-item" data-toggle="tooltip" data-placement="right" title="{{$seccion->description}}">
                        <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" data-target="#multi_menu{{$seccion->id}}">
                        <i class="{{$seccion->icon}}" ></i>
                            <span class="nav-link-text">{{$seccion->section}}</span>
                        </a>
                        <ul class="sidenav-second-level collapse" id="multi_menu{{$seccion->id}}" data-parent="#accordion">
                        @if(count($subsections->where('padre_id',$seccion->id))>0)
                            @foreach ($subsections as $sub)
                                @if ($seccion->id == $sub->padre_id)
                                    <li class="nav-item" data-toggle="tooltip" data-placement="right">
                                        <a class="nav-link nav-link-collapse collapsed" data-toggle="collapse" data-target="#multi_menu_sub{{$sub->subsection}}">
                                            <span class="nav-link-text">{{$sub->subsection}}</span>
                                        </a>
                                        <ul class="sidenav-third-level collapse" id="multi_menu_sub{{$sub->subsection}}" data-parent="multi_menu_{{$seccion->section}}">
                                        @foreach ($user_permissions as $module)
                                        @if ($module->reference == $sub->sub_section_id )
                                            <li>

                                            @if($module->url!=null)
                                                <a class="permisions_id " href="{{ route($module->url) }}">{{$module->module}}</a>
                                            @else
                                                <a href="#">{{$module->module}}</a>
                                            @endif
                                            </li>
                                        @endif
                                        @endforeach

                                    </ul>
                                    </li>
                                @endif
                            @endforeach
                        @else
                            @foreach ($user_permissions as $module)
                                @if ($module->reference == $seccion->id )

                                    <li>

                                    @if($module->url!=null)
                                        <a class="permisions_id " href="{{ route($module->url) }}">{{$module->module}}</a>
                                    @else
                                        <a href="#">{{$module->module}}</a>
                                    @endif
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    </ul>
                </li>
            @endforeach
        </ul>

    </nav>
    {{-- header bar --}}
    <div id="content">
        <div id="app">
            <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
                <div class="container">
                    <a class="navbar-brand" href="{{ url('/') }}">
                    </a>
                    <button type="button" id="sidebarCollapse" class="btn btn-info">
                        <i class="fas fa-align-left"></i>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <!-- Left Side Of Navbar -->
                        {{-- <ul class="navbar-nav mr-auto">
                        </ul> --}}

                        <!-- Right Side Of Navbar -->
                        <ul class="navbar-nav ml-auto">
                            <!-- Authentication Links -->
                            @guest
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                                @if (Route::has('register'))
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                    </li>
                                @endif
                            @else
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        {{ Auth::user()->name }}
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>

                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </div>
                                </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
        <main class="py-4">
            @yield('content')
        </main>
    </div>

</div>
<!-- jQuery CDN - Slim version (=without AJAX) -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<!-- Popper.JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#sidebarCollapse').on('click', function () {
            $('#sidebar').toggleClass('active');
        });
    });
</script>
{{-- <script src="{{ asset('js/app.js') }}" defer></script> --}}
