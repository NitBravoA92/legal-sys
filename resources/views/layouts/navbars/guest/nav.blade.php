<!-- Navbar -->
<nav class="navbar navbar-expand-lg position-absolute top-0 z-index-3 my-3 {{ (Request::is('static-sign-up') ? 'w-100 shadow-none  navbar-transparent mt-4' : 'blur blur-rounded shadow py-2 start-0 end-0 mx4') }}">
  <div class="container-fluid" style="flex-wrap: nowrap; justify-content: flex-start;">

    <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 w-55" @if (explode('/', Request::path())[0] == 'client-area') href="{{ url('client-area/login') }}"
    @else href="{{ url('management-area/login') }}" @endif>

      <img src="@if ($setting->app_logo == ''){{ env('APP_URL') }}/assets/img/logos/system-logo.png @else{{ env('APP_URL') }}{{ Storage::url($setting->app_logo) }}@endif" class="navbar-brand-img h-15 w-15" alt="System Logo">
    </a>
    <ul class="navbar-nav mx-auto">
      @if (Request::is('client-area/register'))
        <li class="nav-item">
          <a href="{{ url('client-area/login') }}" class="btn btn-sm btn-round mb-0 me-1 bg-gradient-dark btn-guest">
            <i class="fas fa-key opacity-6 me-1 text-white"></i> {{ __('content.sign_in') }}</a>
        </li>
      @endif
      @if (Request::is('client-area/login'))
        <li class="nav-item">
          <a href="{{ url('client-area/register') }}" class="btn btn-sm btn-round mb-0 me-1 bg-gradient-info btn-guest">
            <i class="fas fa-user-circle opacity-6 me-1 text-white"></i> {{ __('content.create_an_account') }}</a>
        </li>
      @endif
    </ul>

  </div>
</nav>
<!-- End Navbar -->
