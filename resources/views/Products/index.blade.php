@extends('layouts.user_type.auth')

@section('content')

    <div class="row">
      <div class="col-12">

        <div class="card mb-4 mx-4">

          <div class="card-header pb-0 px-3">
            <div class="d-flex flex-row justify-content-between">
                <div>
                    <h5 class="mb-0">{{ __('content.all_our_services') }}</h5>
                </div>
                <div>
                    <a href="{{ route('services.create') }}" class="btn bg-gradient-info btn-sm mb-0" type="button"><i class="fas fa-plus"></i>&nbsp; {{ __('content.new_service') }}</a>
                </div>
            </div>
          </div>

          <div class="card-body pt-4 p-3">
    <!-- init table -->
    <div class="table-responsive p-0">
      <table class="table align-items-center justify-content-center mb-0" id="services-table">
          <thead>
              <tr>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{ __('content.service') }}</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">{{ __('content.service_type') }}</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">{{ __('content.validator') }}</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">{{ __('content.status') }}</th>
                  <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">{{ __('content.action') }}</th>
              </tr>
          </thead>
          <tbody>

              @foreach ($products as $item)
              <tr>
                <td>
                    <p class="text-sm font-weight-bold mb-0">{{ $item['product']->id }}</p>
                </td>
                <td>
                  <div class="d-flex px-2">
                    <div>
                      <img src="@if($item['product']->image == ''){{ env('APP_URL') }}/assets/img/services/account-service-01.jpeg @else{{ env('APP_URL') }}{{ \Storage::url($item['product']->image)}}@endif" class="avatar avatar-sm rounded-circle me-2" alt="Service image" id="services-image">
                    </div>
                    <div class="my-auto">
                      <h6 class="mb-0 text-sm">{{ $item['product']->name }}</h6>
                    </div>
                  </div>
                </td>
                <td>
                  <p class="text-sm font-weight-bold mb-0">{{ $item['product']->type_service }}</p>
                </td>
                <td>
                    <p class="text-sm font-weight-bold mb-0">{{ $item['validator'] }}</p>
                </td>
                <td class="align-middle text-center text-sm">
                  <span class="badge badge-sm @if ($item['product']->status == 'active') bg-gradient-success @else bg-gradient-danger @endif">{{ $item['product']->status }}</span>
                </td>
                <td class="d-flex justify-content-center align-middle">

                    <form @if ($item['product']->status == 'active') action="{{ route('services.inactive', $item['product']->id) }}" @else  action="{{ route('services.active', $item['product']->id) }}" @endif method="POST">
                      @csrf
                      <button type="submit" class="btn-alert-update-status btn btn-sm @if ($item['product']->status == 'active') bg-gradient-warning @else bg-gradient-success @endif text-white mb-0 px-2" data-bs-toggle="tooltip" data-bs-original-title="@if ($item['product']->status == 'active') {{ __('content.inactive_service') }} @else {{ __('content.active_service') }} @endif" id="service_option_status">
                        @if ($item['product']->status == 'active')
                          <i class="fas fa-ban text-xs"></i>
                        @else
                          <i class="fas fa-check text-xs"></i>
                        @endif
                      </button>
                    </form>

                    <form action="{{ route('services.destroy', $item['product']->id) }}" method="POST">
                      @csrf
                      {{ method_field('DELETE') }}
                      <button type="submit" class="btn-delete-data btn btn-sm bg-gradient-danger text-white mb-0 mx-1 px-2" data-bs-toggle="tooltip" data-bs-original-title="{{ __('content.delete_service') }}" id="service_option_delete">
                        <i class="far fa-trash-alt text-xs"></i>
                      </button>
                    </form>

                </td>
              </tr>
              @endforeach
                    </tbody>
                  </table>
                </div>
                <!-- end of table -->

          </div>
        </div>
      </div>

    </div>
@endsection

