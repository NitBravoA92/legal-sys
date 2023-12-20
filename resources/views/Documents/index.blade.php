@extends('layouts.user_type.auth')
@section('content')
    <div class="container-fluid py-4">

      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">
                <h5 class="mb-0">{{ __('content.document_repository') }}</h5>
            </div>

            <div class="card-body pt-4 p-3">
                <!-- init table -->
                <div class="table-responsive p-0">
                  <table class="table align-items-center justify-content-center mb-0" id="docment_repository-table">
                    <thead>
                      <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">{{ __('content.name') }}</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">{{ __('content.users.client') }}</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">{{ __('content.order') }} #</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">{{ __('content.service') }}</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">{{ __('content.status') }}</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">{{ __('content.date') }}</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">{{ __('content.action') }}</th>
                      </tr>
                    </thead>
                    <tbody>

                      @foreach ($clients_documents as $value)
                        <!--  -->
                        <tr>
                          <td class="align-middle text-left">
                            <span class="text-sm font-weight-bold mb-0">{{ $value->name }}</span>
                          </td>
                          <td>
                            <div class="d-flex px-2">
                              <div>
                                <img src="@if ($value->client_photo == ''){{ env('APP_URL') }}/assets/img/user_avatar/default-photo.png @else {{ env('APP_URL') }}{{ Storage::url($value->client_photo) }}@endif" class="avatar avatar-sm rounded-circle me-2" alt="Client">
                              </div>
                              <div class="my-auto">
                                <h6 class="mb-0 text-sm">{{ $value->client_name . ' ' . $value->client_lastname }}</h6>
                              </div>
                            </div>
                          </td>
                          <td class="align-middle text-left text-sm">
                            <span class="me-2 text-xs font-weight-bold">{{ $value->orderID }}</span>
                          </td>
                          <td>
                            <div class="d-flex px-2">
                              <div>
                                <img src="@if ($value->productImage == ''){{ env('APP_URL') }}/assets/img/services/account-service-01.jpeg @else {{ env('APP_URL') }}{{ Storage::url($value->productImage) }}@endif" class="avatar avatar-sm rounded-circle me-2" alt="Service image" id="services-image">
                              </div>
                              <div class="my-auto">
                                <h6 class="mb-0 text-sm">{{ $value->productName }}</h6>
                              </div>
                            </div>
                          </td>
                          <td class="align-middle text-left">
                            <span class="badge badge-sm {{ $value->type == 'RECEIVED' ? 'bg-gradient-warning' : 'bg-gradient-success' }} "><i class="fas fa-check"></i>
                              @if ($value->type == 'RECEIVED')
                                {{ __('Received') }}
                              @else
                                {{ __('Send') }}
                              @endif
                            </span>
                          </td>
                          <td class="align-middle text-left">
                            <span class="mb-0 text-sm"> {{ $value->updated_at }}</span>
                          </td>

                          <td class="align-middle">
                            <a class="btn btn-sm bg-gradient-dark text-white mb-0 px-3" data-bs-toggle="tooltip" data-bs-original-title="{{ __('content.download') }}" href="{{ route('documents.download-management', $value->id) }}">
                              <i class="fas fa-file-download text-xs"></i>
                            </a>
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

    </div>
@endsection
