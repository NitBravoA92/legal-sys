
@extends('layouts.user_type.auth')

@section('content')

<style>
    #additional_documents_input {
    width: 5px;
    height: 5px;
    background-color: white;
    color: white;
    }
    #additional_docs_label{
    cursor: pointer;
    }
</style>

    <!-- customer header -->
    <div class="container-fluid">
        <div class="page-header min-height-200 border-radius-xl mt-4" style="background-image: url('{{ env('APP_URL') }}/assets/img/curved-images/white-curved.jpeg'); background-position-y: 50%;">
            <span class="mask bg-gradient-info opacity-6"></span>
        </div>
        <div class="card card-body blur shadow-blur mx-4 mt-n6">
            <div class="row gx-4">
                <div class="col-auto">
                    <div class="avatar avatar-xl position-relative">
                        <img src="@if ($order['client_photo'] == ''){{ env('APP_URL') }}/assets/img/user_avatar/default-photo.png @else {{ env('APP_URL') }}{{ \Storage::url($order['client_photo']) }}@endif" alt="Customer Image" class="w-100 border-radius-lg shadow-sm">
                    </div>
                </div>
                <div class="col-auto my-auto">
                    <div class="h-100">
                        <h5 class="mb-1">
                            {{ $order['client_name'] }}
                        </h5>
                        <p class="mb-0 font-weight-bold text-sm">
                            {{ __('CUSTOMER') }}
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                    <div class="nav-wrapper position-relative end-0">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- order details -->
    <div class="container-fluid py-4">

        <div class="row">
          <div class="col-12 col-md-12 col-xl-6">

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
                                <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">{{ __('content.validation')}}:</strong> &nbsp;
                                  @if ($order['validation'] == 'Validation Required')
                                    <span class="badge badge-sm bg-gradient-warning">{{ $order['validation'] }}</span>
                                  @endif
                                  @if ($order['validation'] == 'Validated')
                                    <span class="badge badge-sm bg-gradient-success">{{ $order['validation'] }}</span>
                                  @endif
                                  @if ($order['validation'] == 'Not Required')
                                    <span class="badge badge-sm bg-gradient-dark">{{ $order['validation'] }}</span>
                                  @endif
                                </li>

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
                                      <a href="{{ route('documents.download-management', $order['order_final_document_id']) }}">{{ $order['order_final_document_name'] }}</a> <br>
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
                              <div id="clientDocumentsTabContent" class="tab-content">

                                <div id="send" role="tabpanel" aria-labelledby="send-tab" class="docs-tab-fx tab-pane fade px-4 py-5 show active">
                                    <ul class="list-group" id="clients-documents-order-list">

                                      @foreach ($order['documents']['received'] as $doc)
                                        <li class="list-group-item border-0 bb-1 d-flex justify-content-between ps-0 mb-2">
                                          <div class="d-flex flex-column">
                                            <h6 class="mb-1 text-dark font-weight-bold text-sm">{{ $doc->name }}</h6>
                                            <span class="text-xs">{{ __('content.sent_at')}} {{ $doc->updated_at }}</span>
                                          </div>
                                          <div class="d-flex align-items-center text-sm">
                                            <a href="{{ route('documents.download-management', $doc->id) }}" class="btn btn-link text-dark text-sm mb-0 px-0 ms-4"><i class="fas fa-file-download text-lg me-1"></i> {{ __('content.download') }}</a>
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
                                          <a href="{{ route('documents.download-management', $order['order_final_document_id']) }}" class="btn btn-link text-dark text-sm mb-0 px-0 ms-4"><i class="fas fa-file-download text-lg me-1"></i> {{ __('content.download') }}</a>
                                        </div>
                                      </li>
                                    @endif

                                    </ul>
                                </div>

                                <div id="receive" role="tabpanel" aria-labelledby="receive-tab" class="docs-tab-fx tab-pane fade px-4 py-5">
                                  <ul class="list-group border-bottom-list">

                                      @foreach ($order['documents']['sent'] as $doc)
                                        <li class="list-group-item border-0 bb-1 d-flex justify-content-between ps-0 mb-2">
                                          <div class="d-flex flex-column">
                                            <h6 class="mb-1 text-dark font-weight-bold text-sm">{{ $doc->name }}</h6>
                                            <span class="text-xs">{{ __('content.received_at')}} {{ $doc->updated_at }}</span>
                                          </div>
                                          <div class="d-flex align-items-center text-sm">
                                            <a href="{{ route('documents.download-management', $doc->id) }}" class="btn btn-link text-dark text-sm mb-0 px-0 ms-4"><i class="fas fa-file-download text-lg me-1"></i> {{ __('content.download') }}</a>
                                          </div>
                                        </li>
                                      @endforeach

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
                      <ul class="px-2 py-3 ls-none border-bottom-list" id="order-notifications-sent-list">

                        @foreach ($order['notifications']['received'] as $received)
                        <li class="mb-2 bb-1">
                            <a class="border-radius-md" href="javascript:;">
                            <div class="d-flex py-1">
                                <div class="my-auto">
                                <img src="@if($received->worker_photo == ''){{ env('APP_URL') }}/assets/img/user_avatar/default-photo.png @else {{ env('APP_URL') }}{{ Storage::url($received->worker_photo) }}@endif" class="avatar avatar-sm  me-3 ">
                                </div>
                                <div class="d-flex flex-column justify-content-center">
                                <h6 class="text-sm font-weight-normal mb-1">
                                    <span class="font-weight-bold">{{ $received->title }}</span>
                                </h6>
                                <p class="text-secondary text-sm lh-8 mb-0 mt-0">
                                  {{ $received->content }}
                                </p>

                                <p class="text-xs text-secondary mb-0">
                                  {{ __('content.sent_at')}}
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

                    <div id="notifications-received" role="tabpanel" aria-labelledby="notifications-received-tab" class="tab-pane fade py-3">
                      <ul class="px-2 py-3 ls-none border-bottom-list" id="order-notifications-received-list">

                        <!-- list of notifications -->
                        @foreach ($order['notifications']['sent'] as $sent)
                          <li class="mb-2 bb-1">
                              <a class="border-radius-md" href="javascript:;">
                              <div class="d-flex py-1">
                                  <div class="my-auto">
                                  <img src="@if($order['client_photo'] == ''){{ env('APP_URL') }}/assets/img/user_avatar/default-photo.png @else {{ env('APP_URL') }}{{ Storage::url($order['client_photo']) }}@endif" class="avatar avatar-sm  me-3 ">
                                  </div>
                                  <div class="d-flex flex-column justify-content-center">
                                  <h6 class="text-sm font-weight-normal mb-1">
                                      <span class="font-weight-bold">{{ $sent->title }}</span>
                                  </h6>
                                  <p class="text-secondary text-sm lh-8 mb-0 mt-0">
                                    {{ $sent->content }}
                                  </p>
                                  <p class="text-xs text-secondary mb-0">
                                    {{ __('content.received_at')}}
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

                  </div>
                </div>

              </div>

              @if((($order['accounter_id'] == auth()->user()->id) && auth()->user()->role == 'ADMINISTRATOR') || (($order['validator_id'] == auth()->user()->id) && auth()->user()->role == 'VALIDATOR') || auth()->user()->role == 'SUPER ADMINISTRATOR')
              <div class="card-footer p-3 mt-3">
                <h6 class="mb-0">{{ __('content.send_a_notification') }}</h6>

                <form action="{{ url('/management-area/send-order-notification-toclient/') . '/' . $order['order_id'] }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <div class="form-group mb-1">
                    <div class="input-group input-group-sm">
                      <span class="input-group-text" id="inputGroup-sizing-default">{{ __('content.title') }}</span>
                      <input type="text" name="title" id="notification-title" placeholder="Notification title" class="form-control" aria-label="{{ __('content.title') }}" aria-describedby="inputGroup-sizing-default" style="border-right: 1px solid; margin-right: 5px;" required>
                      <label for="additional_documents_input" id="additional_docs_label" class="text-md bg-transparent border-0 text-muted" data-bs-toggle="tooltip" data-bs-original-title="{{ __('content.upload') }}"><i class="fas fa-paperclip"></i> <input type="file" multiple="multiple" name="addional_documents" id="additional_documents_input" /> </label>
                    </div>
                  </div>

                  <div class="input-group mb-3">
                    <textarea name="message" class="form-control" placeholder="{{ __('content.messages.write_message') }}..." aria-describedby="button-addon2" id="notification-message" cols="30" rows="1" required></textarea>
                    <button class="btn bg-gradient-success mb-0 fs-6" type="submit" id="button-addon2"><i class="far fa-paper-plane"></i></button>
                  </div>

                  <div id="drop-files-here" class="d-flex justify-content-start"></div>

                </form>
              </div>
              @endif


            </div>
          </div>

        @if ($order['status'] == 'PROCESS FINISHED')
        <div class="col-md-12">
          <div class="card h-100 mt-3">
            <div class="card-header pb-0 p-3">
              <h6 class="mb-0">{{ __('content.upload_result_files') }} ({{ __('content.optional') }})</h6>
            </div>
            <div class="card-body p-3">
              <form action="{{ route('clients-service-orders.completed', $order['order_id'])}}" method="POST" role="form text-left" enctype="multipart/form-data">
                @csrf
                <div class="row">
                  <div class="col-12">
                    <div class="form-group">
                      <label for="final_file">{{ __('content.final_file') }} ({{ __('content.optional') }}) </label>
                        <div class="">
                          <input type="file" class="form-control" id="final_file" name="final_file" />
                        </div>
                      </div>
                    </div>
                </div>
                <div class="row">
                  <div class="col-12">
                  <div class="form-group">
                    <label for="comments">{{ __('content.comments') }} ({{ __('content.optional') }})</label>
                      <div class="@error('order.comments')border border-danger rounded-3 @enderror">
                        <textarea class="form-control" id="comments" rows="3" placeholder="" name="comments"></textarea>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="d-flex justify-content-end">
                  <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ __('content.order_completed') }}</button>
              </div>
              </form>
            </div>
          </div>
        </div>
      @endif
        </div>
      </div>
@endsection
