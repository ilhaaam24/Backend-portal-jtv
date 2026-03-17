<!DOCTYPE html>

<html
  lang="en"
  class="light-style layout-navbar-fixed layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="{{ asset('') }}assets/"
  data-template="vertical-menu-template">
  <head>
    @include('layouts.parts_materialize.header')
    @livewireStyles
  </head>

  <body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <!-- Menu -->
        @include('layouts.parts_materialize.sidebar')
    
        <!-- / Menu -->

        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->
          @include('layouts.parts_materialize.navbar')
          
          <!-- / Navbar -->

          <!-- Content wrapper -->
          <div class="content-wrapper">

           <!-- Content -->
         
                @yield('content')

                <button onclick="topFunction()" id="myBtn" title="Go to top"><span class="mdi mdi-chevron-up-circle-outline mdi-24px"></span></button>
            <!-- / Content -->

            <!-- Footer -->
            @include('layouts.parts_materialize.footer')
            <!-- / Footer -->
          
       
            <div class="content-backdrop fade"></div>
          </div>
       
          <!-- Content wrapper -->
        </div>
        <!-- / Layout page -->
      </div>

      <!-- Overlay -->
      <div class="layout-overlay layout-menu-toggle"></div>

      <!-- Drag Target Area To SlideIn Menu On Small Screens -->
      <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->
   
    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    @livewireScripts
   
    <script src="{{ asset('assets/vendor/js/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js') }}"></script>

    <script src="{{ asset('') }}assets/vendor/libs/hammer/hammer.js"></script>
    <script src="{{ asset('') }}assets/vendor/libs/i18n/i18n.js"></script>
    <script src="{{ asset('') }}assets/vendor/libs/typeahead-js/typeahead.js"></script>

    <script src="{{ asset('') }}assets/vendor/js/menu.js"></script>

  
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('') }}assets/vendor/libs/select2/select2.js"></script>
    <script src="{{ asset('') }}assets/vendor/libs/tagify/tagify.js"></script>
    <script src="{{ asset('') }}assets/vendor/libs/bootstrap-select/bootstrap-select.js"></script>
    {{-- <script src="{{ asset('') }}assets/vendor/libs/apex-charts/apexcharts.js"></script> --}}
    <script src="{{ asset('') }}assets/vendor/libs/swiper/swiper.js"></script>

    <!-- Main JS -->
    <script src="{{ asset('') }}assets/js/main.js"></script>
    

    <!-- Page JS -->
    @stack('js')
  
    <script src="{{ asset('') }}assets/js/cards-actions.js"></script>
    <script type="text/javascript">

  </script>
  
  <script>
    // Get the button
    let mybutton = document.getElementById("myBtn");
    
    // When the user scrolls down 20px from the top of the document, show the button
    window.onscroll = function() {scrollFunction()};
    
    function scrollFunction() {
      if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
        mybutton.style.display = "block";
      } else {
        mybutton.style.display = "none";
      }
    }
    
    // When the user clicks on the button, scroll to the top of the document
    function topFunction() {
      document.body.scrollTop = 0;
      document.documentElement.scrollTop = 0;
    }
    </script>
  </body>
</html>
