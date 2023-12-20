@extends('layouts.user_type.auth')
@section('content')

<style>
.w-32 {
    width: 32px!important;
    height: 32px!important;
    font-size: .85em;
}
.tl-item .avatar {
    z-index: 2;
}

.circle {
    border-radius: 500px;
}


.tl-item {
    border-radius: 3px;
    position: relative;
    display: -ms-flexbox;
    display: flex
}

.tl-item>* {
    padding: 10px
}

.tl-item .avatar {
    z-index: 2
}

.tl-item:last-child .tl-dot:after {
    display: none
}

.tl-item.active .tl-dot:before {
    border-color: #448bff;
    box-shadow: 0 0 0 4px rgba(68, 139, 255, .2)
}

.tl-item:last-child .tl-dot:after {
    display: none
}

.tl-item.active .tl-dot:before {
    border-color: #448bff;
    box-shadow: 0 0 0 4px rgba(68, 139, 255, .2)
}

.tl-dot {
    position: relative;
    border-color: rgba(160, 175, 185, .15)
}

.tl-dot:after,
.tl-dot:before {
    content: '';
    position: absolute;
    border-color: inherit;
    border-width: 2px;
    border-style: solid;
    border-radius: 50%;
    width: 10px;
    height: 10px;
    top: 15px;
    left: 50%;
    transform: translateX(-50%)
}

.tl-dot:after {
    width: 0;
    height: auto;
    top: 25px;
    bottom: -15px;
    border-right-width: 0;
    border-top-width: 0;
    border-bottom-width: 0;
    border-radius: 0
}

tl-item.active .tl-dot:before {
    border-color: #448bff;
    box-shadow: 0 0 0 4px rgba(68, 139, 255, .2)
}

.tl-dot {
    position: relative;
    border-color: rgba(160, 175, 185, .15)
}

.tl-dot:after,
.tl-dot:before {
    content: '';
    position: absolute;
    border-color: linear-gradient(310deg,#344767,#344767);
    border-width: 2px;
    border-style: solid;
    border-radius: 50%;
    width: 10px;
    height: 10px;
    top: 15px;
    left: 50%;
    transform: translateX(-50%)
}

.tl-dot:after {
    width: 0;
    height: auto;
    top: 25px;
    bottom: -15px;
    border-right-width: 0;
    border-top-width: 0;
    border-bottom-width: 0;
    border-radius: 0
}

.tl-content p:last-child {
    margin-bottom: 0
}

.tl-date {
    font-size: .85em;
    line-height: 0.8 !important;
}

.timeline .avatar {
    position: relative;
    line-height: 1;
    border-radius: 500px;
    white-space: nowrap;
    font-weight: 700;
    border-radius: 100%;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-pack: center;
    justify-content: center;
    -ms-flex-align: center;
    align-items: center;
    -ms-flex-negative: 0;
    flex-shrink: 0;
    border-radius: 500px;
    box-shadow: 0 5px 10px 0 rgba(50,50,50,.15);
}

.timeline:before {
    border-right: 0px !important;
    height: 0% !important;
    left: 0 !important;
}

</style>

    <div class="container-fluid py-4">

        <div class="row">
            <div class="col-lg-12">
                <h5>All Notifications</h5>
                <div class="timeline p-4 block mb-4 card min-height-card">

                    @foreach ($notifications as $item)
                        {!!html_entity_decode($item->data_short)!!}
                    @endforeach

                </div>
            </div>

        </div>

    </div>
@endsection
