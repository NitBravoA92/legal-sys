@extends('layouts.user_type.guest')

@section('content')

  <section class="min-vh-100 mb-3 register-page-container">
    <div class="page-header align-items-start min-vh-50 pt-5 pb-11 mx-3 border-radius-lg" style="background-image: url('../assets/img/curved-images/register-bg.jpg');"> <!-- ../assets/img/curved-images/curved14.jpg -->
      <span class="mask bg-gradient-dark opacity-6"></span>
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-lg-5 text-center mx-auto">
            <h1 class="text-white mb-2 mt-5">{{ __('content.welcome') }}!</h1>
            <h5 class="text-lead text-white">{{ __('content.messages.sign_up_and_enjoy') }}.</h5>
          </div>
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row mt-lg-n10 mt-md-n11 mt-n10">
        <div class="col-xl-4 col-lg-5 col-md-7 mx-auto">
          <div class="card z-index-0">
            <div class="card-header text-center pt-4">
              <h6 class="text-center text-uppercase">{{ __('content.create_your_account') }}.</h6>
            </div>
            <div class="card-body">

              <form role="form text-left" method="POST" action="/client-area/register">
                @csrf
                <div class="mb-3">
                  <input type="text" class="form-control" placeholder="{{ __('content.first_name') }}" name="name" id="name" aria-label="{{ __('content.first_name') }}" aria-describedby="name" value="{{ old('name') }}">
                  @error('name')
                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                  @enderror
                </div>

                <div class="mb-3">
                  <input type="text" class="form-control" placeholder="{{ __('content.last_name') }}" name="lastname" id="lastname" aria-label="{{ __('content.last_name') }}" aria-describedby="lastname" value="{{ old('lastname') }}">
                  @error('lastname')
                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                  @enderror
                </div>

                <div class="mb-3">
                  <input type="text" class="form-control" placeholder="{{ __('content.phone_number') }}" name="phone" id="phone" aria-label="{{ __('content.phone_number') }}" aria-describedby="phone" value="{{ old('phone') }}">
                  @error('phone')
                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                  @enderror
                </div>

                <div class="mb-3">
                  <input type="text" class="form-control" placeholder="{{ __('content.alt_phone_num') }}" name="alt_phone" id="alt_phone" aria-label="{{ __('content.alt_phone_num') }}" aria-describedby="alt_phone" value="{{ old('alt_phone') }}">
                  @error('alt_phone')
                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                  @enderror
                </div>

                <div class="mb-3">
                  <input type="email" class="form-control" placeholder="{{ __('content.email') }}" name="email" id="email" aria-label="{{ __('content.email') }}" aria-describedby="email-addon" value="{{ old('email') }}">
                  @error('email')
                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                  @enderror
                </div>

                <div class="mb-3">
                  <input type="password" class="form-control" placeholder="{{ __('content.users.password') }}" name="password" id="password" aria-label="{{ __('content.users.password') }}" aria-describedby="password-addon">
                  @error('password')
                    <p class="text-danger text-xs mt-2">{{ $message }}</p>
                  @enderror
                </div>

                <div class="form-check form-check-info text-left">
                  <input class="form-check-input" type="checkbox" name="agreement" id="flexCheckDefault" checked>
                  <label class="form-check-label" for="flexCheckDefault">
                    {{ __('content.i_agree_the') }} <a href="javascript:;" class="text-dark font-weight-bolder">{{ __('content.terms_and_conditions') }}</a>
                  </label>
                  @error('agreement')
                    <p class="text-danger text-xs mt-2">{{ __('content.messages.agree_conditions_try_again') }}.</p>
                  @enderror
                </div>
                
                <input type="hidden" name="role" value="{{ __('CUSTOMER') }}">

                <div class="text-center">
                  <button type="submit" class="btn bg-gradient-dark w-100 my-4 mb-2">{{ __('content.sign_up') }}</button>
                </div>

                <p class="text-sm mt-3 mb-0">{{ __('content.messages.already_have_account') }}? <a href="login" class="text-dark font-weight-bolder">{{ __('content.sign_in') }}</a></p>
              </form>

            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

@endsection

