<!-- Navbar -->
<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl position-sticky bg-white-fx blur shadow-blur mt-4 left-auto top-1 z-index-sticky" id="navbarBlur" navbar-scroll="true" data-scroll="true">
    <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="text-sm"><a class="opacity-5 text-dark" href="javascript:;">{{ __('content.area')}}:</a></li>
                <li class="breadcrumb-item text-sm text-dark active text-capitalize px-2" aria-current="page"><span class="font-weight-bolder mb-0 text-capitalize"> @if(explode('/', Request::path())[0] == 'management-area'){{ __('content.management') }}@else {{ __('content.users.client') }}@endif</span></li>
            </ol>
            <h6 class="font-weight-bolder mb-0 text-capitalize">{{ $current_page }}</h6>

        </nav>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4 d-flex justify-content-end" id="navbar">

            <ul class="navbar-nav  justify-content-end">

            <!-- notification icon -->
            <li class="nav-item dropdown pe-2 me-3 d-flex align-items-center" id="user-notifications-navlist">
                <a href="javascript:;" class="nav-link text-body p-0 position-relative" id="dropdownMenuButton_notif" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bell cursor-pointer"></i> @if (session()->get('notifications_unred') > 0) <span id="notif_counter" class="position-absolute top-5 start-70 translate-middle badge rounded-pill bg-gradient-danger" style="padding: 0.30em 0.4em; font-size: 10px;">{{ session()->get('notifications_unred') }}</span> @endif
                </a>
                            <!-- list of notifications -->
                            <ul class="dropdown-menu  dropdown-menu-end  px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton_notif">
                                @if (session()->get('notifications')->count() == 0)
                                    <li style="width: 100%;">
                                        <p class="text-secondary" style="text-align: center; font-size: 11px;">{{ __('content.messages.no_notifications') }}</p>
                                    </li>
                                @else
                                @foreach (session()->get('notifications') as $notification)
                                    {!!html_entity_decode($notification->data_long)!!}
                                @endforeach

                                <li class="mt-3">
                                    <p class="text-secondary text-center"><a @if (explode('/', Request::path())[0] == 'client-area' )
                                        href="{{ route('notifications.client') }}"
                                    @else
                                        href="{{ route('notifications.management') }}"
                                    @endif>{{ __('content.view_all') }}</a></p>
                                </li>
                                @endif
                            </ul>

                        </li>
                        <!-- end of notifications -->


            <!-- language dropdown -->
            <li class="nav-item dropdown pe-2 d-flex align-items-center language-dropdown">
                <a href="javascript:;" class="nav-link text-body p-0 font-weight-bolder text-upp" id="dropdownMenuButton_lang" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ env('APP_URL') }}/assets/img/icons/flags/{{ __( session()->get('language') ) }}.png" class="h-100 mb-1" alt="" title="{{ __('content.language') }}">
                    {{ __( session()->get('language') ) }}
                </a>
                            <!-- list of notifications -->
                            <ul class="dropdown-menu dropdown-menu-end  px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton_lang">

                                <li class="mb-1">
                                    <a class="dropdown-item border-radius-md {{ ( session()->get('language') == 'en' ? 'active' : '' )}}" href="{{ route('language', 'en') }}">
                                        <div class="d-flex py-1">
                                            <div class="my-auto">
                                                <img src="{{ env('APP_URL') }}/assets/img/icons/flags/en-24px.png" class="me-3 "> <!-- for avatars: class="avatar avatar-sm" -->
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="text-sm font-weight-normal">
                                                    <span class="font-weight-bold">{{ __('content.lang.english') }}</span>
                                                </h6>
                                            </div>
                                        </div>
                                    </a>
                                </li>

                                <li class="mb-1">
                                    <a class="dropdown-item border-radius-md {{ (session()->get('language') == 'es' ? 'active' : '' )}}" href="{{ route('language', 'es') }}">
                                        <div class="d-flex py-1">
                                            <div class="my-auto">
                                                <img src="{{ env('APP_URL') }}/assets/img/icons/flags/es-24px.png" class="me-3 "> <!-- for avatars: class="avatar avatar-sm" -->
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="text-sm font-weight-normal">
                                                    <span class="font-weight-bold">{{ __('content.lang.spanish') }}</span>
                                                </h6>
                                            </div>
                                        </div>
                                    </a>
                                </li>

                            </ul>

                        </li>
            <!-- end language dropdown -->

            <!-- user dropdown -->
            <li class="nav-item dropdown pe-2 d-flex align-items-center language-dropdown">
                <a href="javascript:;" class="nav-link text-body p-0 font-weight-bolder" id="dropdownMenuButton_user" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="@if(auth()->user()->photo == ''){{ env('APP_URL') }}/assets/img/user_avatar/default-photo.png @else{{env('APP_URL')}}{{Storage::url(auth()->user()->photo)}}@endif" class="avatar avatar-sm-user-navbar" alt="{{ __('content.photo') }}" title="{{ auth()->user()->name . __(' ') . auth()->user()->lastname }}">
                    <span class="d-sm-inline d-none"> {{ auth()->user()->name . __(' ') . auth()->user()->lastname}} </span>
                    <span class="d-sm-inline"> <i class="fas fa-angle-down"></i> </span>
                </a>
                        <!-- list -->
                        <ul class="dropdown-menu dropdown-menu-end  px-2 py-3 me-sm-n4" aria-labelledby="dropdownMenuButton_user">

                            <li class="mb-1">
                                <a class="dropdown-item border-radius-md"
                                @if (auth()->user()->role == 'CUSTOMER')
                                    href="{{ url('/client-area/user-profile') }}"
                                @else
                                    href="{{ url('/management-area/user-profile') }}"
                                @endif>
                                    <div class="d-flex py-1">
                                        <div class="my-auto">
                                            <span class="me-1"><i class="fas fa-user-edit"></i></span>
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="text-sm font-weight-normal">
                                                <span class="font-weight-bold">{{ __('content.profile') }}</span>
                                            </h6>
                                        </div>
                                    </div>
                                </a>
                            </li>

                            <li class="mb-1">
                                <a class="dropdown-item border-radius-md" href="{{ url('/logout')}}">
                                    <div class="d-flex py-1">
                                        <div class="my-auto">
                                            <!-- <img src="{ env('APP_URL') }/assets/img/icons/logout.png" class="me-3 "> -->
                                            <span class="me-1 "><i class="fas fa-sign-out-alt"></i></span>
                                        </div>
                                        <div class="d-flex flex-column justify-content-center">
                                            <h6 class="text-sm font-weight-normal">
                                                <span class="font-weight-bold">{{ __('content.sign_out') }}</span>
                                            </h6>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        </ul>
            </li>
            <!-- end user dropdown -->

            <!-- menu icon for other devices -->
            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                    <i class="sidenav-toggler-line"></i>
                    <i class="sidenav-toggler-line"></i>
                    <i class="sidenav-toggler-line"></i>
                </div>
                </a>
            </li>

            </ul>
        </div>
    </div>
</nav>
<!-- End Navbar -->
