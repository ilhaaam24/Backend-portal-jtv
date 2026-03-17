@extends('layouts.materialize')
    @push('css')
    <link href="{{ asset('') }}assets/vendor/libs/dropzone/dropzone.min.css" rel="stylesheet">
    <script src="{{ asset('') }}assets/vendor/libs/dropzone/dropzone.min.js"></script>
    <link href="{{ asset('') }}assets/vendor/libs/cropper/cropper.css" rel="stylesheet"/>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
   
  <style>
        .shows{
          display: block!important;
          margin-left:5px;
        }

        .hides{
          display: none!important;
          margin-left:5px;
        }
      .swal2-container {
        z-index: 2000 !important;
        }
        /* .cropper-canvas {
        max-width: 100%;
        height: 812px!important;
        } */

       /*     */
       .image-container{
        max-height: 720px;
       }
        .img-container {
 
        max-width: 100%;
        }

        .img-container img {

        width: 100%;
        }

        .dz-image img {
            width: 100%;
            height: 100%;
        }

        .dropzone .dz-preview .dz-image {
                border-radius: 20px;
                overflow: hidden;
                width: auto;
                height: 250px;
                position: relative;
                display: block;
                z-index: 10;
            }

        .dropzone.dz-started .dz-message {
            display: block;
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
  <style>
     #loading {
        position: fixed;
        display: block;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        text-align: center;
        opacity: 0.7;
        background-color: #fff;
        z-index: 99;
      }

      #loading-image {
        position: absolute;
        top: 40%;
        left: 33%;
        z-index: 100;
      }
  </style>
    @endpush

        @section('content')
        <div class="container-xxl flex-grow-1 container-p-y">
          @if(Session::has('success'))
        
            <div class="alert alert-warning">

              <strong>Success: </strong>{{ Session::get('success') }}
              
              <button type="button" class="btn-close btn-pinned" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            @endif
            <div class="row gy-4">
              <div class="col-12 col-md-12 col-lg-12">
              <div class="card">
                <div class="card-body">
                 
                  <div class="nav-align-top mb-3">
                    <ul class="nav nav-pills mb-3" role="tablist">
                      <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link waves-effect waves-light active media_lib" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-home" aria-controls="navs-pills-top-home" aria-selected="false" tabindex="-1">
                          Media Library
                        </button>
                      </li>
                      <li class="nav-item" role="presentation" id="multi_upload">
                        <button type="button" class="nav-link waves-effect waves-light " role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-profile" aria-controls="navs-pills-top-profile" aria-selected="true">
                          Upload
                        </button>
                      </li>
                    </ul>
                    <div class="tab-content" style="padding: 2px!important">
                      <div class="tab-pane fade active show" id="navs-pills-top-home" role="tabpanel">
                            {{-- @foreach($images as $image)  --}}
                            <div class="row row gy-4">
                                <div class="col-12 col-md-9">
                                    <div class="card shadow-0">
                                        {{-- Search Images --}}
                                        <div class="input-wrapper my-3 input-group input-group-lg input-group-merge px-1 mb-3">
                                       
                                            <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-magnify mdi-20px"></i></span>
                                            <input type="text" class="form-control search_image_data" placeholder="Search Image...." 
                                            id="search_image_data" aria-label="Search" aria-describedby="basic-addon1">
                                            <button type="button" class="btn btn-secondary btn-xs waves-effect btn-section-block"  id="reload_data_gallery"><span class="mdi mdi-refresh"></span></button>
                                        </div>
                                        {{-- loading --}}
                                        <div id="loading">
                                          <img id="loading-image" src="{{ asset('assets/img/Internet.gif') }}" alt="Loading..." />
                                        </div>
                                        {{-- infinite loadmore --}}
                                        <div id="id_image_gallery" class="row px-1" style="height: 610px; overflow-y: scroll;">
                                            <!-- Results -->
                                        </div>

                                        {{-- button loadmore --}}
                                        <div class="text-center m-3">
                                            <button class="btn btn-primary" id="load-more" data-paginate="2">Load more...</button>
                                        </div>

                                        <!-- Data Loader -->
                                        <div class="auto-load text-center">
                                        </div>

                                    </div>
                                </div>

                                <div class="col-12 col-md-3">
                                    <div class="card">
                                        <div class="card-header">
                                          <div class="d-flex justify-content-between">
                                            <label for="Image" class="text-primary">Info Image</label>
                                              <div class="d-flex justify-content-between">
                                              <button type="button" class="btn btn-icon btn-primary btn-sm waves-effect action_gallery" 
                                              id="update_data_gallery" data-bs-toggle="tooltip"  data-bs-placement="top" 
                                              data-bs-original-title="Save">
                                              <span class="mdi mdi-content-save-check"></span></button>

                                              <button type="button" class="btn btn-icon btn-info btn-sm waves-effect action_gallery " 
                                              id="edit_data_gallery" data-bs-toggle="tooltip"  data-bs-placement="top" 
                                              style="margin-left:5px;" data-bs-original-title="Edit">
                                              <span class="mdi mdi-pencil"></span></button>
                                              
                                              <button type="button" class="btn btn-icon btn-warning btn-sm waves-effect action_gallery" 
                                              id="cancel_data_gallery" data-bs-toggle="tooltip"  data-bs-placement="top" 
                                              style="margin-left:5px;" data-bs-original-title="cancel">
                                              <span class="mdi mdi-window-close""></span></button>

                                              <button type="button" class="btn btn-icon btn-danger btn-sm waves-effect action_gallery" 
                                              id="delete_data_gallery" data-bs-toggle="tooltip"  data-bs-placement="top" 
                                              style="margin-left:5px;" data-bs-original-title="Delete">
                                              <span class="mdi mdi-trash-can-outline"></span></button>
                                            </div>
                                          </div>
                                        </div>
                                        <div class="card-body">
                                            <form id="update_detail" action="" autocomplete="off">
                                                <div id="img_detail" class="mb-4">
                                                </div>

                                                <div class="form-floating form-floating-outline mb-3 mt-3" hidden>
                                                    <input type="text" class="form-control" id="id_gallery" name="id_gallery">
                                                </div>
                                                <div class="form-floating form-floating-outline mb-3 mt-3">
                                                  <div class="form-floating form-floating-outline mb-3">
                                                    <select id="image_tipe" class="select form-select" name="image_tipe">
                                                        <option value="" > Pilih </option>
                                                        <option value="berita"> Berita </option>
                                                        <option value="opini"> Opini </option>
                                                        <option value="sorot"> Sorot </option>
                                                        <option value="iklan"> Iklan </option>
                                                     
                                                    </select>
                                                    <label for="Tipe Gambar">Tipe Gambar</label>
                                                    
                                                </div>
            
                                                  
                                                </div>

                                                <div class="form-floating form-floating-outline mb-3 mt-3">
                                                    <input type="text" class="form-control" id="image_name" name="image_name" 
                                                    placeholder=" " aria-describedby="floatingImageName" value="" readonly>
                                                    <label for="Nama Gambar">Nama Gambar</label>
                                                    <div id="floatingImageName" class="form-text">
                                                    </div>
                                                </div>

                                                <div class="form-group mb-3">
                                                    <div class="form-floating form-floating-outline mb-4">
                                                        <textarea class="form-control" id="image_caption_text" rows="5" placeholder="..." 
                                                        name="image_caption_text" wire:model="image_caption_text" style="font-size: small;height: auto;" readonly></textarea>
                                                        <label for="image_caption_text">Caption Gambar</label>
                                                    </div>
                                                </div>

                                                <div class="form-group mb-3" style="display:none;">
                                                    <div class="form-floating form-floating-outline mb-4">
                                                        <textarea class="form-control" id="image_description_text" rows="4" placeholder="..."
                                                        name="image_description_text" wire:model="image_description_text" style="font-size: small;height: auto;"></textarea>
                                                        <label for="image_description_text">Image Description</label>
                                                    </div>
                                                </div>

                                                <div class="form-floating form-floating-outline mb-3 mt-3">
                                                    <input type="text" class="form-control" id="image_url" name="image_url" value=""
                                                    placeholder=" " aria-describedby="floatingUrlEmbed" style="font-size: small;" readonly>
                                                    <label for="Image Url" >Alamat URL</label>
                                                    <div id="floatingUrlEmbed" class="form-text">
                                                    </div>
                                                </div>
                                        
                                            </form>
                                        </div>
                                    
                                    </div>
                                </div>{{-- END COL-3 --}}
                            </div>{{-- END ROW --}}                             
                            {{-- @endforeach --}}
                      </div>

                        <div class="tab-pane fade " id="navs-pills-top-profile" role="tabpanel">
                            <div class="form-group">
                              <div class="row">
                                  <div class="col-12 col-md-6 mb-3">
                                    <div class="card">
                                      <div class="card-header mb-4">
                                        <div class="d-flex justify-content-between">
                                          <label for="Image" class="text-primary">Documents File Upload</label>
                                        </div>
                                      </div>
                                      <div class="card-body">
                                     
                                        <div class="dropzone" id="dropzone"></div>
                                        <div id="form_id"></div>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-12 col-md-6">
                                    <div class="card">
                                      <div class="card-header">
                                        <div class="d-flex justify-content-between">
                                          <label for="Image" class="text-primary">Detail Image</label>
                                          <button type="button" class="btn btn-primary btn-md waves-effect" 
                                          id="simpan_data_gallery" data-bs-toggle="tooltip"  data-bs-placement="top" 
                                          data-bs-original-title="Simpan data Gallery">Save</button>
                                        </div>
                                      </div>
                                      <div class="card-body">
                                          <form id="form_data_gallery" action="" autocomplete="off">
                                            <div class="row">
                                                <div class="col-12">
                                                  <div id="myform"></div>
                                                  <div id="img_detail_upload" class="mb-4">
                                                    {{-- watermark area --}}
                                                      <div class="d-none" id="area_watermark">
                                                        <canvas id="demo"></canvas>
                                                        <img id="watermark" alt="" crossOrigin="Anonymous" src="{{ asset('') }}assets/admin/upload_logo/jtv1-01.png">
                                                        <input type="text" id="hasildemo"> 
                                                      
                                                      </div>
                                                      <img id="imgdemo" class="img rounded" alt="">
                                                  </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                  <div class="form-floating form-floating-outline mb-3">
                                                  <select id="tipe_gambar" class="select form-select" 
                                                    name="tipe_gambar">
                                                        <option value="berita"> Berita </option>
                                                        <option value="opini"> Opini</option>
                                                        <option value="iklan"> Iklan </option>
                                                        <option value="sorot"> Sorot</option>
                                                    </select>
                                                    <label for="Image Tipe">Tipe Gambar</label>
                                                  </div>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                <div class="form-floating form-floating-outline mb-3">
                                                    <select wire:model="id_kategori" id="id_kategori" name="id_kategori" class="select2 form-select">
                                                        <option value="0">-- Select Kategori --</option>
                                                        @foreach ($kategori_list as $data)
                                                        <option value="{{$data->id_kategori_berita}}"
                                                            {{-- @if ($status_form === "edit_form")
                                                                {{ $data->id_navbar == $item->id_menu_berita ? 'selected' : '' }}
                                                            @endif --}}
                                                            >
                                                            {{$data->nama_kategori_berita}}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                    <label for="id_kategori">Kategori</label>
                                                    @error('id_kategori') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                                </div>

                                                <div class="col-12 col-md-6">
                                                  <div class="form-floating form-floating-outline mb-3">
                                                    <select id="ukuran_gambar" class="select form-select" 
                                                    name="ukuran_gambar">
                                                        <option value="1"> Default </option>
                                                        <option value="2"> Special</option>
                                                    </select>
                                                    <label for="Ukuran Gambar">Ukuran Gambar</label>
                                                </div>
                                                </div>
                                              
                                                <div class="col-12 col-md-6">
                                                  <div class="form-floating form-floating-outline mb-3">
                                                    <select wire:model="watermark_gambar" id="watermark_gambar" class="select form-select" 
                                                    name="watermark_gambar">
                                                        <option value="1"> Watermark </option>
                                                        <option value="0" selected>Tanpa Watermark</option>
                                                    </select>
                                                    <label for="watermark">Watermark</label>
                                                </div>
                                                </div>
                                                <div class="col-12">
                                                  <div class="form-floating form-floating-outline mb-3 mt-3">
                                                    <input type="text" class="form-control" id="image_name_add" name="image_name_add" 
                                                    placeholder=" " aria-describedby="floatingImageName">
                                                    <label for="Nama Gambar">Nama Gambar</label>
                                                    <div id="floatingImageName" class="form-text">
                                                    </div>
                                                  </div>
                                                </div>

                                                <div class="col-12">
                                                  <div class="form-group mb-3">
                                                    <div class="form-floating form-floating-outline mb-4">
                                                        <textarea class="form-control" id="image_caption" rows="3" placeholder="..." 
                                                        name="image_caption" style="font-size: small;height: auto;"></textarea>
                                                        <label for="Caption Gambar">Caption Gambar</label>
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
                        </div>
                    </div>
                    {{-- END TAB OF CONTENT --}}
                </div>

                </div>
                </div>
              </div>
              </div>
            </div>
             
        @endsection

    @push('js')
    <script src="{{ asset('assets/vendor/libs/cropper/cropper.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cropper/jquery-cropper.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cropper/jquery-cropper.js') }}"></script>
    <script>
         $(document).ready(function () {
            $('.select2').select2();
            var page =1;
            infinteLoadMore(page);
            $('.action_gallery').addClass('hides');
            $('#simpan_data_gallery').addClass('hides');
            disabled_form_data_gallery();
           
         });

         function disabled_update_detail(){
            $('#image_tipe').prop('disabled', true);
            $('#image_name').prop('disabled', true);
            $('#image_caption_text').prop('disabled', true);
            $('#image_url').prop('disabled', true);
         };

         function enabled_update_detail(){
            $('#image_tipe').prop('disabled', false);
            $('#image_name').prop('disabled', false);
            $('#image_caption_text').prop('disabled', false);
         };

         function enabled_form_data_gallery(){
          //  $('#form_data_gallery *').prop('disabled', false);
            $('#image_name_add').prop('disabled', false);
            $('#image_caption').prop('disabled', false);
         };

         function disabled_form_data_gallery(){
            // $('#form_data_gallery *').prop('disabled', true);
            $('#image_name_add').prop('disabled', true);
            $('#image_caption').prop('disabled', true);
         };

            //clear disabled serialize form function
            $.fn.serializeIncludeDisabled = function () {
                let disabled = this.find(":input:disabled").removeAttr("disabled");
                let serialized = this.serialize();
                disabled.attr("disabled", "disabled");
                return serialized;
            };

        //INFINITE LOAD
        var ENDPOINT = "{{url('/getimages_gallery')}}";
        var page = 1;
           
            //klik pilih gambar image gallery
            $(document).on('click', '.img_gallerys', function (e) {
                $('#lib_'+id_img).find('span').remove();
                var header = document.getElementById("id_image_gallery");
                var btns = header.getElementsByClassName("crd-img");
                $checklist =  '<span class="position-absolute top-60 start-100 translate-middle badge rounded-pill bg-primary text-white"><i class="mdi mdi-check"></i></span>';
                for (var i = 0; i < btns.length; i++) {
                btns[i].addEventListener("click", function() {
                var current = document.getElementsByClassName("border-primary border-2 rounded");
                $('#lib_'+id_img).find('span').remove();
                current[0].className = current[0].className.replace(" border-primary border-2 rounded", "");
                this.className += " border-primary border-2 rounded";
                });
                }
                    var originalname = $(this).data("originalname");
                    var filename = $(this).data("filename");
                    var id_img = $(this).data("id");
                    var title = $(this).data("title");
                    var idGallery = $(this).data("idgallery");
                    var tipeGallery = $(this).data("tipe");
                    var imagecaption = $(this).data("imagecaption");
                    var imagedescription = $(this).data("imagedescription");
                    var src = $(this).data("src");
      
                    var url_img = $('#base_url').val();
                    var src_img =  url_img+filename;
                    $('#lib_'+id_img).addClass("border-primary border-2 rounded");
                 
                    $('#lib_'+id_img).append($checklist);
                
                    $tampil_img = '<div class="card"><img src="'+ src +'" height="160px" width="310px" class="img rounded"></div>';
                    
                    $('.action_gallery').removeClass('hides');
                    $('.action_gallery').addClass('shows');
                    $('#update_data_gallery').addClass('hides');
                    $('#cancel_data_gallery').addClass('hides');
                    $('#img_detail').html('');
     
                    $('#img_detail').append($tampil_img);
                    $('#id_gallery').val(idGallery);
                    $('#image_tipe').val(tipeGallery).trigger('change');
                    $('#image_name').val(title);
                    $('textarea#image_caption_text').val(imagecaption);
                    $('#image_description').val(imagedescription);
                    $('textarea#image_description').val(imagedescription);
                    $('#image_url').val(src);


            });
     
            var delayTimer;
            function doSearch(page) {
                clearTimeout(delayTimer);
                delayTimer = setTimeout(function() {
                    // Do the ajax stuff
                    search_image_data(page);
                }, 1000); // Will do the ajax stuff after 1000 ms, or 1 s
            }

            //CARI IMAGE GALLERY
            $('#search_image_data').on('keyup', function(){
                var page = 1;
                doSearch(page);
               
            });
            
            function search_image_data(page){
                var keyword = $('#search_image_data').val();
                if(keyword!=''){
                    $.ajax({
                        url: '{{ route("getImageSearch") }}' + "/?page=" + page,
                        datatype: "html",
                        type: "post",
                        data:{
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            keyword:keyword,
                            page : page
                        },
                        beforeSend: function () {
                            $('.auto-load').show();
                        }
                    })
                    .done(function (response) {
                        if (response.html == '') {
                            $('.auto-load').html("");
                            $('.auto-load').html("We don't have more data to display :(");
                            // $('#load-more').hide();
                            document.getElementById("load-more").style.visibility="hidden";
                            return;
                        }else if(response.html != '' && page==1){
                            if (response.list_getimages.total <= 25) {
                                document.getElementById("load-more").style.visibility="hidden";
                                document.getElementById('load-more').setAttribute('data-paginate', 2);
                                $('#load-more').text('Load more...');
                              
                              
                            }else{
                                document.getElementById("load-more").style.visibility="visible";
                                document.getElementById('load-more').setAttribute('data-paginate', parseInt(page)+1);
                                $('#load-more').text('Load more...');
                            }
                                $('#id_image_gallery').html('');
                                $('.auto-load').html("");
                                 $('#load-more').text('Load more...');
                                $('#id_image_gallery').append(response.html);
                        }else {
                                $('#id_image_gallery').html('');
                            if (response.list_getimages.total <= 25) {
                                document.getElementById("load-more").style.visibility="hidden";
                                document.getElementById('load-more').setAttribute('data-paginate', parseInt(page));
                                $('#load-more').text('Load more...');
                              
                            }else{
                                document.getElementById("load-more").style.visibility="visible";
                                document.getElementById('load-more').setAttribute('data-paginate', parseInt(page)+1);
                                $('#load-more').text('Load more...');
                            }
                                $('.auto-load').html("");
                                $('#id_image_gallery').append(response.html);
                            }
                      
                    })
                    .fail(function (jqXHR, ajaxOptions, thrownError) {
                        console.log('Server error occured');
                    });

                }else{
                    infinteLoadMore(page);
                }
            }

            // infinteLoadMore(page);
            $(window).scroll(function () {
                if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
                    page++;
                    infinteLoadMore(page);
                }
            });

            $('#load-more').click(function() {
                var paginate = $(this).data('paginate');
                const typeId = document.getElementById('load-more').dataset.paginate;    
                infinteLoadMore(typeId);
            });

            
            function infinteLoadMore(page) {
                $.ajax({
                        url: ENDPOINT + "/?page=" + page,
                        datatype: "html",
                        type: "get",
                        beforeSend: function () {
                            $('.auto-load').show();
                            $('#loading').show();
                        }
                    })
                    .done(function (response) {
                      $('#loading').hide();
                        if (response.html == '') {
                            $('.auto-load').html("");
                            $('.auto-load').html("We don't have more data to display :(");
                            // $('#load-more').hide();
                            document.getElementById("load-more").style.visibility="hidden";
                            $('#load-more').text('Load more...');
                                document.getElementById('load-more').setAttribute('data-paginate', 2);
                            return;
                        }else if(response.html != '' && page==1){
                                $('#id_image_gallery').html('');
                                $('.auto-load').html("");
                                document.getElementById("load-more").style.visibility="visible";
                                $('#load-more').text('Load more...');
                                document.getElementById('load-more').setAttribute('data-paginate', parseInt(page)+1);
                                $('#id_image_gallery').append(response.html);
                        }else {
                                $('.auto-load').html("");
                                document.getElementById("load-more").style.visibility="visible";
                                $('#load-more').text('Load more...');
                                document.getElementById('load-more').setAttribute('data-paginate', parseInt(page)+1);
                                $('#id_image_gallery').append(response.html);
                            }

                            disabled_update_detail();
                    })
                    .fail(function (jqXHR, ajaxOptions, thrownError) {
                        console.log('Server error occured');
                    });
            }
           
            $.fn.cropper.noConflict();
            $('.modal_upload').on('hidden.bs.modal', function () {
                $(this).find(".image-container").html('');
            });

   
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Modal close crop
            $(document).on('click', '.close_crop', function () {
                $('.modal').find('.image-container').html('');
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

                // (B) IMAGE + WATERMARK + CANVAS
                function watermark_image(src, wtmk)
                {
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
                    '<div id="toggle-aspect-ratio" class="btn-group d-flex flex-nowrap" data-bs-toggle="buttons">' +
                            '<label class="btn btn-secondary">' +
                                '<input type="radio" class="sr-only" id="aspectRatio1" name="aspectRatio" value="1.7777777777777777">' +
                                '<span class="docs-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="aspectRatio: 16 / 9">' +
                                '16:9</span>' +
                            '</label>' +
                            '<label class="btn btn-secondary">' +
                                '<input type="radio" class="sr-only" id="aspectRatio2" name="aspectRatio" value="1.3333333333333333">' +
                                '<span class="docs-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="aspectRatio: 4 / 3">' +
                                '4:3</span>' +
                            '</label>' +
                            '<label class="btn btn-secondary">' +
                                '<input type="radio" class="sr-only" id="aspectRatio3" name="aspectRatio" value="1">' + 
                                '<span class="docs-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="aspectRatio: 1 / 1">' +
                                '1:1</span>' +
                            '</label>' +
                            '<label class="btn btn-secondary">' +
                                '<input type="radio" class="sr-only" id="aspectRatio4" name="aspectRatio" value="0.6666666666666666">' +
                                '<span class="docs-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="aspectRatio: 2 / 3">' +
                                '2:3 </span>' +
                            '</label>' +
                            '<label class="btn btn-secondary">' +
                                '<input type="radio" class="sr-only" id="aspectRatio5" name="aspectRatio" value="NaN">' +
                                '<span class="docs-tooltip" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="aspectRatio: NaN">' +
                                'Free </span>' +
                            '</label>' +
                            '</div>'+
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
                url:'/media/store',
                autoProcessQueue: false,
                // maxFiles: 1, 
                maxFilesize: 6,
                acceptedFiles: ".jpeg,.jpg,.png,.gif",
                addRemoveLinks: true,
                uploadMultiple: false,
                timeout: 5000,
                headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                // ..your other parameters..
          sending:function(file, xhr, formData){
            formData.append('folder_path',$("#tipe_gambar").val() );

            },
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
                        url: '/media/destroy',
                        data: { filename: filename,
                                name: name},
                        success: function (data){
                        // alert(data.name +" File has been successfully removed!"); 
                            console.log("Foto terhapus");
                            $('#simpan_data_gallery').addClass('hides');
                            $('input[name="original_filename"]').val(''); 
                            $('input[name="image_name_add"]').val(''); 
                            $('#image_caption').val(''); 
                            $('.dz-message').removeClass('hides');
                            $('.dz-message').addClass('shows');
                            disabled_form_data_gallery();
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
                var url_img = $('#base_url').val();
                file.previewElement.id = response.image_path;
                file.previewElement.name = response.image_name;
            
                // set new images names in dropzone’s preview box.
                var olddatadzname = file.previewElement.querySelector("[data-dz-name]");   
                file.previewElement.querySelector("img").alt = response.success;
                olddatadzname.innerHTML = response.image_name;
              
                file.previewElement.querySelector("img").style.width = "auto";
                file.previewElement.querySelector("img").style.height = "300px!important";
                file.previewElement.querySelector("img").src =  response.image_full_path;

                    enabled_form_data_gallery();
                    $('#simpan_data_gallery').removeClass('hides');
                    $('#simpan_data_gallery').addClass('shows');
                    $('#myform').html('');
                    $('#image_name_add').val(response.image_name);
                    $('#myform').append('<input type="hidden" class="form-control" name="original_filename" value="'+response.image_name+'" />');
                    $('.dz-message').addClass('hides');
                  
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
               
                    // console.log(reader.result);
                };
                // read uploaded file (triggers code above)
                reader.readAsDataURL(file);

                var a = $('#ukuran_gambar').val(); 
                var b = $('#id_kategori').val(); 
                    // alert(a);
                    if(b==23){
                        var height_img = 720;
                        var width_img =  720;
                    }else if(a==1){
                        var height_img = 720;
                        var width_img =  1280;
                    }else if(a==2){
                        var height_img = 1080;
                        var width_img =  1080;
                    }
                // $cropperModal.modal('show');
                $cropperModal.modal('show').on("shown.bs.modal", function () {
                    $no = ++c;
                 
                    // initialize cropper for uploaded image
                    var $image = $('#img');
                    var options = {
                      
                      // aspectRatio: 16 / 9,
                      // autoCropArea: 1,
                          viewMode: 1,
                          // aspectRatio: 1,
                          scalable:true,
                          movable: false,
                          cropBoxResizable: true,
                          data:{ //define cropbox size
                              height: height_img,
                              width:  width_img,
                          },
                           rotatable: true,
                          minContainerWidth: 250
                     
                      
                  };
                    $image.cropper(options);
                    var cropper = $image.data('cropper');
                    var $this = $(this);
                        $this

                            .on('click', 'input[name*="aspectRatio"]', function () {
                                var $this = $(this).val();
                                options.aspectRatio = $this; 
                                $image.cropper('destroy').cropper(options);
                            })
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
                    
                    var hasil = $img.cropper('getCroppedCanvas',{  height: height_img,
                                width:  width_img,});
                   
                    var blob = hasil.toDataURL("image/jpeg");

                    var wtmk = document.getElementById("watermark").src;
                    var wtmk_status = document.getElementById("watermark_gambar").value;
                    // console.log(src, wtmk);
                    if(wtmk_status==1){
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
                    }else{
                        // transform it to Blob object
                        var newFile = dataURItoBlob(blob);
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
                    }
                });
            });



            //edit data img gallery
            $(document).on('click', '#reload_data_gallery', function () {
              var page =1;
              infinteLoadMore(page);
              clear_form();
              clear_search();
              $('.action_gallery').addClass('hides');
            });

            function clear_search(){
              $('#search_image_data').val('');
            }
            function clear_form(){
                $('#img_detail').html('');
                $('#id_gallery').val('');
                $('#image_tipe').val('');
                $('#image_name').val('');
                $('#image_caption_text').val('');
                $('#image_url').val('');
                $('#action_gallery').addClass('hides');
            }
            
            function clearUpload(){
              $('.dropzone')[0].dropzone.files.forEach(function(file) { 
                file.previewElement.remove(); 
                $('.dz-message').removeClass('hides');
                $('.dz-message').addClass('shows');
              });

              $('.dropzone').removeClass('dz-started');
              $('#simpan_data_gallery').addClass('hides');
              $('input[name="original_filename"]').val(''); 
              $('input[name="image_name_add"]').val(''); 
              $('#image_caption').val(''); 
              disabled_form_data_gallery();
            }
            
            $(document).on('click', '.media_lib', function () {
                $("#reload_data_gallery").trigger("click");
            });


            $(document).on('click', '#edit_data_gallery', function () {
                      $("#image_name").attr("readonly", false); 
                      $("#image_caption_text").attr("readonly", false); 
                      enabled_update_detail();
                      $('#update_data_gallery').removeClass('hides');
                      $('#update_data_gallery').addClass('shows');
                      $('#cancel_data_gallery').removeClass('hides');
                      $('#cancel_data_gallery').addClass('shows');
                      $('#edit_data_gallery').addClass('hides');
                      $('#delete_data_gallery').addClass('hides');
            });

            $(document).on('click', '#cancel_data_gallery', function () {
                      $("#image_name").attr("readonly", true); 
                      $("#image_caption_text").attr("readonly", true); 
                      disabled_update_detail();
                      $('#edit_data_gallery').removeClass('hides');
                      $('#edit_data_gallery').addClass('shows');
                      $('#delete_data_gallery').removeClass('hides');
                      $('#delete_data_gallery').addClass('shows');
                      $('#update_data_gallery').addClass('hides');
                      $('#cancel_data_gallery').addClass('hides');
            });


            $(document).on('click', '#delete_data_gallery', function () {
                      var id = $('#id_gallery').val();
                     deleteConfirmation(id);
            });
    
              function deleteConfirmation(id) {
                Swal.fire({
                  title: 'Apakah Anda Yakin?',
                  text: "Data akan terhapus permanen!",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, Hapus!'
                }).then((result) => {
                  if (result.isConfirmed) {
                    // alert(id);
                    deleteGallery(id);
                  }
                })
              }

              function deleteGallery(id){
                $.ajax({  
                url:`{{ route("media.delete") }}`,
                method:'POST',  
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {'id' : id},  
                dataType : "JSON",  
                    success:function(data)  
                    {  
                   
                    if (data.status == "success") {  
                        Swal.fire('Success!',  data.message + ' !','success');  
                        $("#reload_data_gallery").trigger("click");
                        } 
                            else{
                        Swal.fire('Changes are not saved', '', 'info');
                        }
                    }
                });  
              }

            $(document).on('click', '#simpan_data_gallery', function () {
                save_gallery_baru();
            });

            //SAVE GALLERY BARU
            function save_gallery_baru(){
                var formdata = $('#form_data_gallery').serializeIncludeDisabled();
            
                $.ajax({  
                url:`{{ route("media.save") }}`,
                method:'POST',  
                data: formdata,  
                dataType : "JSON",  
                    success:function(data)  
                    {  
                    if (data.status == "success") {  
                        Swal.fire('Saved!',  data.message + ' !','success');  
                        setTimeout(function(){// wait for 5 secs(2)
                            // location.reload(); // then reload the page.(3)
                            clearUpload();
                            $(".media_lib").trigger("click");
                        }, 1000); 
                       
                        } 
                            else{
                        Swal.fire('Changes are not saved', '', 'info');
                        }
                    },
                    error: function (request, status, error) {
                        jsonValue = jQuery.parseJSON( request.responseText );
                        console.log(jsonValue.message);
                        // alert(jsonValue.message);
                }
                });  
            }

            //SAVE UPDATE GALLERY
            $(document).on('click', '#update_data_gallery', function () {
              save_gallery_update();
            });

             function save_gallery_update(){
                var formdata = $('#update_detail').serializeIncludeDisabled();
                var id = $('#id_gallery').val();
              
                $.ajax({  
                url:`{{ route("media.update") }}`,
                method:'POST',  
                data: formdata,  
                dataType : "JSON",  
                    success:function(data)  
                    {  
                     $("#cancel_data_gallery").trigger("click");
                     $("#reload_data_gallery").trigger("click");
                    
                     if (data.status == "success") {  
                        Swal.fire('Saved!',  data.message + ' !','success');  
                        setTimeout(function(){// wait for 5 secs(2)                           
                            document.getElementById('imageId_'+id).click();
                        }, 3000); 
                        } 
                            else{
                        Swal.fire('Changes are not saved', '', 'info');
                        }
                    },
                    error: function (request, status, error) {
                        jsonValue = jQuery.parseJSON( request.responseText );
                        console.log(jsonValue.message);
                }
                });  
            }
            
    </script>
@endpush