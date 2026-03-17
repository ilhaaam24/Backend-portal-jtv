@extends('layouts.materialize')
    @push('css')
    @endpush

        @section('content')
        <div class="container-xxl flex-grow-1 container-p-y">
          <h5 class="card-header d-flex justify-content-between align-items-center mb-2">
            <span class="fw-bold mb-4" style="font-size:large;">
              <span class="text-muted fw-light">Layout /</span> Logo
            </span>
            {{-- <a href="#" class="btn btn-md btn-primary"> Add New  </a> --}}
          </h5>
        
            <div class="row gy-4">
              <div class="col-md-12 col-lg-12">
              <div class="card">
                <div class="card-body">
                  <input type="hidden" id="base_url" name="base_url" value="{{ url('/') }}/" class="form-control">
                  @livewire('logo-table')
                        {{-- <livewire:iklan-table/> --}}
                </div>
                </div>
              </div>
              </div>
            </div>

            {{-- Modal get image berita --}}
        <div class="modal fade" id="modalGetImage" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Display Image</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                
                    <div id="display-img-logo">

                    </div>
                      
              </div>
              {{-- <div class="modal-footer">
                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                  Close
                </button> 
                <button type="button" class="btn btn-primary">Save changes</button> 
              </div> --}}
            </div>
          </div>
        </div>

      
      </div>
             
        @endsection

    @push('js')
  <script>
      $(document).on('click', '.get-image-logo', function () {
              $('#display-img-logo').html('');
                var url_img = $('#base_url').val();
                var imgIklan = $(this).attr("data-img");
                var src_img =  url_img+ 'assets/logo/' + imgIklan;
                // alert(src_img);
                var html = '<img src="'+src_img+'" class="rounded" style="margin:auto;display:block;max-width: 750px;max-height: 740px;">';
       
                $('#display-img-logo').append(html);
                $('#modalGetImage').modal('show');
            });

  </script>
@endpush