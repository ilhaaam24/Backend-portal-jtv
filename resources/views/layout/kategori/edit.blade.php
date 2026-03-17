@extends('layouts.materialize')
    @push('css')
   
      <link href="{{ asset('') }}assets/vendor/libs/dropzone/dropzone.min.css" rel="stylesheet">
      <script src="{{ asset('') }}assets/vendor/libs/dropzone/dropzone.min.js"></script>
      <link href="{{ asset('') }}assets/vendor/libs/cropper/cropper.css" rel="stylesheet"/>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>
      <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
      <style>
          .dz-image img {
              width: 100%!important;
              height: 100%;
          }
         
          .dropzone .dz-preview .dz-image {
                border-radius: 20px;
                overflow: hidden;
                width: auto;
                height: 120px;
                position: relative;
                display: block;
                z-index: 10;
            }

          .dropzone.dz-started .dz-message {
              display: block !important;
          }
          .dropzone {
              border: 2px dashed #028AF4 !important;;
          }
          .dropzone .dz-preview.dz-complete .dz-success-mark {
              opacity: 1;
          }
          .dropzone .dz-preview.dz-error .dz-success-mark {
              opacity: 0;
          }
          .dropzone .dz-preview .dz-error-message{
              top: 144px;
          }
        
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
                  <form action="{{ route('konten.storesorot') }}" method="POST" enctype="multipart/form-data" autocomplete="off"
                     id="form_create_iklan">
                     @csrf
                 @else
                     <form action="{{ route('konten.updateiklan', $item->id_iklan) }}" method="POST"  autocomplete="off"
                         id="form_edit_iklan" enctype="multipart/form-data">
                         @csrf
                         @method('POST')
                 @endif
          <div class="d-flex justify-content-between">
            <h4 class="fw-bold mb-4"><span class="text-muted fw-light">Konten /</span> 
              @if ($status_form === "create_form")
              Add New
              @else
              Edit Iklan
              @endif
            </h4>
            <h4 class="fw-bold py-1 mb-3">
                @if ($status_form === "create_form")
                <button type="button" class="btn btn-primary btn-md waves-effect" id="storeIklan">Simpan</button>
                <a href="{{ route('konten.iklan') }}" type="button" class="btn btn-secondary btn-md waves-effect" id="backSorot"><span class="mdi mdi-close-thick"></span></a>
              @else
              <button type="button" class="btn btn-primary btn-md waves-effect" id="updateIklan">Simpan Perubahan</button>
              <a href="{{ route('konten.iklan') }}" type="button" class="btn btn-secondary btn-md waves-effect" id="backSorot"><span class="mdi mdi-close-thick"></span></a>
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
 
                 <input type="hidden" class="form-control" id="id_iklan" name="id_iklan"
                 value="@if($status_form === "edit_form"){{$item->id_iklan}}@else{{''}}@endif">
                

                    <div class="form-floating form-floating-outline mb-3 mt-3">
                        <input type="text" class="form-control" id="judul" name="judul" placeholder=" " 
                        aria-describedby="floatingInputJudul" value="@if ($status_form === "edit_form"){{ $item->nama_iklan }}@endif">
                        <label for="Judul">Judul</label>
                        <div id="floatingInputJudul" class="form-text">
                        </div>
                        @error('judul') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                   {{--  <img src="https://i.pinimg.com/550x/97/bf/27/97bf27becd0df4ff387b882572925416.jpg" class="img_awesome" alt="Trulli"> --}}
                  
                   <div class="d-none">
                       <canvas id="demo"></canvas>
                        <img id="watermark" alt="" crossOrigin="Anonymous" src="{{ asset('') }}assets/admin/upload_logo/jtv1-01.png">
                        <input type="text" id="hasildemo"> 
                        <img id="imgdemo" alt="">
                    </div>

                    <div class="row">
                      <div class="col-6">
                      <div class="form-floating form-floating-outline mb-3 mt-3">
                        <input type="text" class="form-control" id="tag" name="keterangan_iklan" placeholder=" " 
                        aria-describedby="floatingInputKet" value="@if ($status_form === "edit_form"){{ $item->keterangan_iklan }}@endif">
                        <label for="Keterangan">Keterangan</label>
                        <div id="floatingInputKet" class="form-text">
                        </div>
                        @error('keterangan_iklan') <span class="text-danger">{{ $message }}</span> @enderror
                      </div>
                      </div>

                      <div class="col-3">
                        <div class="form-floating form-floating-outline mb-3 mt-3">
                          <input type="text" class="form-control" id="height_img" name="height_img" placeholder=" " 
                          aria-describedby="floatingInputHeight" value="@if ($status_form === "edit_form"){{ $item->height }}@endif">
                          <label for="Height">Height</label>
                          <div id="floatingInputHeight" class="form-text">
                          </div>
                          @error('height_img') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                      </div>

                      <div class="col-3">
                        <div class="form-floating form-floating-outline mb-3 mt-3">
                          <input type="text" class="form-control" id="width_img" name="width_img" placeholder=" " 
                          aria-describedby="floatingInputWidth" value="@if ($status_form === "edit_form") {{ $item->width }} @endif">
                          <label for="Width">Width</label>
                          <div id="floatingInputWidth" class="form-text">
                          </div>
                          @error('width_img') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                      </div>

                      <div class="col-7">
                        <button type="button" class="btn btn-secondary w-100 get-image-berita mb-2">Pilih gambar</button>
                 
                        <div class="image_area">
                            <div enctype="multipart/form-data" id="dropzone" class="dropzone">
              
                                <div>
                                    <div class="dz-message">
                                        <H5> Klik atau Drop Gambar</h5>
                                    </div>
                                
                                    <div id="myform"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-floating form-floating-outline mb-3 mt-3" hidden>
                          <input type="text" class="form-control" id="edit_image_src" 
                          name="edit_image_src" 
                          value="@if($status_form == 'edit_form'){{$item->gambar_iklan}}@endif"
                          placeholder=" " aria-describedby="floatingImageSrc">
                          <label for="Image Src">Image Src</label>
                          <div id="floatingImageSrc" class="form-text">
                          </div>
                      </div>
                      </div>
                    </div>

                  </form>
                </div>
                </div>
              </div>
              </div>
            </div>

            {{-- Modal get image berita --}}
        <div class="modal fade" id="modalGetImage" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-scrollable modal-fullscreen" role="document">
            <div class="modal-content">
              <div class="modal-body" style="background:#f7f7f9;">
                  <button
                    type="button"
                    class="btn-close btn btn-light p-3 rounded-circle position-absolute"
                    data-bs-dismiss="modal"
                    aria-label="Close" style="right: 30px;z-index: 5;"></button>
                  <div class="nav-align-top mb-4">
                      <ul class="nav nav-pills mb-3" role="tablist">
                        <li class="nav-item" role="presentation">
                          <button type="button" class="nav-link waves-effect waves-light active media_lib" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-home" aria-controls="navs-pills-top-home" aria-selected="false" tabindex="-1">
                            Media Library Iklan
                          </button>
                        </li>
                      
                      </ul>
                      <div class="tab-content">
                        <div class="tab-pane fade active show" id="navs-pills-top-home" role="tabpanel">
                          {{-- @foreach($images as $image)  --}}
                          <div class="p-0">
                              <div class="row">
                                  <div class="col-md-9">
                                      <div class="card shadow-0">
                                          <div class="input-wrapper my-3 input-group input-group-lg input-group-merge px-5">
                                              <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-magnify mdi-20px"></i></span>
                                              <input type="text" class="form-control search_getimage_data" placeholder="Search Image Sorot...." 
                                              id="search_getimage_data" aria-label="Search" aria-describedby="basic-addon1">
                                          </div>
                                      <div id="id_getimage_gallery" class="row g-3"></div>
                                      </div>
                                  </div>

                                  <div class="col-md-3">
                                      <div class="card">
                                          <div class="card-header">
                                              <label for="Image" class="text-primary">Detail Image</label>
                                          </div>
                                          <div class="card-body">
                                              <form id="getimage_form" action="" autocomplete="off">
                                                  <div class="form-floating form-floating-outline mb-3 mt-3">
                                                      <input type="text" class="form-control" id="getimage_name" name="getimage_name" 
                                                      placeholder=" " aria-describedby="floatingGetImageName">
                                                      <label for="Image Name">Image Name</label>
                                                      <div id="floatingGetImageName" class="form-text">
                                                      </div>
                                                  </div>

                                                  <div class="form-floating form-floating-outline mb-3 mt-3">
                                                    <input type="text" class="form-control" id="getimage_url" name="getimage_url" 
                                                    placeholder=" " aria-describedby="floatingGetImageUrl">
                                                    <label for="Image Url">Image Url</label>
                                                    <div id="floatingGetImageUrl" class="form-text">
                                                    </div>
                                                </div>

                                                  <div class="form-floating form-floating-outline mb-3 mt-3">
                                                      <input type="text" class="form-control" id="getimage_caption" name="getimage_caption" 
                                                      placeholder=" " aria-describedby="floatingGetImageCaption">
                                                      <label for="Image Caption">Image Caption</label>
                                                      <div id="floatingGetImageCaption" class="form-text">
                                                      </div>
                                                  </div>


                                                  <div class="form-floating form-floating-outline mb-3 mt-3">
                                                      <input type="text" class="form-control" id="getimage_description" name="getimage_description" 
                                                      placeholder=" " aria-describedby="floatingGetImageDescription">
                                                      <label for="Image Description">Image Description</label>
                                                      <div id="floatingGetImageDescription" class="form-text">
                                                      </div>
                                                  </div>
                                                  <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                      <button type="button" class="btn btn-info btn-sm waves-effect" id="add-getimage-berita">Pilih</button>
                                                    </div>
                                              </form>

                                          </div>
                                      
                                      </div>
                                  </div>

                              </div>
                          </div>
                         {{-- @endforeach --}}
                        </div>
                    </div>
              </div>
              <div class="modal-footer">
               {{--  <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                  Close
                </button> --}}
                {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
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

         // (B) IMAGE + WATERMARK + CANVAS
         function watermark_image(src, wtmk){
        // (B) IMAGE + WATERMARK + CANVAS
            var img = new Image(),
                watermark = new Image(),
                loaded = 0,
                canvas = document.getElementById("demo"),
                ctx = canvas.getContext("2d");
                img.crossOrigin = "Anonymous";
            
                // (C) ADD WATERMARK (WHEN IMAGES ARE FULLY LOADED)
                function mark () { 
                    var h;
                    loaded++; 
                    if (loaded==2) {
                        // (C1) SET IMAGE
                        canvas.width = img.naturalWidth;
                        canvas.height = img.naturalHeight;
                        ctx.drawImage(img, 0, 0, img.naturalWidth, img.naturalHeight);
                        
                        // (C2) ADD WATERMARK
                        ctx.drawImage(watermark, 1000, 20, 226, 38);

                        h = canvas.toDataURL('image/jpeg', 1.0);
                        $('#hasildemo').val(h);
                        // console.log('canvas baru', h);
                        // (C3) DOWNLOAD (IF YOU WANT)
                    /*  let anchor = document.createElement("a");
                        anchor.href = canvas.toDataURL("image/jpeg");
                        anchor.download = "demoB.jpeg";
                        anchor.click();
                        anchor.remove(); */
                    }
                    return h;
                }
            // (D) GO
            img.onload = mark;
            watermark.onload = mark;
            img.src = src;
            watermark.src = wtmk;
            hasil = mark;
        }
          $(document).on('click', '#updateIklan', function () {
              save_edit_iklan();
          });

          
          $('#search_getimage_data').on('keyup', function(){
              search_getimage_data();
          });

          function search_getimage_data(){
                var keyword = $('#search_getimage_data').val();
                if(keyword!=''){
                    $.post('{{ route("getImageSearchiklan") }}',
                        {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            keyword:keyword
                        },
                        function(data){
                            // console.log(data);
                            $("#id_getimage_gallery").html('');
                            if(data.list_getimages){
                                $.each(data.list_getimages, function (key, value) {
                                    $("#id_getimage_gallery").append( 
                                    `<div class="col-2">
                                    <div class="card crd-getimg gap-2 d-grid" id="lib_img_`+ value.id +`" >
                                        <div id="image_`+ value.id +`">
                                            <img class="getimg_gallerys img rounded-top" 
                                            data-id="img_`+ value.id +`" 
                                            data-originalname="`+ value.original_filename +`"
                                            data-filename="`+ value.filename +`"
                                            data-imagecaption="`+ value.caption +`"
                                            data-imagedescription="`+ value.description +`"
                                            src="{{ url('') }}/`+ value.filename +`" max-width: 90%; height="120px">
                                            <div class="w-100 badge bg-label-primary rounded-bottom img_name_preview">`+ ellipsify(value.original_filename)     +`</div>
                                            </div>
                                        </div>
                                    </div>
                                    `);
                                });
                            }
                        });
                }else{
                    load_getimage_gallery();
                }
                
            }

         function ellipsify (str) {
                if (str.length > 10) {
                    return (str.substring(0, 10) + "...");
                }
                else {
                    return str;
                }
            }
        $(document).ready(function () {
            $('.select2').select2();
            tampil_gambar_iklan_edit();

             //clear disabled serialize form function
            $.fn.serializeIncludeDisabled = function () {
                let disabled = this.find(":input:disabled").removeAttr("disabled");
                let serialized = this.serialize();
                disabled.attr("disabled", "disabled");
                return serialized;
            };

            src = "https://i.pinimg.com/550x/97/bf/27/97bf27becd0df4ff387b882572925416.jpg";
            wtmk = "https://www.freepnglogos.com/uploads/avengers-png-logo/avengers-logo-vector-png-logo-18.png";
            // watermark_image(src, wtmk);
          
        });

        function tampil_gambar_iklan_edit()
            {
                $('#myform').html('');
                const note = document.querySelector('.dz-message');
             

                var formname = $('#nama_form').val();
                var url_img = $('#base_url').val();
                var edit_src_img  = $('#edit_image_src').val();
                var full_path_img = url_img + 'assets/iklan/' + edit_src_img;

                if(formname=='edit'){
                    note.style.display = 'none';
                    $('#myform').append(`<div class="card border-primary rounded mb-2"><img src="`+full_path_img+`" max-width: 90%; height="auto"/>
                                    </div>`);
                    $('#myform').append('<input type="hidden" name="addimage_iklan" class="form-control" value="'+edit_src_img+'" />');
                    $('#myform').append(`<div class="d-m d-flex justify-content-md-end">
                                        <button type="button" class="btn btn xs btn-danger clear-preview-getimage">
                                        cancel</button> </div>`)
              }
            }

        $(document).on('click', '.get-image-berita', function () {
                load_getimage_gallery();
                $('#modalGetImage').modal('show');
            });
        
          //load data gallery getimage
          function load_getimage_gallery(){
                $.ajax({
                    url: "{{url('/getImages_galleryiklan')}}",
                    type: "GET",
                    dataType: 'json',
                    success: function (result) {
                        // var photosObj = $.parseJSON(result.images);
                        $("#id_getimage_gallery").html('');
                        // console.log(result.images);
                        if(result.images){
                            $.each(result.images, function (key, value) {
                                $("#id_getimage_gallery").append( 
                                `<div class="col-2" >
                                    <div class="card crd-getimg  gap-2 d-grid" id="lib_img_`+ value.id +`">
                                        <div id="image_`+ value.id +`">
                                            <img class="getimg_gallerys img rounded--top" 
                                                data-id="img_`+ value.id +`" 
                                                data-originalname="`+ value.original_filename +`"
                                                data-filename="`+ value.filename +`"
                                                data-imagecaption="`+ value.caption +`"
                                                data-imagedescription="`+ value.description +`"
                                                src="{{ url('') }}/`+ value.filename +`" max-width: 90%; height="120px">
                                                <div class="w-100 badge bg-label-light text-muted rounded-bottom img_name_preview">`+ ellipsify(value.original_filename)     +`</div>
                                                </div>
                                        </div>
                                    </div>
                                `);
                            });

                        }else{
                            $("#id_getimage_gallery").append('No Image Found');
                        }
                    }
                });
            }

            // Get Image
            $(document).on('click', '.getimg_gallerys', function (e) {
                var header = document.getElementById("id_getimage_gallery");
                var btns = header.getElementsByClassName("crd-getimg");
                for (var i = 0; i < btns.length; i++) {
                btns[i].addEventListener("click", function() {
                var current = document.getElementsByClassName("border-primary border-2 rounded");
                current[0].className = current[0].className.replace(" border-primary border-2 rounded", "");
                this.className += " border-primary border-2 rounded";
                });
                }

                var originalname = $(this).data("originalname");
                var filename = $(this).data("filename");
                var id_img = $(this).data("id");
                var imagecaption = $(this).data("imagecaption");
                var imagedescription = $(this).data("imagedescription");

                var url_img = $('#base_url').val();
                var src_img =  url_img+filename;
                $('#lib_'+id_img).addClass("border-primary border-2 rounded");

                $('#getimage_name').val(originalname);
                $('#getimage_url').val(filename);
                $('#getimage_caption').val(imagecaption);
                $('#getimage_description').val(imagedescription);

            });

            //tambah image yg dipilih
            $(document).on('click', '#add-getimage-berita', function () {
                $('#myform').html('');
                const note = document.querySelector('.dz-message');
                note.style.display = 'none';

                var url_img = $('#base_url').val();
                var filename =  $('#getimage_name').val();
                var url =  $('#getimage_url').val();
                var src_img =  url_img+ 'assets/iklan/' + filename;
                var image_name = url_img + url;
                // var image_name = 'assets/upload-gambar/' + filename;

                // imageHandler2(src_img);
                $('#myform').append(`<div class="card border-primary rounded mb-2"><img src="`+image_name+`" max-width: 90%; height="auto"/>
                                    </div>`);
                $('#myform').append('<input type="hidden" name="addimage_iklan" class="form-control" value="'+filename+'" />');
                $('#myform').append(`<div class="d-m d-flex justify-content-md-end">
                                    <button type="button" class="btn btn xs btn-danger clear-preview-getimage">
                                    cancel</button> </div>`)
                $('#modalGetImage').modal('hide');

            });

            //Clear Image
            $(document).on('click', '.clear-preview-getimage', function () {
                $('#myform').html('');
                var val_img_src =  $('#edit_image_src').val();
                $('#myform').append('<input type="hidden" name="addimage_iklan" class="form-control" value="'+val_img_src+'" />');
                const note = document.querySelector('.dz-message');
                note.style.display = 'block';
            });

             //close modal
             $('#modalGetImage').on('hidden.bs.modal', function () {
                $('#getimage_name').val('');
                $('#getimage_caption').val('');
                $('#getimage_description').val('');
                $("#dropzoneImage .dz-image-preview").remove();
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

             // transform cropper dataURI output to a Blob which Dropzone accepts
             function dataURItoBlob(dataURI) {
            var byteString = atob(dataURI.split(',')[1]);
            var ab = new ArrayBuffer(byteString.length);
            var ia = new Uint8Array(ab);
            for (var i = 0; i < byteString.length; i++) {
                ia[i] = byteString.charCodeAt(i);
            }
            return new Blob([ab], { type: 'image/jpeg' });
            }

            // modal window template
            var modalTemplate = '<div class="modal fade modal_upload" tabindex="-1" role="dialog">' +
                    '<div class="modal-dialog modal-fullscreen" role="document">' +
                    '<div class="modal-content">' +
                    '<div class="modal-header">' +
                    '<h5 class="modal-title" id="modalLabel">Crop </h5>'+
                    '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>' +
                
                    '</div>' +
                    '<div class="modal-body">' +
                    '<div class="image-container">' +
                    '<img src="">' +
                    '</div>' +
                    '</div>' +
                    '<div class="modal-footer">' +
                    '<button type="button" class="btn btn-dark rotate-left"><span class="fa fa-rotate-left"></span></button>' +
                    '<button type="button" class="btn btn-dark rotate-right"><span class="fa fa-rotate-right"></span></button>' +
                    '<button type="button" class="btn btn-dark scale-x" data-value="-1"><span class="fa fa-arrows-h"></span></button>' +
                    '<button type="button" class="btn btn-dark scale-y" data-value="-1"><span class="fa fa-arrows-v"></span></button>' +
                    '<button type="button" class="btn btn-dark reset"><span class="fa fa-refresh"></span></button>' +
                    '<button type="button" class="btn btn-default close_crop" data-bs-dismiss="modal">Close</button>' +
                    '<button type="button" class="btn btn-primary crop-upload">Crop & upload</button>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>';

            // initialize dropzone
            Dropzone.autoDiscover = false;
            var c = 0;

            var myDropzone = new Dropzone(
            "#dropzone",
            {
                url:'/dropzone/store_iklan',
                autoProcessQueue: false,
                maxFiles: 1, 
                maxFilesize: 6,
                acceptedFiles: ".jpeg,.jpg,.png,.gif",
                addRemoveLinks: true,
                uploadMultiple: false,
                timeout: 50000,
                headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                // ..your other parameters..
            removedfile: function(file) 
            {
                if (this.options.dictRemoveFile && file.previewElement.id != "") 
                {
                    return Dropzone.confirm("Are You Sure to "+this.options.dictRemoveFile, function() 
                    {
                        // console.log(file);
                            
                        if(file.previewElement.id != ""){
                        var filename = file.previewElement.id;
                        var name = file.previewElement.name;
                        }else{
                        var filename = file.name;
                                    var name = file.previewElement.name;
                        }

                        //ajax delete
                        $.ajax({
                        headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: 'POST',
                        url: '/dropzone/delete_iklan',
                        data: { filename: filename,
                                name: name},
                        success: function (data){
                            // alert(data.name +" File has been successfully removed!"); 
                            console.log("Foto terhapus");
                            // $('input[name="addimage_iklan"][value="'+data.name+'"]').remove(); 
                           var val_img_src =  $('#edit_image_src').val();
                            $('input[name="addimage_iklan"]').val(val_img_src); 
                        },
                        error: function(e) {
                            console.log(e);
                        }});
                
                        var fileRef;
                        return (fileRef = file.previewElement) != null ? 
                        fileRef.parentNode.removeChild(file.previewElement) : void 0;
                    });
                }else{
                    var fileRef;
                    return (fileRef = file.previewElement) != null ? 
                    fileRef.parentNode.removeChild(file.previewElement) : void 0;
                }		
            },
            success: function(file, response) 
                {
                file.previewElement.id = response.image_path;
                file.previewElement.name = response.image_name;
                var url_img = $('#base_url').val();
                // console.log(file); 
                // console.log(response); 
                // set new images names in dropzone’s preview box.
                var olddatadzname = file.previewElement.querySelector("[data-dz-name]");  
              
                file.previewElement.querySelector("img").alt = response.success;
                olddatadzname.innerHTML = response.success;
              
                file.previewElement.querySelector("img").style.width = "600px";
                file.previewElement.querySelector("img").src = url_img + response.image_path;
                    $('#myform').html('');
                    $('#myform').append('<input type="hidden" class="form-control" name="addimage_iklan" value="'+response.image_name+'" />');
                //  var $button = $('<a href="#" class="js-open-cropper-modal" data-file-name="' + file.previewElement.id + '">Crop & Upload</a>');
                    // $(file.previewElement).append($button);
                },  
            error: function(file, response)
                {
                    if($.type(response) === "string")
                var message = response; //dropzone sends it's own error messages in string
                else
                var message = response.message;
                file.previewElement.classList.add("dz-error");
                _ref = file.previewElement.querySelectorAll("[data-dz-errormessage]");
                _results = [];
                for (_i = 0, _len = _ref.length; _i < _len; _i++) {
                node = _ref[_i];
                _results.push(node.textContent = message);
                }
                return _results;
                }
            }
            );

            // listen to thumbnail event
            myDropzone.on('thumbnail', function (file) {
                // console.log(file);
               
                // ignore files which were already cropped and re-rendered
                // to prevent infinite loop
                if (file.cropped) {
                    return;
                }
                if (file.width < 200) {
                    // validate width to prevent too small files to be uploaded
                    // .. add some error message here
                    return;
                }
                // cache filename to re-assign it to cropped file
                var cachedFilename = file.name;
                // remove not cropped file from dropzone (we will replace it later)
                myDropzone.removeFile(file);

                // dynamically create modals to allow multiple files processing
                var $cropperModal = $(modalTemplate);
                // 'Crop and Upload' button in a modal
                var $uploadCrop = $cropperModal.find('.crop-upload');

                var $img = $('<img id="img"/>');
            
                // initialize FileReader which reads uploaded file
                var reader = new FileReader();
                reader.onloadend = function () {
                    // add uploaded and read image to modal
                    $cropperModal.find('.image-container').html($img);
                    $img.attr('src', reader.result);
                };
                // read uploaded file (triggers code above)
                reader.readAsDataURL(file);

                // $cropperModal.modal('show');
                $cropperModal.modal('show').on("shown.bs.modal", function () {
            
                    $no = ++c;

                    var hgth = parseInt($('#height_img').val());
                    var wdth = parseInt($('#width_img').val());
                    // alert(hgth + '-' + wdth);
                    
                    // alert(++c);
                    // initialize cropper for uploaded image
                    var $image = $('#img');
                    $image.cropper({
                        // aspectRatio: 16 / 9, 
                        /* guides : true,
                        center: true, */
                        // autoCropArea: 1,
                        viewMode:1,
                        movable: false,
                        // cropBoxResizable: true,
                        cropBoxResizable: false,
                        data:{ //define cropbox size
                            height: hgth,
                          width:wdth,
                         
                        },
                         
                        rotatable: true,
                        minContainerWidth: 850
                        
                    });
                    var cropper = $image.data('cropper');
                    var $this = $(this);
                        $this
                            .on('click', '.rotate-right', function () {
                                cropper.rotate(90);
                            })
                            .on('click', '.rotate-left', function () {
                                cropper.rotate(-90);
                                
                            })
                            .on('click', '.reset', function () {
                                cropper.reset();
                            })
                            .on('click', '.scale-x', function () {
                                var $this = $(this);
                                cropper.scaleX($this.data('value'));
                                $this.data('value', -$this.data('value'));
                            })
                            .on('click', '.scale-y', function () {
                                var $this = $(this);
                                cropper.scaleY($this.data('value'));
                                $this.data('value', -$this.data('value'));
                            });
                });
                
                // listener for 'Crop and Upload' button in modal
                $uploadCrop.on('click', function() {
                    // get cropped image data
                    var hasil = $img.cropper('getCroppedCanvas');
                    var blob = hasil.toDataURL("image/jpeg");
                    // transform it to Blob object
                    // var newFile = dataURItoBlob(blob);
                    // set 'cropped to true' (so that we don't get to that listener again)

                    var wtmk = document.getElementById("watermark").src;
                    // console.log(src, wtmk);
                    watermark_image(blob, wtmk); 
                    setTimeout(() => {
                        var hasildemo = $('#hasildemo').val();
                        $('#imgdemo').attr('src' ,hasildemo);
                        // console.log('hasildemo', hasildemo);
                        var newFile = dataURItoBlob(hasildemo);
                        // newFile = $('#imgdemo');
                        newFile.cropped = true;
                        // assign original filename
                        newFile.name = cachedFilename;

                        // add cropped file to dropzone
                        myDropzone.addFile(newFile);
                        // upload cropped file with dropzone
                        myDropzone.processQueue();
                        
                        $cropperModal.modal('hide');
                        $('.modal').find('.image-container').html('');
                    }, 1000);
                    
                });

            });
        
            $(document).on('hidden.bs.modal','.modal_upload', function () {
               $(this).find(".image-container").html('');
            });

             //SAVE EDIT SOROT
             function save_edit_iklan(){
                var formdata = $('#form_edit_iklan').serializeIncludeDisabled();

                $.ajax({  
                url:`{{ route("konten.updateiklan") }}`,
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