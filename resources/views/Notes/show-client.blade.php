@extends('layouts.user_type.auth')
@section('content')

<div>
    <div class="container-fluid">
        <div class="page-header min-height-200 border-radius-xl mt-4" style="background-image: url('{{ env('APP_URL') }}/assets/img/curved-images/white-curved.jpeg'); background-position-y: 50%;">
            <span class="mask bg-gradient-info opacity-6"></span>
        </div>
        <div class="card card-body blur shadow-blur mx-4 mt-n6">
            <div class="row gx-4">
                <div class="col-auto">
                    <div class="avatar avatar-xl position-relative">
                        <img src="@if ($client_data['client']->photo == ''){{ env('APP_URL') }}/assets/img/user_avatar/default-photo.png @else {{ env('APP_URL') }}{{ Storage::url($client_data['client']->photo) }}@endif" alt="..." class="w-100 border-radius-lg shadow-sm">
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

            <div class="col-12 col-xl-4 mb-4">
            <div class="card h-100">
              <div class="card-header pb-0 p-3">
                <div class="row">
                  <div class="col-md-8 d-flex align-items-center">
                    <h6 class="mb-0">{{ __('content.users.profile_information') }}</h6>
                  </div>
                  <div class="col-md-4 text-end">
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
                    <li class="list-group-item border-0 ps-0 pt-0 text-sm"><strong class="text-dark">{{ __('content.register_date') }}:</strong> &nbsp; {{ $client_data['client']->created_at }} </li>
                </ul>
                <hr class="horizontal gray-light my-2">
                <h6>{{ __('content.about_client') }}:</h6>
                <p class="text-sm">
                    {{ $client_data['client']->about_me }}
                </p>
              </div>
            </div>
          </div>

          <div class="col-12 col-xl-8">
            <div class="card h-100">
              <div class="card-header pb-0 p-3">
                <h6 class="mb-0">{{ __('content.management_notes') }}</h6>
              </div>
              <div class="card-body p-3">
                <ul class="list-group">
                  @foreach ($client_data['notes'] as $note)
                    <!-- items -->
                    <li class="list-group-item border-0 d-flex align-items-center px-0 mb-2">
                        <div class="avatar me-3">
                            <img src="{{ env('APP_URL') }}/assets/img/icons/notes.png" class="avatar avatar-sm me-3">
                        </div>

                        <div class="w-80 d-flex align-items-start flex-column justify-content-center">
                          <h6 class="mb-0 text-sm">{{ $note->title }}</h6>
                          <p class="mb-0 text-xs">{{ $note->description }}</p>
                          <small class="mt-1 text-xs"> <i class="fa fa-calendar me-1"></i> {{ $note->created_at }}</small>
                        </div>

                        <div class="w-10 d-flex align-items-center justify-content-end">
                          <form action="{{ route('clients-management.destroy', $note->id) }}" method="POST">
                            @csrf
                            {{ method_field('DELETE') }}
                            <button type="submit" class="btn-delete-data btn btn-sm bg-gradient-danger text-white mb-0 mx-1 px-2" data-bs-toggle="tooltip" data-bs-original-title="{{ __('content.delete') }}" id="note_option_delete">
                              <i class="far fa-trash-alt text-xs"></i>
                            </button>
                          </form>
                        </div>

                    </li>
                    <!-- -->
                  @endforeach
                </ul>
              </div>

              <!-- form to create notes -->
              <div class="card-footer p-3 mt-3">
                <div class="row">
                  <div class="col-md-12">
                    <h6 class="mb-0">{{ __('content.create_management_note') }}</h6>
                    <form action="{{ route('clients-management.store', $client_data['client']->id_client) }}" method="POST">
                      @csrf
                      <div class="form-group mb-1">
                        <div class="input-group input-group-sm">
                          <span class="input-group-text" id="inputGroup-sizing-default">{{ __('content.title') }}</span>
                          <input type="text" name="title" id="note-title" placeholder="{{ __('content.title') }}" class="form-control" aria-label="Title" aria-describedby="inputGroup-sizing-default" required>
                          @error('title')
                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                          @enderror
                        </div>
                      </div>
                      <div class="input-group mb-3">
                        <textarea name="description" class="form-control" placeholder="{{ __('content.messages.write_note_details') }}..." aria-describedby="button-addon2" id="note-description" cols="30" rows="1" required></textarea>
                        <button class="btn bg-gradient-dark mb-0 fs-6" type="submit" id="button-addon2"><i class="far fa-paper-plane"></i></button>
                        @error('description')
                          <p class="text-danger text-xs mt-2">{{ $message }}</p>
                        @enderror
                      </div>
                    </form>
                  </div>
                </div>
              </div>
              <!-- end of notes form -->

            </div>
          </div>

        </div>

      </div>
</div>
@endsection
