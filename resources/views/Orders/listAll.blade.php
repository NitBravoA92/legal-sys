@extends('layouts.user_type.auth')

@section('content')
    <div class="container-fluid py-4">

      <div class="row">
        <div class="col-12">

          <div class="card mb-4">
            <div class="card-header pb-0">
                <h5 class="mb-0">{{ __('content.clients_orders') }}</h5>
            </div>

            <div class="card-body pt-4 p-3">
                <!-- init table -->
                <div class="table-responsive p-0">
                  <table class="table align-items-center justify-content-center mb-0" id="client_orders-table">
                    <thead>
                      <tr>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{ __('content.order') }} #</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">{{ __('content.service') }}</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">{{ __('content.users.client') }}</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">{{ __('content.accounter') }}</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">{{ __('content.status') }}</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">{{ __('content.validation') }}</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">{{ __('content.completion') }}</th>
                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder text-center opacity-7 ps-2">{{ __('content.action') }}</th>
                      </tr>
                    </thead>
                    <tbody>

                      @foreach ($clientOrders as $value)
                        <tr>
                          <td>
                            <p class="text-sm font-weight-bold mb-0">#{{ $value['order_id'] }}</p>
                          </td>
                          <td>
                            <div class="d-flex px-2">
                              <div>
                                <img src="@if ($value['image'] == ''){{ env('APP_URL') }}/assets/img/services/account-service-01.jpeg @else {{ env('APP_URL') }}{{ \Storage::url($value['image']) }}@endif" class="avatar avatar-sm rounded-circle me-2" alt="Service image" id="services-image">
                              </div>
                              <div class="my-auto">
                                <h6 class="mb-0 text-sm">{{ $value['name'] }}</h6>
                              </div>
                            </div>
                          </td>
                          <td>
                            <p class="text-sm font-weight-bold mb-0">{{ $value['client_name'] }}</p>
                          </td>
                          <td>
                            <p class="text-sm font-weight-bold mb-0">{{ $value['accounter'] }}</p>
                          </td>
                          <td class="align-middle text-center text-sm">
                            <span class="badge badge-sm bg-gradient-{{ __( $value['status_color'] ) }}">{{ $value['status'] }}</span>
                          </td>
                          <td class="align-middle text-center text-sm">
                            @if ($value['validation'] == "Not Required")
                              <span class="badge badge-sm bg-gradient-dark">{{ $value['validation'] }}</span>
                            @else
                              <span class="badge badge-sm @if ($value['validation'] == 'Validation Required') bg-gradient-warning @else bg-gradient-success @endif">{{ $value['validation'] }}</span>
                            @endif
                          </td>
                          <td class="align-middle text-center">
                            <div class="d-flex align-items-center justify-content-center">
                              <span class="me-2 text-xs font-weight-bold">{{ $value['progress'] }}%</span>
                              <div>
                                <div class="progress">
                                  <div class="progress-bar bg-gradient-{{ __( $value['status_color'] ) }}" role="progressbar" aria-valuenow="{{ __( $value['progress'] ) }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ __( $value['progress'] ) }}%;"></div>
                                </div>
                              </div>
                            </div>
                          </td>
                          <td class="align-middle">
                            <a class="btn btn-sm bg-gradient-info text-white mb-0 px-2" data-bs-toggle="tooltip" data-bs-original-title="{{ __('content.view_details') }}" href="{{ url('/management-area/client-service-order/details/') . '/' . $value['order_id'] }}">
                              <i class="far fa-eye text-xs"></i>
                            </a>

                            <!-- check if order is cancelled -->
                            @if ($value['status'] == "CANCELLED")
                            <!-- button to restart the order -->
                            <!-- button to delete the order -->
                            @else

                              @if ($value['validation'] == "Validation Required")
                                <a class="btn btn-sm bg-gradient-success text-white mb-0 px-2" data-bs-toggle="tooltip" data-bs-original-title="{{ __('content.validate_order') }}" href="{{ url('/management-area/clients-service-orders/validate-order/') . '/' . $value['order_id'] }}">
                                  <i class="fas fa-check text-xs"></i>
                                </a>
                              @else

                                @if ($value['status'] == "PROCESS STARTED")
                                <!-- button to take order and work on it -->
                                  <a class="btn btn-sm bg-gradient-dark text-white mb-0 px-2" data-bs-toggle="tooltip" data-bs-original-title="{{ __('content.take_order') }}" href="{{ url('/management-area/clients-service-orders/take-order/') . '/' . $value['order_id'] }}">
                                    <i class="fas fa-check text-xs"></i>
                                  </a>
                                  <!-- button to cancel the order -->
                                  <a class="btn btn-sm bg-gradient-warning text-white mb-0 px-2" data-bs-toggle="tooltip" data-bs-original-title="{{ __('content.messages.cancel_button') }}" href="{{ url('/management-area/clients-service-orders/cancel/') . '/' . $value['order_id'] }}">
                                    <i class="fas fa-ban text-xs"></i>
                                  </a>
                                @endif

                                @if ($value['status'] == "IN PROCESS")
                                  @if ($value['accounter_id'] == auth()->user()->id)
                                    <!-- button to deliver the order -->
                                    <a class="btn btn-sm bg-gradient-success text-white mb-0 px-2" data-bs-toggle="tooltip" data-bs-original-title="{{ __('content.order_finished') }}" href="{{ url('/management-area/clients-service-orders/order-finished/') . '/' . $value['order_id'] }}">
                                      <i class="fas fa-check text-xs"></i>
                                    </a>
                                    <!-- button to cancel the order -->
                                    <a class="btn btn-sm bg-gradient-warning text-white mb-0 px-2" data-bs-toggle="tooltip" data-bs-original-title="{{ __('content.messages.cancel_button') }}" href="{{ url('/management-area/clients-service-orders/cancel/') . '/' . $value['order_id'] }}">
                                      <i class="fas fa-ban text-xs"></i>
                                    </a>
                                  @endif

                                @endif

                              @endif

                            @endif

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
