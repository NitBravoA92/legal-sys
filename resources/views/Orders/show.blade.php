@extends('layouts.user_type.auth')
@section('content')

    <div class="container-fluid py-4">

      <div class="row">
        <div class="col-12 col-xl-6">
            <div class="row">
                <div class="col-12 col-md-4 col-lg-12 col-xl-12 mb-3">
                    <div class="card h-100">
                      <div class="card-header pb-0 p-3">

                        <div class="row">
                          <div class="col-md-8 d-flex align-items-center">
                            <h6 class="mb-0">{{ __('content.order')}} #{{ $order['order_id'] }}</h6>
                          </div>
                          <div class="col-md-4 text-end">

                          </div>
                        </div>

                      </div>
                      <div class="card-body p-3">
                            <ul class="list-group">
                              <li class="list-group-item border-0 ps-0 text-sm"><strong class="text-dark">{{ __('content.service')}}:</strong> &nbsp; {{ $order['service_name'] }}</li>
                              <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">{{ __('content.accounter')}}:</strong> &nbsp; {{ $order['accounter'] }}</li>
                              <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">{{ __('content.date_ordered')}}:</strong> &nbsp; {{ $order['order_started_at'] }}</li>
                              <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">{{ __('content.date_finished')}}:</strong> &nbsp; {{ $order['order_finished_at'] }} </li>
                              <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">{{ __('content.status')}}:</strong> &nbsp; <span class="badge badge-sm bg-gradient-{{ $order['status_color'] }}">{{ $order['status'] }}</span></li>
                              <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">{{ __('content.progress')}}:</strong> <br>

                                <div class="d-flex align-items-center justify-content-center">
                                  <div class="w-80">
                                    <div class="progress">
                                      <div class="progress-bar bg-gradient-{{ $order['status_color'] }}" role="progressbar" aria-valuenow="{{ $order['progress'] }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $order['progress'] }}%;"></div>
                                    </div>
                                  </div>
                                  <span class="me-2 text-xs font-weight-bold">{{ $order['progress'] }}%</span>
                                </div>

                              </li>

                              @if($order['status'] == 'ORDER COMPLETED')
                                  <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">{{ __('content.order_results')}}:</strong> <br>
                                      <a href="{{ route('documents.download-client', $order['order_final_document_id']) }}">{{ $order['order_final_document_name'] }}</a> <br>
                                      <p class="text-xs mt-1">{{ __('content.comments') }}: {{ $order['order_comments'] }}</p>
                                  </li>
                              @endif

                            </ul>
                      </div>
                    </div>
                  </div>

                  <div class="col-12 col-md-8 col-lg-12 col-xl-12">
                    <div class="card h-100">
                      <div class="card-header pb-0 p-3">
                        <h6 class="mb-0">{{ __('content.documents')}}</h6>
                      </div>
                      <div class="card-body p-3">

                        <div class="nav-wrapper position-relative end-0">
                            <ul class="nav nav-pills nav-fill p-1 bg-transparent" role="tablist">
                              <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab" href="#send" role="tab" aria-controls="send" aria-selected="true">
                                  <i class="far fa-paper-plane"></i>
                                  <span class="ms-1">{{ __('content.sent')}}</span>
                                </a>
                              </li>

                              <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#receive" role="tab" aria-controls="receive" aria-selected="false">
                                  <i class="fas fa-inbox"></i>
                                  <span class="ms-1">{{ __('content.received')}}</span>
                                </a>
                              </li>

                            </ul>

                            <!-- tab content -->
                            <div id="myTabContent" class="tab-content">

                              <div id="send" role="tabpanel" aria-labelledby="send-tab" class="docs-tab-fx tab-pane fade px-4 py-5 show active">
                                <ul class="list-group" id="documents-order-list">
                                  @foreach ($order['documents']['sent'] as $doc)
                                    <li class="list-group-item border-0 bb-1 d-flex justify-content-between ps-0 mb-2">
                                      <div class="d-flex flex-column">
                                        <h6 class="mb-1 text-dark font-weight-bold text-sm">{{ $doc->name }}</h6>
                                        <span class="text-xs">{{ __('content.sent_at')}} {{ $doc->updated_at }}</span>
                                      </div>
                                      <div class="d-flex align-items-center text-sm">
                                        <a href="{{ route('documents.download-client', $doc->id) }}" class="btn btn-link text-dark text-sm mb-0 px-0 ms-4"><i class="fas fa-file-download text-lg me-1"></i> {{ __('content.download') }}</a>
                                      </div>
                                    </li>
                                  @endforeach
                                </ul>
                              </div>

                              <div id="receive" role="tabpanel" aria-labelledby="receive-tab" class="docs-tab-fx tab-pane fade px-4 py-5">
                                <ul class="list-group">

                                  @foreach ($order['documents']['received'] as $doc)
                                    <li class="list-group-item border-0 bb-1 d-flex justify-content-between ps-0 mb-2">
                                      <div class="d-flex flex-column">
                                        <h6 class="mb-1 text-dark font-weight-bold text-sm">{{ $doc->name }}</h6>
                                        <span class="text-xs">{{ __('content.received_at')}} {{ $doc->updated_at }}</span>
                                      </div>
                                      <div class="d-flex align-items-center text-sm">
                                        <a href="{{ route('documents.download-client', $doc->id) }}" class="btn btn-link text-dark text-sm mb-0 px-0 ms-4"><i class="fas fa-file-download text-lg me-1"></i> {{ __('content.download') }}</a>
                                      </div>
                                    </li>
                                  @endforeach

                                  @if($order['status'] == 'ORDER COMPLETED')
                                    <li class="list-group-item border-0 bb-1 d-flex justify-content-between ps-0 mb-2">
                                      <div class="d-flex flex-column">
                                        <h6 class="mb-1 text-dark font-weight-bold text-sm">{{ $order['order_final_document_name'] }}</h6>
                                        <span class="text-xs">{{ __('content.final_documents')}}</span>
                                      </div>
                                      <div class="d-flex align-items-center text-sm">
                                        <a href="{{ route('documents.download-client', $order['order_final_document_id']) }}" class="btn btn-link text-dark text-sm mb-0 px-0 ms-4"><i class="fas fa-file-download text-lg me-1"></i> {{ __('content.download') }}</a>
                                      </div>
                                    </li>
                                  @endif

                                </ul>
                              </div>

                            </div>
                          </div>

                      </div>

                    </div>
                  </div>
            </div>
        </div>

        <div class="col-12 col-md-12 col-xl-6">
            <div class="card h-100">
              <div class="card-header pb-0 p-3">
                  <h5 class="mb-0 mt-4 text-center text-info text-gradient font-weight-bolder">{{ __('content.service_information') }}</h5>
              </div>
              <div class="card-body p-3 notif-tab-fx">

                <div class="row">
                @foreach ($order['service_data'] as $data_item)
                  @if ($data_item->field_type != 'file')
                    <div class="col-12 col-md-6 col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label font-weight-bold">
                          @if (session()->get('language') == 'es')
                            {{ $data_item->fieldname_spanish }}
                          @else
                            {{ $data_item->fieldname_english }}
                          @endif
                        </label>
                        <p class="mb-0 text-sm lh-8">{{ $data_item->data }}</p>
                      </div>
                    </div>
                  @endif
                @endforeach
                </div>

              </div>
            </div>
        </div>

        <div class="col-12 col-xl-12 mt-4">
          <div class="card h-100">
            <div class="card-header pb-0 p-3">
              <h6 class="mb-0">{{ __('content.notifications') }}</h6>
            </div>
            <div class="card-body p-3">

              <div class="nav-wrapper position-relative end-0">
                <ul class="nav nav-pills nav-fill p-1 bg-transparent" role="tablist">
                  <li class="nav-item">
                    <a class="nav-link mb-0 px-0 py-1 active" data-bs-toggle="tab" href="#notifications-sent" role="tab" aria-controls="notifications-sent" aria-selected="true">
                      <i class="far fa-paper-plane"></i>
                      <span class="ms-1">{{ __('content.sent')}}</span>
                    </a>
                  </li>

                  <li class="nav-item">
                    <a class="nav-link mb-0 px-0 py-1" data-bs-toggle="tab" href="#notifications-received" role="tab" aria-controls="notifications-received" aria-selected="false">
                      <i class="fas fa-inbox"></i>
                      <span class="ms-1">{{ __('content.received')}}</span>
                    </a>
                  </li>

                </ul>

                <!-- tab content -->
                <div id="notifications-tab-content" class="tab-content">
                  <div id="notifications-sent" role="tabpanel" aria-labelledby="notifications-sent-tab" class="tab-pane fade py-3 show active">
                    <ul class="px-2 py-3 ls-none" id="order-notifications-sent-list">

                      @foreach ($order['notifications']['sent'] as $sent)
                      <li class="mb-2 bb-1">
                          <a class="border-radius-md" href="javascript:;">
                          <div class="d-flex py-1">
                              <div class="my-auto">
                              <img src="@if (auth()->user()->photo == ''){{ env('APP_URL') }}/assets/img/user_avatar/default-photo.png @else {{ env('APP_URL') }}{{ Storage::url(auth()->user()->photo) }}@endif" class="avatar avatar-sm  me-3 ">
                              </div>
                              <div class="d-flex flex-column justify-content-center">
                              <h6 class="text-sm font-weight-normal mb-1">
                                  <span class="font-weight-bold">{{ $sent->title }}</span>
                              </h6>
                              <p class="text-secondary lh-8 text-sm mb-0 mt-0">
                                {{ $sent->content }}
                              </p>
                              <p class="text-xs text-secondary mb-0">
                                  {{ __('content.sent_at')}}
                                  <i class="fa fa-calendar me-1"></i>
                                  {{ $sent->updated_at }}
                              </p>
                              </div>
                          </div>
                          </a>
                      </li>
                      @endforeach

                    </ul>
                  </div>

                  <div id="notifications-received" role="tabpanel" aria-labelledby="notifications-received-tab" class="tab-pane fade py-3">
                    <!-- list of notifications -->

                    <ul class="px-2 py-3 ls-none" id="order-notifications-received-list">

                      @foreach ($order['notifications']['received'] as $received)
                        <li class="mb-2 bb-1">
                            <a class="border-radius-md" href="javascript:;">
                            <div class="d-flex py-1">

                              <div class="my-auto">
                                <img src="@if($sent->worker_photo == ''){{ env('APP_URL') }}/assets/img/user_avatar/default-photo.png @else {{ env('APP_URL') }}{{ Storage::url($sent->worker_photo) }}@endif" class="avatar avatar-sm me-3">
                                </div>

                                <div class="d-flex flex-column justify-content-center">
                                <h6 class="text-sm font-weight-normal mb-1">
                                    <span class="font-weight-bold">{{ $received->title }}</span>
                                </h6>
                                <p class="text-secondary lh-8 text-sm mb-0 mt-0">
                                  {{ $received->content }}
                                </p>
                                <p class="text-xs text-secondary mb-1">
                                  {{ __('content.received_from') }} {{ $received->worker_name . ' ' . $received->worker_lastname }}
                                </p>
                                <p class="text-xs text-secondary mb-0">
                                  {{ __('content.received_at')}}
                                    <i class="fa fa-calendar me-1"></i>
                                    {{ $received->updated_at }}
                                </p>
                                </div>
                            </div>
                            </a>
                        </li>
                      @endforeach

                    </ul>
                  </div>

                </div>
              </div>

            </div>


            <div class="card-footer p-3 mt-3">
              <div class="row">
                <div class="col-md-12">
                  <h6 class="mb-0">{{ __('content.send_a_notification') }}</h6>
                  <form action="{{ url('/client-area/send-order-notification/') . '/' . $order['order_id'] }}" method="POST">
                    @csrf
                    <div class="form-group mb-1">
                      <div class="input-group input-group-sm">
                        <span class="input-group-text" id="inputGroup-sizing-default">{{ __('content.title') }}</span>
                        <input type="text" name="title" id="notification-title" placeholder="" class="form-control" aria-label="{{ __('content.title') }}" aria-describedby="inputGroup-sizing-default" required>
                        @error('title')
                          <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                      </div>
                    </div>
                    <div class="input-group mb-3">
                      <textarea name="content" class="form-control" placeholder="{{ __('content.write_message') }}..." aria-describedby="button-addon2" id="notification-content" cols="30" rows="1" required></textarea>
                      <button class="btn bg-gradient-success mb-0 fs-6" type="submit" id="button-addon2"><i class="far fa-paper-plane"></i></button>
                      @error('content')
                        <p class="text-danger text-xs mt-2">{{ $message }}</p>
                      @enderror
                    </div>
                  </form>
                </div>
              </div>

            </div>

          </div>
        </div>

          <div class="col-md-12">
            <form name="additional-documents" action="{{ route('documents.store') }}" method="POST" role="form text-left" enctype="multipart/form-data" id="additional-docs">
              @csrf

            @if ($order['status'] == 'ADDITIONAL DOCUMENTS REQUIRED' && $order['additional_documents'] != null)
            <div class="card h-100 mt-3">
              <div class="card-header pb-0 p-3">
                <h6 class="mb-0">{{ __('content.upload_requested_additional_documents') }}</h6>
              </div>
              <div class="card-body p-3">

                  <div class="row">
                    @foreach ($order['additional_documents'] as $field)
                      <div class="col-md-6">
                        <div class="form-group">
                            <label for="data-file-field-{{ __( $field->id ) }}" class="form-control-label">
                                @if (session()->get('language') == 'es')
                                    {{ __($field->fieldname_spanish) }}
                                @else
                                    {{ __($field->fieldname_english) }}
                                @endif
                            </label>
                            <div class="">
                                <input class="form-control" type="file" id="data-file-field-{{ __( $field->id ) }}" name="additional-{{ __( $field->id ) }}" required>
                                <input type="hidden" name="data-token[]" value="{{ __( $field->id ) }}">
                            </div>
                        </div>
                      </div>
                    @endforeach
                    <input type="hidden" value="{{ $order['order_id'] }}" name="gitgth02848g*%gfd">
                  </div>

                  <div class="d-flex justify-content-end">
                    <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ __('content.upload') }}</button>
                </div>


              </div> <!-- card body -->
            </div> <!-- card -->
            @endif

          </form>
          </div>
      </div>
    </div>
@endsection
