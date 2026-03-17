@extends('layouts.materialize')
    @push('css')

      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>
      <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
      <style>
    
        
        .image_area {
          position: relative;
        }

        img {
            display: block;
            max-width: 100%;
        }

        img {
        max-width: 100%; /* This rule is very important, please do not ignore this! */
        }
          
        .preview {
            overflow: hidden;
            width: 160px; 
            height: 160px;
            margin: 10px;
            border: 1px solid red;
        }

        .modal-lg{
            max-width: 1000px !important;
        }

        .overlay {
          position: absolute;
          bottom: 10px;
          left: 0;
          right: 0;
          background-color: rgba(255, 255, 255, 0.5);
          overflow: hidden;
          height: 0;
          transition: .5s ease;
          width: 100%;
        }

        .image_area:hover .overlay {
          height: 50%;
          cursor: pointer;
        }

        .text {
          color: #333;
          font-size: 20px;
          position: absolute;
          top: 50%;
          left: 50%;
          -webkit-transform: translate(-50%, -50%);
          -ms-transform: translate(-50%, -50%);
          transform: translate(-50%, -50%);
          text-align: center;
        }
      
      </style>
    @endpush

        @section('content')
        <div class="container-xxl flex-grow-1 container-p-y">
          @if ($status_form === "create_form")
                  <form action="{{ route('video.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off"
                     id="form_create_video">
                     @csrf
                 @else
                     <form action="{{ route('video.update', $item->id_video) }}" method="POST"  autocomplete="off"
                         id="form_edit_video" enctype="multipart/form-data">
                         @csrf
                         @method('POST')
                 @endif
          <div class="d-flex justify-content-between">
            <h4 class="fw-bold py-1 mb-3"><span class="text-muted fw-light">Konten /</span> 
              @if ($status_form === "create_form")
              Add New
              @else
              Edit Video
              @endif
            </h4>
            <h4 class="fw-bold py-1 mb-3">
                @if ($status_form === "create_form")
                <button type="button" class="btn btn-primary btn-sm waves-effect" id="storeVideo">Simpan</button>
                <a href="{{ route('konten.video') }}" type="button" class="btn btn-secondary btn-sm waves-effect" id="backVideo"><b>X</b></a>
              @else
              <button type="button" class="btn btn-primary btn-sm waves-effect" id="updateVideo">Update</button>
              <a href="{{ route('konten.video') }}" type="button" class="btn btn-secondary btn-sm waves-effect" id="backVideo"><b>X</b></a>
              @endif   
            </h4>
          </div>

            <div class="row gy-4">
              <div class="col-md-12 col-lg-12">
              <div class="card">
                <div class="card-body">
                  
                 <input type="hidden" id="base_url" name="base_url" value="{{ url('/') }}/" class="form-control">
                 <input type="hidden" class="form-control" id="nama_form" 
                 value="@if($status_form === "create_form"){{'create'}}@else{{'edit'}}@endif">
 
                 <input type="hidden" class="form-control" id="id_video" name="id_video"
                 value="@if($status_form === "edit_form"){{$item->id_video}}@else{{''}}@endif">
                

                    <div class="form-floating form-floating-outline mb-3 mt-3">
                        <input type="text" class="form-control" id="judul_video" name="judul_video" placeholder=" " 
                        aria-describedby="floatingInputJudulVideo" value="@if ($status_form === "edit_form"){{ $item->judul_video }}@endif">
                        <label for="Judul Video">Judul Video</label>
                        <div id="floatingInputJudulVideo" class="form-text">
                        </div>
                        @error('judul_video') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                   {{--  <img src="https://i.pinimg.com/550x/97/bf/27/97bf27becd0df4ff387b882572925416.jpg" class="img_awesome" alt="Trulli"> --}}
                  
               

                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="form-floating form-floating-outline mb-4 mt-3">
                              <input type="text" class="form-control" id="id_video_yt" name="id_video_yt" placeholder=" " 
                              aria-describedby="floatingInputYt" value="@if ($status_form === "edit_form"){{ $item->id_video_yt }}@endif">
                              <label for="Link Id Video Youtube">Link Id Video Youtube</label>
                              <div id="floatingInputYt" class="form-text">
                              </div>
                              <div class="form-text">Link Thumbnail Terisi Otomatis sesuai link Id Video &amp; Youtube*</div>
                              @error('id_video_yt') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-floating form-floating-outline mb-4 mt-3">
                                <input type="text" class="form-control" id="thumbnail" name="thumbnail" placeholder=" " readonly
                                aria-describedby="floatingInputThumbnail" value="@if ($status_form === "edit_form"){{ $item->thumbnail }}@endif">
                                <label for="Link Thumbnail Video Youtube">Link Thumbnail Video Youtube</label>
                                <div id="floatingInputThumbnail" class="form-text">
                                </div>
                                @error('thumbnail') <span class="text-danger">{{ $message }}</span> @enderror
                              </div>
                        </div>

                            
                        <div class="col-12 col-md-6 mt-3" style="margin: 0 auto;max-height:270px;" >
                          <div class="image_area">
                              <div id="myform"></div>
                          </div>
                        </div>
                    </div>

                  </form>
                </div>
                </div>
              </div>
              </div>
            </div>

     
        @endsection

    @push('js')
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="{{ asset('assets/vendor/libs/cropper/cropper.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cropper/jquery-cropper.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cropper/jquery-cropper.js') }}"></script>

      <script>

         
        $(document).ready(function () {
            $('.select2').select2();
            tampil_gambar_video_edit();

             //clear disabled serialize form function
            $.fn.serializeIncludeDisabled = function () {
                let disabled = this.find(":input:disabled").removeAttr("disabled");
                let serialized = this.serialize();
                disabled.attr("disabled", "disabled");
                return serialized;
            };
        });

            var delayTimer;
            function doSearch() {
                clearTimeout(delayTimer);
                delayTimer = setTimeout(function() {
                    // Do the ajax stuff
                    tampil_gambar_video_edit();
                }, 1000); // Will do the ajax stuff after 1000 ms, or 1 s
            }

            //CARI IMAGE GALLERY
            $('#id_video_yt').on('keyup', function(){
              var id_video = $(this).val();
              var new_val = 'https://img.youtube.com/vi/'+ id_video +'/sddefault.jpg';
              if(id_video !=''){
                $('#thumbnail').val(new_val);
                doSearch();
              }
              else{
                $('#thumbnail').val('');
                doSearch();
              }
            });
            
       /*  $(document).on('change', '#id_video_yt', function () {
          var id_video = $(this).val();
          alert(id_video);
            // tampil_gambar_video_edit();
            });
         */
         /*  $(document).on('change', '#thumbnail', function () {
          tampil_gambar_video_edit();
          });
       */

            function tampil_gambar_video_edit()
            {
                $('#myform').html('');
                var formname = $('#nama_form').val();
                var url_img = $('#base_url').val();
                var edit_src_img  = $('#thumbnail').val();
                var full_path_img =  edit_src_img;

                if(edit_src_img!=''){
                // if(formname=='edit' && edit_src_img!=''){
                    $('#myform').append(`<div class="card border-primary rounded mb-2"><img src="`+full_path_img+`" onerror="this.src='https://www.portaljtv.com/images/broken.webp'" max-width: 80%; height="260px" class="rounded"/>
                                    </div>`);
                  
              }else{
                $('#myform').append(`<div class="card border-primary rounded mb-2"><img src="https://www.portaljtv.com/images/broken.webp" onerror="this.src='https://www.portaljtv.com/images/broken.webp'" max-width: 80%; height="260px" class="rounded"/>
                                    </div>`);
              }

            }

            $(document).on('click', '#updateVideo', function () {
                save_edit_video();
            });

            $(document).on('click', '#storeVideo', function () {
                save_video_baru();
            });


            //SAVE BERITA BARU
            function save_video_baru(){
                var formdata = $('#form_create_video').serializeIncludeDisabled();
              
                $.ajax({  
                url:`{{ route("video.store") }}`,
                method:'POST',  
                data: formdata,  
                dataType : "JSON",  
                    success:function(data)  
                    {  
                   
                    if (data.status == "success") {  
                        Swal.fire('Saved!',  data.message + ' !','success');  
                        setTimeout(function(){// wait for 5 secs(2)
                            // location.reload(); // then reload the page.(3)
                               window.location=data.url;
                        }, 1000); 
                       
                        } 
                            else{
                        Swal.fire('Changes are not saved', '', 'info');
                        }
                    }
                });  
            }

             //SAVE EDIT SOROT
             function save_edit_video(){
                var formdata = $('#form_edit_video').serializeIncludeDisabled();

                $.ajax({  
                url:`{{ route("video.update") }}`,
                method:'POST',  
                data: formdata,  
                dataType : "JSON",  
                    success:function(data)  
                    {  
                    if (data.status == "success") {  
                          Swal.fire('Saved!',  data.message + ' !','success');  
                          setTimeout(function(){// wait for 5 secs(2)
                              location.reload(); // then reload the page.(3)
                          }, 1000); 
                        } 
                        else{
                        Swal.fire('Changes are not saved', '', 'info');
                        }
                    }
                });  

            }

            
      </script>

@endpush