@extends('layouts.user_type.auth')
@section('content')

<style>
    [type="file"] + label {
        cursor: pointer;
        transition: all 0.2s;
    } 
</style>
<div>
    <div class="container-fluid py-4">
        <div class="card">
            <div class="card-header pb-0 px-3">
                <h5 class="mb-0">{{ __('content.create_service') }}</h5>
            </div>
            <div class="card-body pt-4 p-3">
                
                <form action="{{ route('services.store') }}" method="POST" role="form text-left" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                           <div class="row">
                                <div class="col-lg-7 col-sm-12">
                                    <h6 class="mb-0">{{ __('content.general_info') }}</h6>
                                </div>
                                <div class="col-lg-5 col-sm-12 d-flex justify-content-center">
                                    
                                        <div class="form-check me-3">
                                            <input type="radio" name="type_service" id="main-option-yes" class="form-check-input" value="MAIN" checked>
                                            <label for="main-option-yes" class="custom-control-label text-upp">{{ __('content.main') }}</label>
                                        </div>
                                        <div class="form-check">
                                            <input type="radio" name="type_service" id="additional-option-yes" class="form-check-input" value="ADDITIONAL">
                                            <label for="additional-option-yes" class="custom-control-label text-upp">{{ __('content.additional') }}</label>
                                        </div>
                                    
                                </div>
                           </div>
                           <hr class="mt-0 mb-3" />
                        </div>
                        
                        <div class="col-md-6 col-lg-8">
                            <div class="form-group">
                                <label for="product-name" class="form-control-label">{{ __('content.service_name') }}</label>
                                <div class="@error('product.name')border border-danger rounded-3 @enderror">
                                    <input class="form-control" value="" type="text" placeholder="{{ __('content.service_name') }}" id="product-name" name="name">
                                        @error('name')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                            <div class="form-group">
                                <label for="product-requireValidator" class="form-control-label">{{ __('content.does_it_require_validator') }}</label>
                                <div class="form-check">
                                    <input type="radio" name="requirevalidator-options" id="requirevalidator-option-yes" class="form-check-input" value="YES">
                                    <label for="requirevalidator-option-yes" class="custom-control-label text-upp">{{ __('content.yes_answer') }}</label>
                                </div>
                                <div class="form-check">
                                    <input type="radio" name="requirevalidator-options" id="requirevalidator-option-no" class="form-check-input" value="NO" checked>
                                    <label for="requirevalidator-option-no" class="custom-control-label text-upp">{{ __('content.no_answer') }}</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-8">
                            <div class="form-group">
                                <label for="product-description" class="form-control-label">{{ __('content.service_description') }}</label>
                                <div class="@error('product.description')border border-danger rounded-3 @enderror">
                                    <input class="form-control" value="" type="text" placeholder="{{ __('content.service_description') }}" id="product-description" name="description">
                                        @error('description')
                                            <p class="text-danger text-xs mt-2">{{ $message }}</p>
                                        @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-lg-4" style="visibility: hidden" id="validators-list-container">
                            <label for="product-validator" class="form-control-label">{{ __('content.select_a_validator') }}</label>
                            <select name="validator_required" id="product-validator" class="form-control">
                                @foreach ($validators as $validator)
                                    <option value="{{ $validator->id }}">{{ $validator->name . __(' ') . $validator->lastname }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row mt-1 mb-5">
                        <div class="col-md-9">
                            <div class="form-group">
                                <label for="product-indications">{{ __('content.indications_to_clients') }}</label>
                                <div class="@error('product.indications')border border-danger rounded-3 @enderror">
                                    <textarea class="form-control" id="product-indications" rows="3" placeholder="{{ __('content.messages.let_indications_to_clients') }}" name="indications"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group mb-1">
                                <label for="product-image">{{ __('content.service_image') }} ({{ __('content.optional') }})</label>
                                <div class="col-auto">
                                    <div class="avatar position-relative w-70 avatar-xl-80">
                                        <img src="{{ env('APP_URL') }}/assets/img/services/account-service-01.jpeg" id="service-image" alt="..." class="w-100 border-radius-lg shadow-sm">
                                        <input type="file" id="choose-image-service" name="image" accept="image/*" />
                                        <label for="choose-image-service" class="btn btn-sm btn-icon-only bg-gradient-light position-absolute bottom-0 end-0 mb-n2 me-n2" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('content.update') }} {{ __(' ') }} {{ __('content.image') }}">
                                            <i class="fa fa-pen top-0"></i>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4 mb-4">
                        <div class="col-12">
                            <div class="d-flex flex-row flex-nowrap justify-content-between">
                                <h6 class="mb-0 d-flex flex-row align-items-end"><span class="align-bottom">{{ __('content.required_fields') }}</span></h6>
                                <button type="button" id="addNewFieldsGroup" class="btn bg-gradient-success mb-0 pt-2 pb-2 ps-3 pe-3 fs-8"><i class="fas fa-plus text-white"></i> {{ __('content.add_field') }}</button>
                            </div>
                            <hr class="mt-1 mb-3" />
                        </div>
                    </div>
                    <div class="row mt-4 mb-4" id="productFieldGroupContainer">

                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn bg-gradient-dark btn-md mt-4 mb-4">{{ __('content.create') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="{{ env('APP_URL') }}/assets/js/input-generator.js"></script>
<script>
    //choose image 
    const chooseFile = document.getElementById("choose-image-service");
    const imgPreview = document.getElementById("service-image");
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

    //check if the service will have a validator or not
    const validator_yes = document.querySelector('#requirevalidator-option-yes');
    validator_yes.addEventListener("change", function(){
        if(this.checked){
            document.getElementById('validators-list-container').style.visibility = "visible";
        } 
    });
    const validator_no = document.querySelector('#requirevalidator-option-no');
    validator_no.addEventListener("change", function(){
        if(this.checked){
            document.getElementById('validators-list-container').style.visibility = "hidden";
        }
    });
</script>

@endsection