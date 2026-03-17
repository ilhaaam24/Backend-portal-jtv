<meta charset="utf-8" />
<meta
  name="viewport"
  content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
<title>CmsPortaljtv.com</title>
<meta name="description" content="" />
<meta name="csrf-token" content="{{ csrf_token() }}" />

<!-- Favicon -->
<link rel="icon" type="image/x-icon" href="{{ asset('') }}assets/admin/images/favicon.ico" />
<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

<!-- Icons -->
<link rel="stylesheet" href="{{ asset('') }}assets/vendor/fonts/materialdesignicons.css" />
<link rel="stylesheet" href="{{ asset('') }}assets/vendor/fonts/fontawesome.css" />
<link rel="stylesheet" href="{{ asset('') }}assets/vendor/fonts/fontawesome.css" />

<!-- Core CSS -->
 <link rel="stylesheet" href="{{ asset('') }}assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
<link rel="stylesheet" href="{{ asset('') }}assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" /> 
<link rel="stylesheet" href="{{ asset('') }}assets/css/demo.css" />
<link rel="stylesheet" href="{{ asset('') }}assets/css/custom-backend.css" />

<!-- Vendors CSS -->
<link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
<link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/node-waves/node-waves.css" /> 
<link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/swiper/swiper.css" />
<link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/select2/select2.css" />
<link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/tagify/tagify.css" />
<link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/bootstrap-select/bootstrap-select.css" />
<link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/animate-css/animate.css" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>
<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>

<style>
  
  .swal2-container {
        z-index: 10000;
        }
   .shows{
        display: block!important;
        margin-left:5px;
        }

  .hides{
  display: none!important;
  margin-left:5px;
  }
 #myBtn {
        display: none;
        position: fixed;
        bottom: 20px;
        right: 30px;
        z-index: 99;
        /* font-size: 18px; */
        border: none;
        outline: none;
        background-color: #ad4bee;
        color: white;
        cursor: pointer;
        padding: 2px;
        border-radius: 4px;
        width: calc(2.895rem + 2px);
        height: calc(3rem + 2px);
        }

        #myBtn:hover {
        background-color: #aa7ef1;
        }
</style>
<!-- Page CSS -->
@stack('css')
<!-- Helpers -->
<script src="{{ asset('') }}assets/vendor/js/helpers.js"></script>
<script src="{{ asset('') }}assets/js/config.js"></script>
<script defer src="{{ asset('') }}assets/vendor/libs/alpine/alpine.min.js"></script>