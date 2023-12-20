@extends('layouts.user_type.auth')

@section('content')

<style>
    [type="file"] + label {
        cursor: pointer;
        transition: all 0.2s;
    } 
</style>

<div>
<form @if(auth()->user()->role == 'CUSTOMER') action="/client-area/client-profile" @else action="/management-area/user-profile" @endif method="POST" role="form text-left" enctype="multipart/form-data">
    @csrf
    <div class="container-fluid">
        <div class="page-header min-height-200 border-radius-xl mt-4" style="background-image: url('../assets/img/curved-images/white-curved.jpeg'); background-position-y: 50%;">
            <span class="mask bg-gradient-info opacity-6"></span>
        </div>
        <div class="card card-body blur shadow-blur mx-4 mt-n6">
            <div class="row gx-4">
                <div class="col-auto">
                    
                    <div class="avatar avatar-xl position-relative">
                        <img @if(auth()->user()->photo == '') src="{{ env('APP_URL') }}/assets/img/user_avatar/default-photo.png" @else src="{{ asset(\Storage::url(auth()->user()->photo)) }}" @endif alt="Profile Image" class="w-100 border-radius-lg shadow-sm" id="profile-image">
                        <input type="file" id="choose-image-profile" name="photo"  />
                        <label for="choose-image-profile" class="btn btn-sm btn-icon-only bg-gradient-light position-absolute bottom-0 end-0 mb-n2 me-n2" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('content.update') }} {{ __(' ') }} {{ __('content.image') }}">
                            <i class="fa fa-pen top-0"></i>
                        </label>
                    </div>
                </div>
                
                <div class="col-auto my-auto">
                    <div class="h-100">
                        <h5 class="mb-1">
                            {{ auth()->user()->name . __(' ') . auth()->user()->lastname }}
                        </h5>
                        <p class="mb-0 font-weight-bold text-sm">
                            {{ auth()->user()->role }}
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
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header pb-0 px-3">
                <h6 class="mb-0">{{ __('content.users.profile_information') }}</h6>
            </div>
            <div class="card-body pt-4 p-3">
            
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user-name" class="form-control-label">{{ __('content.first_name') }}</label>
                                <div class="@error('user.name')border border-danger rounded-3 @enderror">
                                    <input class="form-control" value="{{ auth()->user()->name }}" type="text" placeholder="{{ __('content.first_name') }}" id="user-name" name="name">
                                        @error('name')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user-lastname" class="form-control-label">{{ __('content.last_name') }}</label>
                                <div class="@error('user.lastname')border border-danger rounded-3 @enderror">
                                    <input class="form-control" value="{{ auth()->user()->lastname }}" type="text" placeholder="{{ __('content.last_name') }}" id="user-lastname" name="lastname">
                                        @error('lastname')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user-phone" class="form-control-label">{{ __('content.phone_number') }}</label>
                                <div class="@error('user.phone')border border-danger rounded-3 @enderror">
                                    <input class="form-control" type="text" placeholder="40770888444" id="user-phone" name="phone" value="{{ auth()->user()->phone }}">
                                        @error('phone')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user-alt_phone" class="form-control-label">{{ __('content.alt_phone_num') }}</label>
                                <div class="@error('user.alt_phone')border border-danger rounded-3 @enderror">
                                    <input class="form-control" type="text" placeholder="40770888444" id="user-alt_phone" name="alt_phone" value="{{ auth()->user()->alt_phone }}">
                                        @error('alt_phone')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user-email" class="form-control-label">{{ __('content.email') }}</label>
                                <div class="@error('email')border border-danger rounded-3 @enderror">
                                    <input class="form-control" value="{{ auth()->user()->email }}" type="email" placeholder="@example.com" id="user-email" name="email">
                                        @error('email')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user.location" class="form-control-label">{{ __('content.location') }}</label>
                                <div class="@error('user.location') border border-danger rounded-3 @enderror">
                                    <input class="form-control" type="text" placeholder="{{ __('content.location') }}" id="location" name="location" value="{{ auth()->user()->location }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="about">{{ __('content.about_me') }}</label>
                        <div class="@error('user.about')border border-danger rounded-3 @enderror">
                            <textarea class="form-control" id="about" rows="3" placeholder="{{ __('content.messages.say_something_bout_you') }}" name="about_me">{{ auth()->user()->about_me }}</textarea>
                        </div>
                    </div>

                    <input type="hidden" name="role" value="{{ auth()->user()->role }}">
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ __('content.save_changes') }}</button>
                    </div>
                

            </div>
        </div>
    </div>

</form>
</div>
<script>
        const chooseFile = document.getElementById("choose-image-profile");
        const imgPreview = document.getElementById("profile-image");
        chooseFile.addEventListener("change", function () {
            getImgData();
        });
        function getImgData() {
            const files = chooseFile.files[0];
            if (files) {
                const fileReader = new FileReader();
                fileReader.readAsDataURL(files);
                fileReader.addEventListener("load", function () {
                imgPreview.setAttribute("src", this.result);
                });    
            }
        }
</script>
@endsection