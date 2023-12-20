@extends('layouts.user_type.guest')

@section('content')

  <main class="main-content mt-0">
    <section>
      <div class="page-header min-vh-75">
        <div class="container">
          <div class="row">

            <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">
                @if ($message != null)
                <div class="row">
                  <div class="col-12">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                      <span class="alert-icon"><i class="ni ni-like-2"></i></span>
                      <span class="alert-text text-white"><strong class="text-white">{{ $message }}</strong></span>
                      <button type="button" class="btn-close text-white" data-bs-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  </div>
                </div>
              @endif

              <div class="card card-plain mt-8">
                <div class="card-header pb-0 text-left bg-transparent">
                  <h1 class="font-weight-bolder text-info text-gradient">{{ __('content.messages.welcome_back') }}!</h1>
                  <h5 class="text-center text-uppercase">{{ __('content.sign_in') }}</h5>
                </div>
                <div class="card-body">
                  <form role="form" method="POST" action="/client-area/session">
                    @csrf
                    <label>{{ __('content.email') }}</label>
                    <div class="mb-3">
                      <input type="email" class="form-control" name="email" id="email" placeholder="{{ __('content.email') }}" value="customer-test@mail.com" aria-label="{{ __('content.email') }}" aria-describedby="email-addon">
                      @error('email')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                      @enderror
                    </div>
                    <label>{{ __('content.users.password') }}</label>
                    <div class="mb-3">
                      <input type="password" class="form-control" name="password" id="password" placeholder="{{ __('content.users.password') }}" value="customer12345%" aria-label="{{ __('content.users.password') }}" aria-describedby="password-addon">
                      @error('password')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                      @enderror
                    </div>
                    <div class="form-check form-switch">
                      <input class="form-check-input" type="checkbox" id="rememberMe" checked="">
                      <label class="form-check-label" for="rememberMe">{{ __('content.users.remember_me') }}</label>
                    </div>
                    <div class="text-center">
                      <input type="hidden" name="role" value="">
                      <button type="submit" class="btn bg-gradient-info w-100 mt-4 mb-0">{{ __('content.sign_in') }}</button>
                    </div>

                  </form>
                </div>
                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                  <div class="text-center">
                    <small class="text-muted">{{ __('content.messages.forgot_password') }}? {{ __('content.reset_it') }}
                      <a href="/client-area/login/forgot-password" class="text-info text-gradient font-weight-bolder">{{ __('content.here') }}</a>
                    </small>
                  </div>
                  <p class="mb-4 text-sm mx-auto">
                    {{ __('content.messages.dont_have_an_account') }}?
                    <a href="register" class="text-info text-gradient font-weight-bold">{{ __('content.sign_up') }}</a>
                  </p>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8">
                <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6" style="background-image:url('../assets/img/curved-images/login-bg.jpg')"></div> <!-- ../assets/img/curved-images/curved6.jpg -->
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

@endsection
