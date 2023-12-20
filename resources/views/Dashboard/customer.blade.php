@extends('layouts.user_type.auth')
@section('content')

  <div class="row mt-4 pb-3">

    <!-- show services to clients -->
    <div class="col-md-12 col-lg-8 mb-4">
      <div class="row">

        <!-- main services -->
        <div class="col-md-12">
          <div class="row mt-0 mb-4">
            <h6 class="text-muted mt-2 mb-0 fw-400">{{ __('content.main_services') }}</h6>
          </div>

          <div id="main-services" class="owl-carousel owl-theme"> <!-- carousel to show the services -->

            @foreach($services['main_services'] as $service)
              @if ($service->status == 'active')
                <div class="card" style="background: linear-gradient(45deg, #344767 55%, #ffffff 50%); ">
                  <div class="card-body p-3">

                    <div class="row">
                      <div class="col-lg-                            <!-- button to restart the order -->
                            <!-- button to delete the order -->6">
                        <div class="d-flex flex-row flex-nowrap justify-content-start">
                          <div class="h-100 w-20"> <!-- bg-gradient-dark border-radius-lg -->
                            <div class="h-100">
                              <i class="fas fa-briefcase text-info" style="font-size: 2.8rem;"></i>
                            </div>
                          </div>
                          <div class="d-flex flex-column h-100 w-80">
                            <h5 class="text-white mb-0">{{ __( $service->name ) }}</h5>
                            <hr class="mt-0 bg-info">
                            <p class="mb-3 pt-0 text-white mt-0 service-subtitle" style="line-height: 1">{{ __( $service->description ) }}</p>
                            <a class="btn btn-sm btn-round mb-0 me-1 bg-gradient-info icon-move-right mt-auto" href="{{ url('/client-area/service-orders/create-service-order/') . __('/') . $service->id }}">
                              {{ __('content.order_now') }}
                              <i class="fas fa-arrow-right text-sm ms-1" aria-hidden="true"></i>
                            </a>
                          </div>
                        </div>
                      </div>

                      <div class="col-lg-5 ms-auto text-center mt-5 mt-lg-0"></div>

                    </div>
                  </div>
                </div>
              @endif
            @endforeach
            <!-- -->

          </div>
        </div>

        <!--additional services -->
        <div class="col-md-12">

          <div class="row mt-4 mb-4">
            <h6 class="text-muted mt-2 mb-0 fw-400">{{ __('content.additional_services') }}</h6>
          </div>

          <div id="additional-services" class="owl-carousel owl-theme">

            <!-- -->
            @foreach($services['additional_services'] as $service)
              @if ($service->status == 'active')
                <div class="card">
                  <div class="card-header mx-4 p-3 text-center">
                    <div class="icon icon-shape icon-lg bg-gradient-info shadow text-center border-radius-lg">
                      <i class="fab fa-accusoft opacity-10"></i>
                    </div>
                  </div>
                  <div class="card-body pt-0 p-3 text-center">
                    <h6 class="text-center mb-0">{{ __( $service->name ) }}</h6>
                    <p class="text-xs" style="line-height: 1">{{ __( $service->description ) }}</p>
                    <hr class="horizontal dark my-3">
                    <a class="btn btn-sm btn-round mb-0 me-1 bg-gradient-info icon-move-right mt-auto" href="{{ url('/client-area/service-orders/create-service-order/') . __('/') . $service->id }}">
                      {{ __('content.order_now') }}
                      <i class="fas fa-arrow-right text-sm ms-1" aria-hidden="true"></i>
                    </a>
                  </div>
                </div>
              @endif
            @endforeach
            <!-- -->

           </div>
        </div>
      </div>

    </div>

    <!-- chart to show orders overview -->

    <div class="col-lg-4 col-md-12 mb-lg-0 mb-4">
      <div class="card z-index-2">
        <div class="card-body p-3">
          <div class="bg-gradient-dark border-radius-lg py-3 pe-1 mb-3">
            <div class="chart">
              <canvas id="chart-bars" class="chart-canvas" height="170"></canvas>
            </div>
          </div>
          <h6 class="ms-2 mt-4 mb-0">{{ __('content.my_orders_overview') }}</h6>
          <p class="text-sm ms-2"> <i class="far fa-calendar-alt"></i> Always</p>
          <div class="container border-radius-lg">
            <div class="row">
              <div class="col-6 py-3 ps-0">
                <div class="d-flex mb-2">

                  <div class="icon icon-shape icon-xs shadow border-radius-sm bg-gradient-danger text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="fas fa-folder-open icon-x8"></i>
                  </div>

                  <p class="text-xs mt-1 mb-0 font-weight-bold">{{ __('content.cancelled') }}</p>
                </div>
                <h4 class="font-weight-bolder">{{ $order_status_count['CANCELLED'] }}</h4>
              </div>
              <div class="col-6 py-3 ps-0">
                <div class="d-flex mb-2">

                  <div class="icon icon-shape icon-xs shadow border-radius-sm bg-gradient-info text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="fas fa-folder-open icon-x8"></i>
                  </div>

                  <p class="text-xs mt-1 mb-0 font-weight-bold">{{ __('content.process_started') }}</p>
                </div>
                <h4 class="font-weight-bolder">{{ $order_status_count['PROCESS STARTED'] }}</h4>
              </div>

              <div class="col-6 py-3 ps-0">
                <div class="d-flex mb-2">
                  <div class="icon icon-shape icon-xs shadow border-radius-sm bg-gradient-warning text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="fas fa-file-alt icon-x8"></i>
                  </div>
                  <p class="text-xs mt-1 mb-0 font-weight-bold">{{ __('content.required_additional_docs') }}</p>
                </div>
                <h4 class="font-weight-bolder">{{ $order_status_count['ADDITIONAL DOCUMENTS REQUIRED'] }}</h4>
              </div>
              <div class="col-6 py-3 ps-0">
                <div class="d-flex mb-2">
                  <div class="icon icon-shape icon-xs shadow border-radius-sm bg-gradient-dark text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="fas fa-file-signature icon-x8"></i>
                  </div>
                  <p class="text-xs mt-1 mb-0 font-weight-bold">{{ __('content.in_process') }}</p>
                </div>
                <h4 class="font-weight-bolder">{{ $order_status_count['IN PROCESS'] }}</h4>
              </div>
              <div class="col-6 py-3 ps-0">
                <div class="d-flex mb-2">
                  <div class="icon icon-shape icon-xs shadow border-radius-sm bg-gradient-secondary text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="fas fa-check icon-x8"></i>
                  </div>
                  <p class="text-xs mt-1 mb-0 font-weight-bold">{{ __('content.finished') }}</p>
                </div>
                <h4 class="font-weight-bolder">{{ $order_status_count['PROCESS FINISHED'] }}</h4>
              </div>
              <div class="col-6 py-3 ps-0">
                <div class="d-flex mb-2">
                  <div class="icon icon-shape icon-xs shadow border-radius-sm bg-gradient-success text-center me-2 d-flex align-items-center justify-content-center">
                    <i class="fas fa-check icon-x8"></i>
                  </div>
                  <p class="text-xs mt-1 mb-0 font-weight-bold">{{ __('content.completed') }}</p>
                </div>
                <h4 class="font-weight-bolder">{{ $order_status_count['ORDER COMPLETED'] }}</h4>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>

<script>
    window.onload = function() {
      var ctx = document.getElementById("chart-bars").getContext("2d");

      new Chart(ctx, {
        type: "bar",
        data: {
          labels: ["Cancelled", "Process Started", "Required Additional Documents", "In Process", "Finished", "Completed"],
          datasets: [{
            label: "Orders",
            tension: 1,
            borderWidth: 0,
            borderRadius: 4,
            borderSkipped: false,
            backgroundColor: "#fff",
            data: [`{{$order_status_count['CANCELLED']}}`, `{{$order_status_count['PROCESS STARTED']}}`, `{{$order_status_count['ADDITIONAL DOCUMENTS REQUIRED']}}`, `{{$order_status_count['IN PROCESS']}}`, `{{$order_status_count['PROCESS FINISHED']}}`, `{{$order_status_count['ORDER COMPLETED']}}`],
            maxBarThickness: 6
          }, ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false,
            }
          },
          interaction: {
            intersect: false,
            mode: 'index',
          },
          scales: {
            y: {
              grid: {
                drawBorder: false,
                display: true,
                drawOnChartArea: false,
                drawTicks: false,
              },
              ticks: {
                suggestedMin: 0,
                suggestedMax: 500,
                beginAtZero: true,
                padding: 15,
                font: {
                  size: 14,
                  family: "Open Sans",
                  style: 'normal',
                  lineHeight: 2
                },
                color: "#fff"
              },
            },
            x: {
              grid: {
                drawBorder: false,
                display: true,
                drawOnChartArea: false,
                drawTicks: false
              },
              ticks: {
                display: false
              },
            },
          },
        },
      });
    }
</script>
@endsection
