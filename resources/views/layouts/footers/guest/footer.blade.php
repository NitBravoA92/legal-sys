  <footer class="footer py-5">
    <div class="container">
      @if (!auth()->user() || \Request::is('static-sign-up'))
        <div class="row">
          <div class="col-8 mx-auto text-center mt-1">
            <p class="mb-0 text-secondary">
              Copyright © 2022 <span class="font-weight-bolder mb-0 text-capitalize">{{ config('app.name') }}.</span> {{ __('content.copyright')}}
            </p>
          </div>
        </div>
      @endif
    </div>
  </footer>
