@extends('layouts.user_type.terms')
@section('content')
  <div class="container-fluid py-4">

    <div class="row justify-content-center">
      <div class="col-lg-5 text-center mx-auto">
        <a class="ms-lg-0 ms-3 w-60" href="#">
          <img src="@if($setting->app_logo == ''){{ env('APP_URL') }}/assets/img/logos/system-logo.png @else{{ env('APP_URL') }}{{ Storage::url($setting->app_logo) }}@endif" class="navbar-brand-img h-60 w-60" alt="System Logo">
        </a>

      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">
        <div class="card mt-4">
          <div class="card-header pb-0 p-3">
            <div class="row">
              <div class="col-12 d-flex justify-content-center align-items-center">
                <h4 class="mb-0 text-center">{{ __('content.terms_and_conditions') }}</h4>
              </div>
            </div>
          </div>
          <div class="card-body p-3">
            <div class="row">
              <div class="col-md-12">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

