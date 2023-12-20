<!--
=========================================================
* Legal Sys - v1.0.0
=========================================================

* Copyright 2022. Modern Solutions Group (https://modernsolutionsgroup.net/)
* Licensed under MIT
* Developed by Nitcelis Bravo
=========================================================
* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->

<!DOCTYPE html>
<html @if (Auth::check())
lang="{{ __( session()->get('language') ) }}"
@else
lang="es"
@endif  >
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <link rel="apple-touch-icon" sizes="76x76"
    @if (Auth::check())
          @if(session('logo') == '') href="{{ env('APP_URL') }}/assets/img/logos/system-logo.png" @else href="{{ env('APP_URL') }}{{ session('logo') }}" @endif
    @else
          @if($setting->app_logo == '') href="{{ env('APP_URL') }}/assets/img/logos/system-logo.png" @else href="{{ env('APP_URL') }}{{ \Storage::url($setting->app_logo) }}" @endif
    @endif>

  <link rel="icon" type="image/png"
  @if (Auth::check())
          @if(session('logo') == '') href="{{ env('APP_URL') }}/assets/img/logos/system-logo.png" @else href="{{ env('APP_URL') }}{{ session('logo') }}" @endif
    @else
          @if($setting->app_logo == '') href="{{ env('APP_URL') }}/assets/img/logos/system-logo.png" @else href="{{ env('APP_URL') }}{{ \Storage::url($setting->app_logo) }}" @endif
    @endif>

  <title>
    @if (Auth::check())
        {{ session()->get('setting')->app_name }}
    @else
      {{ $setting->app_name }}
    @endif
  </title>

  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />

  <!-- Nucleo Icons -->
  <link href="{{ env('APP_URL') }}/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="{{ env('APP_URL') }}/assets/css/nucleo-svg.css" rel="stylesheet" />

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{ env('APP_URL') }}/assets/css/fontawesome/css/all.css">
  <link rel="stylesheet" href="{{ env('APP_URL') }}/assets/css/fontawesome/css/brands.css">
  <link rel="stylesheet" href="{{ env('APP_URL') }}/assets/css/fontawesome/css/fontawesome.css">
  <link rel="stylesheet" href="{{ env('APP_URL') }}/assets/css/fontawesome/css/regular.css">
  <link rel="stylesheet" href="{{ env('APP_URL') }}/assets/css/fontawesome/css/solid.css">

  <!-- owl carousel styles -->
  <link rel="stylesheet" href="{{ env('APP_URL') }}/assets/css/owl.carousel.min.css">
  <link rel="stylesheet" href="{{ env('APP_URL') }}/assets/css/owl.theme.default.min.css">

  <!-- animations -->
  <link rel="stylesheet" href="{{ env('APP_URL') }}/assets/css/animate.min.css">

  <link rel="stylesheet" href="{{ env('APP_URL') }}/assets/css/bootstrap.min.css">
  <link rel="stylesheet" href="{{ env('APP_URL') }}/assets/css/dataTables.bootstrap5.min.css">

  <link rel="stylesheet" href="{{ env('APP_URL') }}/assets/css/sweetalert2.min.css">

  <!-- CSS Files -->
  <link id="pagestyle" href="{{ env('APP_URL') }}/assets/css/soft-ui-dashboard.css?v=1.0.3" rel="stylesheet" />
  <link id="pagestyle" href="{{ env('APP_URL') }}/css/app.css" rel="stylesheet" />

  @livewireStyles
</head>

<body class="g-sidenav-show bg-gray-100">
  @auth
    @yield('auth')
  @endauth

  @guest
    @yield('guest')
  @endguest

  <div class="toast-container bottom-0 end-0 p-3">
    <div id="successAlert" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body">
          @if(session('success')) {{ session('success') }} @endif
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>

    <div id="errorAlert" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body">
          @if(session('error')) {{ session('error') }} @endif
        </div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>

  </div>

    <!--   Core JS Files   -->
  <script src="{{ env('APP_URL') }}/assets/js/plugins/jquery.min.js"></script>
  <script src="{{ env('APP_URL') }}/assets/js/core/popper.min.js"></script>
  <script src="{{ env('APP_URL') }}/assets/js/core/bootstrap.min.js"></script>
  <script src="{{ env('APP_URL') }}/assets/js/core/jquery.dataTables.min.js"></script>
  <script src="{{ env('APP_URL') }}/assets/js/core/dataTables.bootstrap5.min.js"></script>

  <script src="{{ env('APP_URL') }}/assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="{{ env('APP_URL') }}/assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script src="{{ env('APP_URL') }}/assets/js/plugins/fullcalendar.min.js"></script>
  <script src="{{ env('APP_URL') }}/assets/js/plugins/chartjs.min.js"></script>
  <script src="{{ env('APP_URL') }}/assets/js/plugins/owl.carousel.min.js"></script>

  @if(session('error'))
    <script>
      const toastAlert = document.getElementById('errorAlert');
      const toastDanger = new bootstrap.Toast(toastAlert);
      toastDanger.show();
    </script>
  @endif

  @if(session('success'))
    <script>
      const toastAlert = document.getElementById('successAlert');
      const toastSuccess = new bootstrap.Toast(toastAlert);
      toastSuccess.show();
    </script>
  @endif

  @stack('rtl')
  @stack('dashboard')
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>

  <script src="{{ env('APP_URL') }}/assets/js/buttons.js"></script>
  <script src="{{ env('APP_URL') }}/assets/js/soft-ui-dashboard.min.js?v=1.0.3"></script>
  <script src="{{ env('APP_URL') }}/assets/js/plugins/sweetalert2.min.js"></script>
  <script src="{{ env('APP_URL') }}/assets/js/jquery.MultiFile.js" type="text/javascript" language="javascript"></script>

  <script>
    $(document).ready(function(){
      function* fileIndexMaker(){
          let index = 0;
          while(true)
            yield index++;
      }

      let index_file = fileIndexMaker();
      $('#additional_documents_input').on('change', function(){
        let files = $(this)[0].files;
        let index = index_file.next().value;
        for(let i = 0; i < files.length; i++) {
          $(this).clone().hide().attr('name', 'addic_doc_final_' + index).attr('id', 'addic_docs_final_' + index).insertAfter($("#drop-files-here"));
          $('<input type="hidden" name="_index_tokens_[]" value="' + index + '" />').insertAfter($("#drop-files-here"));
        }
      });

    });
  </script>

  <script>
    $(document).ready(function(){
      $("#main-services").owlCarousel({
        margin: 10,
        responsiveClass:true,
        responsive: {
          0:{
            items:1,
            nav:true
          },
          600:{
            items:1,
            nav:true
          },
          1000:{
            items:1,
            nav:true
          }
        }
      });
      $("#additional-services").owlCarousel({
        margin: 20,
        responsiveClass:true,
        responsive: {
          0:{
            items:1,
            nav:true
          },
          600:{
            items:2,
            nav:true
          },
          1000:{
            items:3,
            nav:true
          }
        }
      });

      $("#client-service-orders").owlCarousel({
        margin: 20,
        responsiveClass:true,
        responsive: {
          0:{
            items:1,
            nav:true
          },
          600:{
            items:2,
            nav:true
          },
          1000:{
            items:3,
            nav:true
          },
          1200:{
            items:4,
            nav:true
          }
        }
      });

    });
  </script>

<script type="text/javascript">
  $(document).ready(function () {

    let lang = `{{ session()->get('language') }}`;
    let languages_translate = {
      "es": {
            "emptyTable": "No hay datos disponibles en la tabla",
            "lengthMenu": "Mostrar _MENU_ registros por pagina",
            "zeroRecords": "No se encontraron resultados",
            "info": "Mostrando pagina _PAGE_ de _PAGES_",
            "infoEmpty": "No hay registros disponibles",
            "infoFiltered": "(filtrado de _MAX_ registros totales)",
            "loadingRecords": "Cargando...",
            "search": "Buscar:",
            "zeroRecords": "No se encontraron coincidencias",
            "paginate": {
                "first":      "Primero",
                "last":       "Ultimo",
                "next":       "Siguiente",
                "previous":   "Anterior"
            },
            "aria": {
                "sortAscending":  ": activado para ordenar columna de manera ascendente",
                "sortDescending": ": activado para ordenar columna de manera descendente"
            }
        },
        "en": {
            "emptyTable": "No data available in table",
            "lengthMenu": "Display _MENU_ records per page",
            "zeroRecords": "Nothing found",
            "info": "Showing page _PAGE_ of _PAGES_",
            "infoEmpty": "No records available",
            "infoFiltered": "(filtered from _MAX_ total records)",
            "loadingRecords": "Loading...",
            "search": "Search:",
            "zeroRecords": "No matching records found",
            "paginate": {
                "first":      "First",
                "last":       "Last",
                "next":       "Next",
                "previous":   "Previous"
            },
            "aria": {
                "sortAscending":  ": activate to sort column ascending",
                "sortDescending": ": activate to sort column descending"
            }
        }
      };

    //users
      $('#user-table').DataTable({"language": languages_translate[lang]});
    //clients
      $('#clients-table').DataTable({"language": languages_translate[lang]});
    //services
      $('#services-table').DataTable({"language": languages_translate[lang]});
    //myorders
      $('#myorders-table').DataTable({"language": languages_translate[lang]});
    //client_orders
      $("#client_orders-table").DataTable({"language": languages_translate[lang]});
    //client_orders_validate
      $("#client_orders_validate-table").DataTable({"language": languages_translate[lang]});
    //mywork_orders-table
      $("#mywork_orders-table").DataTable({"language": languages_translate[lang]});
    //document_history
      $('#docment_history-table').DataTable({"language": languages_translate[lang]});
    //document_repository
      $('#docment_repository-table').DataTable({"language": languages_translate[lang]});
    //

    let previews = $('.dataTables_paginate .previous .page-link');
    let nexts = $('.dataTables_paginate .next .page-link');
    $.each(previews, function(index, element){
      element.innerHTML = '<i class="fas fa-chevron-left"></i>';
    });
    $.each(nexts, function(index, element){
      element.innerHTML = '<i class="fas fa-chevron-right"></i>';
    });
  });
</script>

<script>
  $(document).ready(function () {

    // alert to prevent deleting data
    $('.btn-delete-data').click(function(event) {
      var form = $(this).closest("form");
      event.preventDefault();
      Swal.fire({
        title: `{{ __('content.messages.delete_record_questiong') }}`,
        text: `{{ __('content.messages.delete_record_warning') }}`,
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: `{{ __('content.messages.delete_confirmed_button') }}`,
        cancelButtonText: `{{ __('content.messages.cancel_button') }}`
      }).then((willDelete) => {
        if(willDelete.isConfirmed){
          form.submit();
        }
      });

    });

    // alert to prevent updating data
    $('.btn-alert-update-status').click(function(event) {
      var form = $(this).closest("form");
      event.preventDefault();
      Swal.fire({
        title: `{{ __('content.messages.update_record_questiong') }}`,
        text: "",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: `{{ __('content.messages.update_confirmed_button') }}`, //'Yes, Change it!'
        cancelButtonText: `{{ __('content.messages.cancel_button') }}`
      }).then((willUpdate) => {
        if(willUpdate.isConfirmed){
          form.submit();
        }
      });
    });

    // alert to prevent blocking user
    $('.btn-alert-block-user').click(function(event) {
      var form = $(this).closest("form");
      event.preventDefault();
      Swal.fire({
        title: `{{ __('content.messages.block_user_questiong') }}`,
        text: "",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: `{{ __('content.messages.block_confirmed_button') }}`, //'Yes, Block it!'
        cancelButtonText: `{{ __('content.messages.cancel_button') }}`
      }).then((willUpdate) => {
        if(willUpdate.isConfirmed){
          form.submit();
        }
      });
    });

    // alert to prevent active the user
    $('.btn-alert-active-user').click(function(event){
      var form = $(this).closest("form");
      event.preventDefault();
      Swal.fire({
        title: `{{ __('content.messages.active_user_questiong') }}`,
        text: "",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: `{{ __('content.messages.active_confirmed_button') }}`, //'Yes, Active it!'
        cancelButtonText: `{{ __('content.messages.cancel_button') }}`
      }).then((willUpdate) => {
        if(willUpdate.isConfirmed){
          form.submit();
        }
      });
    });

  });
</script>

@if (session()->get('notifications_unred') > 0)
<script>
const notif_count = parseInt(`{{ session()->get('notifications_unred') }}`);
const notif_items = document.querySelectorAll('#user-notifications-navlist .dropdown-item');
notif_items.forEach((element, index) => {
  if( (index+1) <= notif_count){
  element.classList.add('active', 'mb-1');
  }
});
</script>

<script>
  $(document).ready(function(){
    $("#dropdownMenuButton_notif").click(function(){
      var dropdown_state = $("#dropdownMenuButton_notif").attr('aria-expanded');
      if(dropdown_state == "false")
      {
        //ajax request to update the notifications state
        $.ajax(
          {
            type: "GET",
            @if (explode('/', Request::path())[0] == 'client-area' )
            url: `{{ route('notifications.client-update') }}`,
            @else
            url: `{{ route('notifications.management-update') }}`,
            @endif
            dataType: "json",
            data: {status: 'read'},
            success: function(data){
              let notif_items = document.querySelectorAll('#user-notifications-navlist .dropdown-item');
              notif_items.forEach((element, index) => {
                element.classList.remove('active', 'mb-1');
              });
              let notif_counter = document.querySelector('#notif_counter').style.display = 'none';
            },
            error: function(data){
              console.log(data)
            }
          });
      }
    });
  });
</script>
@else
<script>
  const notif_items = document.querySelectorAll('#user-notifications-navlist .dropdown-item');
  notif_items.forEach((element, index) => {
    element.classList.remove('active', 'mb-1');
  });
</script>
@endif

@livewireScripts
</body>
</html>
