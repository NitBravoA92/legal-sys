@extends('layouts.app')

@section('auth')

    @if(auth()->user()->role == "CUSTOMER")
        @include('layouts.navbars.auth.sidebar-clients')
        <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg">
            @include('layouts.navbars.auth.nav')
            <div class="container-fluid py-4">
                @yield('content')
            </div>
        </main>
    @else
        @include('layouts.navbars.auth.sidebar')
        <main class="main-content position-relative max-height-vh-100 h-100 mt-1 border-radius-lg">
            @include('layouts.navbars.auth.nav')
            <div class="container-fluid py-4">
                @yield('content')
            </div>
        </main>
        @if (auth()->user()->role == "SUPER ADMINISTRATOR")
            @include('components.fixed-plugin')
        @endif
    @endif
@endsection
