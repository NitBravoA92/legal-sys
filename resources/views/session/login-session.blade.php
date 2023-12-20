@extends('layouts.user_type.guest')

@section('content')

  <section class="min-vh-100 mb-3 register-page-container">

    <div class="page-header align-items-start min-vh-50 pt-8 pb-6 mx-3 border-radius-lg">
      <span class="mask bg-gray-100 opacity-6"></span>
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-5 text-center mx-auto">
            <a class="ms-lg-0 ms-3 w-60" href="{{ url('management-area/login') }}">
              <img src="@if ($setting->app_logo == ''){{ env('APP_URL') }}/assets/img/logos/system-logo.png @else{{ env('APP_URL') }}{{ Storage::url($setting->app_logo) }}@endif" class="navbar-brand-img h-60 w-60" alt="System Logo">
            </a>

          </div>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="row mt-lg-n10 mt-md-n11 mt-n10">
        <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
          <div class="card z-index-0">
            <div class="card-header text-center pt-4">
              <h4 class="text-center font-weight-bolder text-dark text-gradient text-uppercase">{{ __('content.sign_in') }}.</h4>
            </div>
            <div class="card-body">
              <form role="form text-left" method="POST" action="/management-area/session">
                @csrf
                <div class="mb-3">
                  <input type="email" class="form-control" placeholder="{{__('content.email') }}" name="email" id="email" aria-label="{{__('content.email') }}" aria-describedby="email-addon" value="test_user@mail.com">
                  @error('email')
                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                  @enderror
                </div>

                <div class="mb-3">
                  <input type="password" class="form-control" placeholder="{{ __('content.users.password') }}" name="password" id="password" aria-label="{{ __('content.users.password') }}" aria-describedby="password-addon" value="test-user12345%">
                  @error('password')
                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                  @enderror
                </div>

                <div class="form-check form-check-info text-left">
                  <input class="form-check-input" type="checkbox" id="flexCheckDefault" checked="">
                  <label class="form-check-label text-dark font-weight-bolder" for="flexCheckDefault">
                    {{ __('content.users.remember_me') }}
                  </label>
                </div>
                <div class="text-center">
                  <button type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2">{{ __('content.sign_in') }}</button>
                </div>
              </form>

              <div class="text-center">
                <small class="text-muted">{{ __('content.messages.forgot_password') }}? {{ __('content.reset_it') }}
                  <a href="/management-area/login/forgot-password" class="text-dark text-gradient font-weight-bolder">{{ __('content.here') }}</a>
                </small>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

@endsection
