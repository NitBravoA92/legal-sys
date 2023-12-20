@extends('layouts.user_type.auth')
@section('content')

<style>

#management-notes .card-wrap {
  width: 90%;
  margin: 0 auto;
  border-radius: 20px;
  overflow: hidden;
  color: #ffffff !important;
  box-shadow: rgba(0, 0, 0, 0.19) 0px 10px 20px, rgba(0, 0, 0, 0.23) 0px 6px 6px;
  cursor: pointer;
  transition: all .2s ease-in-out;
}
#management-notes .card-wrap:hover{
  transform: scale(1.05);
}
#management-notes .card-header{
  height: 70px;
  width: 100%;
  background-image: linear-gradient(310deg,#2152ff,#21d4fd);
  border-radius: 100% 0% 100% 0% / 0% 50% 50% 100%;
  display: grid;
  place-items: center;
}
#management-notes .card-content{
  display: flex;
  flex-direction: column;
  align-items: center;
  width: 80%;
  margin: 0 auto;
}
#management-notes .card-content p{
  line-height: 1;
}
#management-notes .card-title{
  text-align: center;
  text-transform: uppercase;
  font-size: 16px;
  color: #ffffff !important;
  padding-bottom: 10px;
  margin-bottom: 5px;
}
#management-notes .card-text{
  text-align: center;
  font-size: 12px;
  margin-bottom: 5px;
  padding-top: 10px;
  padding-bottom: 10px;
}
#management-notes .card-content small{
  text-align: center;
  font-size: 10px;
}

</style>
<div>
    <div class="container-fluid">
        <div class="page-header min-height-200 border-radius-xl mt-4" style="background-image: url('{{ env('APP_URL') }}/assets/img/curved-images/white-curved.jpeg'); background-position-y: 50%;">
            <span class="mask bg-gradient-info opacity-6"></span>
        </div>
        <div class="card card-body blur shadow-blur mx-4 mt-n6">
            <div class="row gx-4">
                <div class="col-auto">
                    <div class="avatar avatar-xl position-relative">
                        <img src="@if ($client_data['client']->photo == ''){{ env('APP_URL') }}/assets/img/user_avatar/default-photo.png @else {{ env('APP_URL') }}{{ \Storage::url($client_data['client']->photo) }}@endif" alt="..." class="w-100 border-radius-lg shadow-sm">
                    </div>
                </div>
                <div class="col-auto my-auto">
                    <div class="h-100">
                        <h5 class="mb-1">
                            {{ $client_data['client']->name . ' ' . $client_data['client']->lastname }}
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

    <!-- all details -->
    <div class="container-fluid py-4">
        <div class="row">

            <div class="col-12 col-lg-6 mb-2">
            <div class="card h-100 client-cards-fx">
              <div class="card-header pb-0 p-3">
                <div class="row">
                  <div class="col-md-8 d-flex align-items-center">
                    <h6 class="mb-0">{{ __('content.users.profile_information') }}</h6>
                  </div>
                  <div class="col-md-4 text-end">
                    <a href="{{ route('clients.edit', $client_data['client']->id_client) }}" class="px-2 btn btn-sm bg-gradient-dark text-white" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('content.users.edit_client') }}">
                      <i class="fas fa-user-edit text-xs"></i>
                    </a>
                  </div>
                </div>
              </div>
              <div class="card-body p-3">
                <ul class="list-group">
                    <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">{{ __('content.full_name') }}:</strong> &nbsp; {{ $client_data['client']->name . ' ' . $client_data['client']->lastname }}</li>
                    <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">{{ __('content.phone_number') }}:</strong> &nbsp; {{ $client_data['client']->phone }}</li>
                    <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">{{ __('content.alt_phone_num') }}:</strong> &nbsp; {{ $client_data['client']->alt_phone }}</li>
                    <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">{{ __('content.email') }}:</strong> &nbsp; {{ $client_data['client']->email }} </li>
                    <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">{{ __('content.location') }}:</strong> &nbsp; {{ $client_data['client']->location }} </li>
                    <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">{{ __('content.creation_date') }}:</strong> &nbsp; {{ $client_data['client']->userCreated }} </li>
                </ul>
                <hr class="horizontal gray-light my-2">
                <h6>{{ __('content.about_this_client') }}:</h6>
                <p class="text-sm">
                    {{ $client_data['client']->about_me }}
                </p>
              </div>
            </div>
          </div>

          <div class="col-lg-6 col-md-12 mb-2">
            <div class="card mb-4 client-cards-fx">
              <div class="card-header pb-0 p-3">
                <h6 class="mb-1">{{ __('content.management_notes') }}</h6>
                <p class="text-sm"></p>
              </div>
              <div class="card-body p-3">
                <div class="row d-flex align-items-center">
                  <!-- notes items -->
                  @foreach ($client_data['notes'] as $note)
                    <div id="management-notes" class="col-lg-12 mb-3">
                      <div class="card-wrap bg-gradient-dark">
                        <div class="card-header one">
                          <h4 class="card-title">{{ $note->title }}</h4>
                        </div>
                        <div class="card-content">
                          <p class="card-text">{{ $note->description }}</p>
                          <small class="mb-0">{{ __('content.created_by') }} {{ $note->worker_name . ' ' . $note->worker_lastname }} </small>
                          <small class="mb-0">{{ __('content.created_at') }} {{ $note->updated_at }}</small>
                        </div>
                      </div>
                    </div>
                  @endforeach
                  <!-- notes -->
                </div>
              </div>
            </div>
          </div>

          <div class="col-12 col-lg-6 mb-2">
            <div class="card h-100 client-cards-fx border-bottom-list">
              <div class="card-header pb-0 p-3">
                <h6 class="mb-0">{{ __('content.all_notifications') }}</h6>
              </div>
              <div class="card-body p-3">
                <ul class="list-group">

                  @foreach ($client_data['notifications'] as $notification)
                    <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2 bb-1">
                      <div class="avatar me-3">
                        <img src="@if($notification['maker']['photo'] != ''){{ env('APP_URL') }}{{ Storage::url($notification['maker']['photo']) }}@else{{ env('APP_URL') }}/assets/img/user_avatar/default-photo.png @endif" alt="kal" class="border-radius-lg shadow">
                      </div>
                      <div class="d-flex align-items-start flex-column justify-content-center">
                        <h6 class="mb-0 text-sm">{{ $notification['title'] }}</h6>
                        <p class="mb-0 text-xs lh-8">{{ $notification['content'] }}</p>
                        <small class="mt-1 text-xs">
                          {{ __('content.sent_at')}}
                           <i class="fa fa-calendar me-1"></i> {{ $notification['updated_at'] }}
                        </small>
                        <small class="mt-1 text-xs">{{ __('content.by') }} {{ $notification['maker']['full_name'] }}</small>
                        <small class="mt-1 text-xs">{{ __('content.linked_to') }}: {{ __('content.order') }} #{{ $notification['order_id'] }} {{ $notification['service'] }}</small>
                      </div>
                    </li>
                  @endforeach

                </ul>
              </div>
            </div>
          </div>

          <div class="col-12 col-lg-6 mb-2">
            <div class="card h-100 client-cards-fx border-bottom-list">
              <div class="card-header pb-0 p-3">
                <h6 class="mb-0">{{ __('content.document_history') }}</h6>
              </div>
              <div class="card-body p-3">
                <ul class="list-group" id="client-alldocuments-list">
                    @foreach ($client_data['documents'] as $doc)
                      <li class="list-group-item border-0 bb-1 d-flex justify-content-between ps-0 mb-2">
                        <div class="d-flex flex-column">
                          <h6 class="mb-1 text-dark font-weight-bold text-sm">{{ $doc->name }}</h6>
                          <span class="text-xs">{{ __('content.uploaded_at') }} {{ $doc->updated_at }} </span>
                        </div>
                        <div class="d-flex align-items-center text-sm">
                          <a href="{{ route('documents.download-management', $doc->id) }}" class="btn btn-link text-dark text-xs mb-0 px-0 ms-4"><i class="fas fa-file-download text-lg me-1"></i> {{ __('content.download') }}</a>
                        </div>
                      </li>
                    @endforeach
                  </ul>
              </div>
            </div>
          </div>

          <div class="col-lg-12 col-md-12 mt-4">
            <div class="card mb-4 client-cards-fx">
              <div class="card-header pb-0 p-3">
                <h6 class="mb-1">{{ __('content.contracted_services') }}</h6>
                <p class="text-sm">{{ __('content.messages.all_contracted_services_by_this_client') }}</p>
              </div>
              <div class="card-body p-3">
                <div class="row">

                  <div id="client-service-orders" class="owl-carousel owl-theme">
                    <!-- service orders items -->
                    @foreach ($client_data['orders'] as $order)
                        <div class="card card-blog card-plain">
                          <div class="position-relative">
                            <a class="d-block shadow-xl border-radius-xl">
                              <img src="@if ($order->image == ''){{ env('APP_URL') }}/assets/img/services/account-service-01.jpeg @else {{ env('APP_URL') }}{{ \Storage::url($order->image) }}@endif" class="img-fluid shadow border-radius-xl" alt="Service image" id="services-image">
                            </a>
                          </div>
                          <div class="card-body px-1 pb-0">
                            <p class="text-gradient text-dark mb-2 text-sm">{{ __('content.order') }} #{{ $order->orderID }}</p>
                            <a href="javascript:;">
                              <h5>
                                {{ $order->name }}
                              </h5>
                            </a>
                            <p class="mb-4 text-sm">
                                {{ $order->description }}
                            </p>
                            <div class="d-flex align-items-center justify-content-between">
                              <a href="{{ url('/management-area/client-service-order/details/') . '/' . $order->orderID }}" class="btn btn-outline-info btn-sm mb-0">{{ __('content.view_details') }}</a>
                            </div>
                          </div>
                        </div>
                    @endforeach
                  <!-- service orders -->
                  </div> <!-- end of carousel -->


                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
</div>
@endsection










