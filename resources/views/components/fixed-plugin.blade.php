<div class="fixed-plugin">
    <a class="fixed-plugin-button text-dark position-fixed px-3 py-2">
      <i class="fa fa-cog py-2"> </i>
    </a>
    <div class="card shadow-lg ">
      <div class="card-header pb-0 pt-3 ">
        <div class="float-start">
          <h5 class="mt-3 mb-0">Settings</h5>
          <p>All application view configurations.</p>
        </div>
        <div class="float-end mt-4">
          <button class="btn btn-link text-dark p-0 fixed-plugin-close-button">
            <i class="fas fa-close"></i>
          </button>
        </div>
        <!-- End Toggle Button -->
      </div>
      <hr class="horizontal dark my-1">
      <div class="card-body pt-sm-3 pt-0">

        <form action="{{ route('settings.store') }}" method="POST" role="form text-left" enctype="multipart/form-data">
          @csrf
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="app_name" class="form-control-label">{{ __('content.app.name') }}</label>
                <div class="@error('setting.app_name')border border-danger rounded-3 @enderror">
                  <input class="form-control" value="{{ session('setting')->app_name }}" type="text" id="app_name" name="app_name">
                    @error('app_name')
                      <p class="text-danger text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                <label for="app_owner" class="form-control-label">{{ __('content.app.owner') }}</label>
                <div class="@error('setting.app_owner')border border-danger rounded-3 @enderror">
                  <input class="form-control" value="{{ session('setting')->app_owner }}" type="text" id="app_owner" name="app_owner">
                    @error('app_owner')
                      <p class="text-danger text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                <label for="app_address" class="form-control-label">{{ __('content.app.address') }}</label>
                <div class="@error('setting.app_address')border border-danger rounded-3 @enderror">
                  <input class="form-control" value="{{ session('setting')->app_address }}" type="text" id="app_address" name="app_address">
                    @error('app_address')
                      <p class="text-danger text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                <label for="app_email" class="form-control-label">{{ __('content.app.email') }}</label>
                <div class="@error('setting.app_email')border border-danger rounded-3 @enderror">
                  <input class="form-control" value="{{ session('setting')->app_email }}" type="email" id="app_email" name="app_email">
                    @error('app_email')
                      <p class="text-danger text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                <label for="app_phone" class="form-control-label">{{ __('content.app.phone') }}</label>
                <div class="@error('setting.app_phone')border border-danger rounded-3 @enderror">
                  <input class="form-control" value="{{ session('setting')->app_phone }}" type="text" id="app_phone" name="app_phone">
                    @error('app_phone')
                      <p class="text-danger text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                <label for="app_logo" class="form-control-label">{{ __('content.app.logo') }}</label>
                <div class="@error('setting.app_logo')border border-danger rounded-3 @enderror">
                  <input class="form-control" type="file" id="app_logo" name="app_logo">
                    @error('app_logo')
                      <p class="text-danger text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>
              </div>
            </div>

            <div class="col-md-12">
              <div class="form-group">
                <label for="about_us" class="form-control-label">{{ __('content.app.about_us') }}</label>
                <div class="@error('setting.about_us')border border-danger rounded-3 @enderror">
                  <textarea class="form-control" id="about_us" name="about_us" rows="5">{{ session('setting')->about_us }}</textarea>
                    @error('about_us')
                      <p class="text-danger text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>
              </div>
            </div>

            <div class="col-md-12">
              <button type="submit" class="btn bg-gradient-dark w-100">Save Changes</button>
            </div>

          </div>
        </form>
        <hr class="horizontal dark my-sm-4">
      </div>
    </div>
  </div>
