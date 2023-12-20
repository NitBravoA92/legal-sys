@extends('layouts.user_type.guest')

@section('content')

<div class="page-header section-height-75">
    <div class="container">
        <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">
                <div class="card card-plain mt-8">
                    <div class="card-header pb-0 text-left bg-transparent">
                        <h4 class="mb-0">{{ __('content.messages.forgot_password') }}? {{ __('content.messages.enter_your_email_here') }}</h4>
                    </div>
                    <div class="card-body">
                    
                        <form action="/management-area/forgot-password" method="POST" role="form text-left">
                            @csrf
                            <div>
                                <label for="email">{{ __('content.email') }}</label>
                                <div class="">
                                    <input id="email" name="email" type="email" class="form-control" placeholder="{{ __('content.email') }}" aria-label="{{ __('content.email') }}" aria-describedby="email-addon">
                                    @error('email')
                                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="btn bg-gradient-info w-100 mt-4 mb-0">{{ __('content.messages.recover_your_password') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8">
                    <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6" style="background-image:url('../assets/img/curved-images/register-bg.jpg')"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection