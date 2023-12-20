@extends('layouts.user_type.guest')
@section('content')
<div class="page-header section-height-75">
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
                        <div class="text-center mx-auto mb-2">
                            <a class="ms-lg-0 ms-3 w-20" href="{{ url('client-area/login') }}">
                              <img src="@if($setting->app_logo == ''){{ env('APP_URL') }}/assets/img/logos/system-logo.png @else{{ env('APP_URL') }}{{ \Storage::url($setting->app_logo) }}@endif" class="navbar-brand-img h-60 w-60" alt="System Logo">
                            </a>
                          </div>

                        <h4 class="mb-0">{{ __('content.messages.thank_you_for_signing_up') }}!</h4>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ __('content.messages.check_confirm_email_we_sent') }}</p>
                        <small>{{ __('content.note') }}: {{ __('content.messages.if_you_not_receive_email')}}:</small>
                        <ul>
                            <li><small>{{ __('content.messages.check_spam_folder') }}</small></li>
                            <li><small>{{ __('content.messages.verify_if_you_type_your_email_correctly') }}</small></li>
                            <li><small>{{ __('content.messages.if_still_not_receive_email') }} <a href="{{ route('verification.send') }}">{{ __('content.messages.click_here_to_resent') }}</a>, {{ __('content.messages.or_contact_us_via') }}: <a href="mailto:support@legalsys.com">support@legalsys.com<a></small></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8">
                    <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6" style="background-image: url('../assets/img/curved-images/register-bg.jpg');"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection