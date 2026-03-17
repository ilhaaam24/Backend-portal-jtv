@extends('layouts.materialize')
    @push('css')
      <link href="{{ asset('') }}assets/vendor/libs/dropzone/dropzone.min.css" rel="stylesheet">
      <script src="{{ asset('') }}assets/vendor/libs/dropzone/dropzone.min.js"></script>
      <link href="{{ asset('') }}assets/vendor/libs/cropper/cropper.css" rel="stylesheet"/>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>
      <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
      <style>
          .dz-image img {
              width: 100%;
              height: 100%;
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
                     id="form_create_sorot">
                     @csrf
                 @else
                     <form action="{{ route('konten.updatesorot', $item->no_urut) }}" method="POST"  autocomplete="off"
                         id="form_edit_sorot" enctype="multipart/form-data">
                         @csrf
                         @method('POST')
                 @endif
          <div class="d-flex justify-content-between">
            <h4 class="fw-bold mb-4"><span class="text-muted fw-light">Konten /</span> 
              @if ($status_form === "create_form")
              Add New
              @else
              Edit Sorot
              @endif
            </h4>
            <h4 class="fw-bold py-1 mb-3">
                @if ($status_form === "create_form")
                <button type="button" class="btn btn-primary btn-sm waves-effect" id="storeSorot">Simpan</button>
                <a href="{{ route('konten.sorot') }}" type="button" class="btn btn-secondary btn-sm waves-effect" id="backSorot"><span class="mdi mdi-close-thick"></span></a>
              @else
              <button type="button" class="btn btn-primary btn-sm waves-effect" id="updateSorot">Update</button>
              <a href="{{ route('konten.sorot') }}" type="button" class="btn btn-secondary btn-sm waves-effect" id="backSorot"><b>X</b></a>
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
 
                 <input type="hidden" class="form-control" id="id_sorot" name="id_sorot"
                 value="@if($status_form === "edit_form"){{$item->no_urut}}@else{{''}}@endif">


                    <div class="form-floating form-floating-outline mb-3 mt-3">
                        <input type="text" class="form-control" id="judul" name="judul" placeholder=" " 
                        aria-describedby="floatingInputJudul" value="@if ($status_form === "edit_form"){{ $item->judul }}@endif">
                        <label for="Judul">Judul</label>
                        <div id="floatingInputJudul" class="form-text">
                        </div>
                        @error('judul') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                    <div class="row">
                      <div class="col-12 col-md-6">
                        <div class="form-floating form-floating-outline mb-3 mt-3">
                            <select id="author-dropdown" class="select2 form-select" 
                            wire:model="tag" name="tag">
                                <option value="">-- Pilih Tag --</option>
                                @foreach ($list_tag as $data)
                                @if ($status_form === "create_form")
                                <option value="{{$data->seo_tag}}">
                                    {{$data->nama_tag}}
                                </option>
                                
                                @elseif ($status_form === "edit_form")
                                    <option value="{{ $data->seo_tag }}" 
                                        {{ $data->seo_tag == $item->tag ? 'selected' : '' }}>
                                        {{$data->nama_tag}}
                                    </option>
                                @endif
                                @endforeach
                            </select>
                            <label for="author">Tag Sorot</label>
                        </div>
                      </div>

                      {{-- <div class="form-floating form-floating-outline mb-3 mt-3">
                        <input type="text" class="form-control" id="tag" name="tag" placeholder=" " 
                        aria-describedby="floatingInputTag" value="@if ($status_form === "edit_form"){{ $item->tag }}@endif">
                        <label for="Tag">Tag</label>
                        <div id="floatingInputTag" class="form-text">
                        </div>
                        @error('tag') <span class="text-danger">{{ $message }}</span> @enderror
                      </div> --}}
                      <div class="col-12 col-md-6" style="display:none">
                      <div class="form-floating form-floating-outline mb-3 mt-3">
                        <input
                          id="TagifyCustomInlineSuggestion"
                          name="tag_sorot"
                          class="form-control h-auto"
                          placeholder="select"
                          value="@if($status_form == 'edit_form') {{ $tag->tag->nama_tag }}  @endif" />
                        <label for="TagifyCustomInlineSuggestion">Tag Sorot</label>
                      </div>
                      </div>

                      <div class="col-12 col-md-6">
                        <button type="button" class="btn btn-secondary w-100 get-image-berita mb-2 mt-3">Pilih gambar</button>
                 
                        <div class="image_area">
                            <div enctype="multipart/form-data" id="dropzone" class="dropzone">
              
                                <div>
                                    <div class="dz-message">
                                        <H5> Klik atau Drop Gambar</h5>
                                    </div>
                                
                                    <div id="myform" style="max-height:550px"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-floating form-floating-outline mb-3 mt-3" hidden>
                          <input type="text" class="form-control" id="edit_image_src" 
                          name="edit_image_src" 
                          value="@if($status_form == 'edit_form'){{$item->photo}}@endif"
                          placeholder=" " aria-describedby="floatingImageSrc">
                          <label for="Image Src">Image Src</label>
                          <div id="floatingImageSrc" class="form-text">
                          </div>
                      </div>
                      <div class="form-text bold" id="text_tgl_publish">Ukuran Gambar => <b>width: 400px ; height: 510px</b>*</div>
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
                            Media Library Sorot
                          </button>
                        </li>
                      
                      </ul>
                      <div class="tab-content">
                        <div class="tab-pane fade active show" id="navs-pills-top-home" role="tabpanel">
                          {{-- @foreach($images as $image)  --}}
                             {{-- @foreach($images as $image)  --}}
                             <div class="row row gy-4">
                                <div class="col-9">
                                    <div class="card shadow-0">
                                        {{-- Search Images --}}
                                        <div class="input-wrapper my-3 input-group input-group-lg input-group-merge px-5 mb-3">
                                            <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-magnify mdi-20px"></i></span>
                                            <input type="text" class="form-control search_image_data" placeholder="Search Image...." 
                                            id="search_image_data" aria-label="Search" aria-describedby="basic-addon1">
                                        </div>
                                    
                                        {{-- infinite loadmore --}}
                                        <div id="id_image_gallery" class="row px-3" style="height: 610px; overflow-y: scroll;">
                                            <!-- Results -->
                                        </div>

                                        {{-- button loadmore --}}
                                        <div class="text-center m-3">
                                            <button class="btn btn-primary" id="load-more" data-paginate="2">Load more...</button>
                                        </div>

                                        <!-- Data Loader -->
                                        <div class="auto-load text-center">
                                            <svg version="1.1" id="L9" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                x="0px" y="0px" height="60" viewBox="0 0 100 100" enable-background="new 0 0 0 0" xml:space="preserve">
                                                <path fill="#000"
                                                    d="M73,50c0-12.7-10.3-23-23-23S27,37.3,27,50 M30.9,50c0-10.5,8.5-19.1,19.1-19.1S69.1,39.5,69.1,50">
                                                    <animateTransform attributeName="transform" attributeType="XML" type="rotate" dur="1s"
                                                        from="0 50 50" to="360 50 50" repeatCount="indefinite" />
                                                </path>
                                            </svg>
                                        </div>

                                    </div>
                                </div>

                                <div class="col-3">
                                    <div class="card">
                                        <div class="card-header">
                                            <label for="Image" class="text-primary">Detail Image</label>
                                        </div>
                                        <div class="card-body">
                                            <form id="image" action="" autocomplete="off">
                                                <div id="img_detail" class="mb-4">
                                                </div>

                                                <div class="form-floating form-floating-outline mb-3 mt-3">
                                                    <input type="text" class="form-control" id="image_name" name="image_name" 
                                                    placeholder=" " aria-describedby="floatingImageName" readonly>
                                                    <label for="Image Name">Image Name</label>
                                                    <div id="floatingImageName" class="form-text">
                                                    </div>
                                                </div>

                                                <div class="form-group mb-3">
                                                    <div class="form-floating form-floating-outline mb-4">
                                                        <textarea class="form-control" id="image_caption_text" rows="3" placeholder="..." 
                                                        name="image_caption_text" wire:model="image_caption_text" style="font-size: small;height: auto;" readonly></textarea>
                                                        <label for="image_caption_text">Image Caption</label>
                                                    </div>
                                                </div>

                                                <div class="form-floating form-floating-outline mb-3 mt-3" hidden>
                                                    <input type="text" class="form-control" id="image_caption" name="image_caption" 
                                                    placeholder=" " aria-describedby="floatingImageCaption" >
                                                    <label for="Image Caption">Image Caption</label>
                                                    <div id="floatingImageCaption" class="form-text">
                                                    </div>
                                                </div>

                                                <div class="form-floating form-floating-outline mb-3 mt-3" hidden>
                                                    <input type="text" class="form-control" id="image_description" name="image_description" 
                                                    placeholder=" " aria-describedby="floatingImageDescription">
                                                    <label for="Image Description">Image Description</label>
                                                    <div id="floatingImageDescription" class="form-text">
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
                                                    <input type="text" class="form-control" id="image_url" name="image_url" 
                                                    placeholder=" " aria-describedby="floatingImageUrl" style="font-size: small;" readonly>
                                                    <label for="Image Url" >Image Url</label>
                                                    <div id="floatingImageUrl" class="form-text">
                                                    </div>
                                                </div>
                                                <div class="d-grid gap-2 d-md-flex justify-content-md-end" id="pilih_gambar_content">
                                                    <button type="button" class="btn btn-info btn-lg w-100 waves-effect" id="add-image-berita">Insert Into Post</button>
                                                   </div>

                                                <div class="d-grid gap-2 d-md-flex justify-content-md-end" id="pilih_gambar_utama">
                                                    <button type="button" class="btn btn-info btn-lg w-100 waves-effect" id="add-getimage-berita">Pilih Gambar Utama</button>
                                                </div>
                                            </form>
                                        </div>
                                    
                                    </div>
                                </div>{{-- END COL-3 --}}
                            </div>{{-- END ROW --}}                             
                            {{-- @endforeach --}}

                          {{-- <div class="p-0">
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
                          </div> --}}
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
          $(document).on('click', '#updateSorot', function () {
              save_edit_sorot();
          });

          $(document).on('click', '#storeSorot', function () {

              save_sorot_baru();
          });

          $('#search_getimage_data').on('keyup', function(){
              search_getimage_data();
          });

           //  TagifyCustomInlineSuggestion
        const TagifyCustomInlineSuggestionEl = document.querySelector('#TagifyCustomInlineSuggestion');
        var whitelist = [];

        $.ajax({
            url: "{{ route('whitelist.tag') }}",
            method: 'GET',
            data: {},
            success: function(response) {
                var suggestions = response;
              console.log('response',response);
                   suggestions.map((item)=> {
                    whitelist.push(item.nama_tag);
                     })

                let TagifyCustomInlineSuggestion = new Tagify(TagifyCustomInlineSuggestionEl, {
                    whitelist: whitelist,
                    maxTags: 1,
                    dropdown: {
                    maxItems: 20,
                    classname: 'tags-inline',
                    enabled: 0,
                    closeOnSelect: false
                    }
            });

    
            
            }
        });

          function search_getimage_data(){
                var keyword = $('#search_getimage_data').val();
                if(keyword!=''){
                    $.post('{{ route("getImageSearchsorot") }}',
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
            tampil_gambar_berita_edit();

             //clear disabled serialize form function
            $.fn.serializeIncludeDisabled = function () {
                let disabled = this.find(":input:disabled").removeAttr("disabled");
                let serialized = this.serialize();
                disabled.attr("disabled", "disabled");
                return serialized;
            };

           
          
        });

        function tampil_gambar_berita_edit()
            {
                $('#myform').html('');
                const note = document.querySelector('.dz-message');
             

                var formname = $('#nama_form').val();
                var url_img = $('#base_url').val();
                var edit_src_img  = $('#edit_image_src').val();
                var full_path_img = url_img + 'assets/sorot/' + edit_src_img;

                if(formname=='edit'){
                    note.style.display = 'none';
                    $('#myform').append(`<div class="card border-primary rounded mb-2"><img src="`+full_path_img+`" max-width: 90%; height="500px"/>
                                    </div>`);
                    $('#myform').append('<input type="hidden" name="addimage_sorot" class="form-control" value="'+edit_src_img+'" />');
                    $('#myform').append(`<div class="d-m d-flex justify-content-md-end">
                                        <button type="button" class="btn btn xs btn-danger clear-preview-getimage">
                                        cancel</button> </div>`)
              }
            }

        $(document).on('click', '.get-image-berita2', function () {
                var page =1;
                infinteLoadMore(page);
                $('#pilih_gambar_utama').attr("style", "display: none!important");
                load_getimage_gallery();
                $('#modalGetImage').modal('show');
            });
        
          //load data gallery getimage
          function load_getimage_gallery(){
                $.ajax({
                    url: "{{url('/getImages_gallerysorot')}}",
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
                var src_img =  url_img+ 'assets/upload-gambar/' + filename;
                var image_name = url_img + url;
                // var image_name = 'assets/upload-gambar/' + filename;

                // imageHandler2(src_img);
                $('#myform').append(`<div class="card border-primary rounded mb-2"><img src="`+image_name+`" max-width: 90%; height="auto"/>
                                    </div>`);
                $('#myform').append('<input type="hidden" name="addimage_sorot" class="form-control" value="'+filename+'" />');
                $('#myform').append(`<div class="d-m d-flex justify-content-md-end">
                                    <button type="button" class="btn btn xs btn-danger clear-preview-getimage">
                                    cancel</button> </div>`)
                $('#modalGetImage').modal('hide');

            });

            //Clear Image
            $(document).on('click', '.clear-preview-getimage', function () {
                $('#myform').html('');
                var val_img_src =  $('#edit_image_src').val();
                $('#myform').append('<input type="hidden" name="addimage_sorot" class="form-control" value="'+val_img_src+'" />');
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
                url:'/dropzone/store_sorot',
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
                        console.log(file);
                            
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
                        url: '/dropzone/delete_sorot',
                        data: { filename: filename,
                                name: name},
                        success: function (data){
                        // alert(data.name +" File has been successfully removed!"); 
                                    console.log("Foto terhapus");
                            // $('input[name="addimage_sorot"][value="'+data.name+'"]').remove(); 
                           var val_img_src =  $('#edit_image_src').val();
                            $('input[name="addimage_sorot"]').val(val_img_src); 
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
                console.log(file); 
                console.log(response); 
                // set new images names in dropzone’s preview box.
                var olddatadzname = file.previewElement.querySelector("[data-dz-name]");   
                file.previewElement.querySelector("img").alt = response.success;
                olddatadzname.innerHTML = response.success;
                    $('#myform').html('');
                    $('#myform').append('<input type="hidden" class="form-control" name="addimage_sorot" value="'+response.image_name+'" />');
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
                    
                    // alert(++c);
                    // initialize cropper for uploaded image
                    var $image = $('#img');
                    $image.cropper({
                        // aspectRatio: 16 / 9, 
                      /*   guides : true,
                        center: true, */
                        // autoCropArea: 1,
                        viewMode: 1,
                        movable: false,
                        cropBoxResizable: true,
                        data:{ //define cropbox size
                          width: 400,
                          height:  510,
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
                    var blob = $img.cropper('getCroppedCanvas').toDataURL("image/jpeg");
                    // transform it to Blob object
                    var newFile = dataURItoBlob(blob);
                    // set 'cropped to true' (so that we don't get to that listener again)
                    newFile.cropped = true;
                    // assign original filename
                    newFile.name = cachedFilename;

                    // add cropped file to dropzone
                    myDropzone.addFile(newFile);
                    // upload cropped file with dropzone
                    myDropzone.processQueue();
                    
                    $cropperModal.modal('hide');
                    $('.modal').find('.image-container').html('');
                });

            });
        
            $(document).on('hidden.bs.modal','.modal_upload', function () {
               $(this).find(".image-container").html('');
            });

             //SAVE EDIT SOROT
             function save_edit_sorot(){
                var formdata = $('#form_edit_sorot').serializeIncludeDisabled();

                $.ajax({  
                url:`{{ route("konten.updatesorot") }}`,
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

             //SAVE SOROT BARU
             function save_sorot_baru(){
                var formdata = $('#form_create_sorot').serializeIncludeDisabled();

                $.ajax({  
                url:`{{ route("konten.storesorot") }}`,
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

            // get image
            //INFINITE LOAD
            var ENDPOINT = "{{route('getImages_sorot_gallery')}}";
            var page = 1;

            $(document).on('click', '.get-image-berita', function () {
                var page =1;
                infinteLoadMore(page);
                $('#pilih_gambar_utama').attr("style", "display: none!important");
                // load_getimage_gallery();
                $('#modalGetImage').modal('show');
            });

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
                        }
                    })
                    .done(function (response) {
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
                    })
                    .fail(function (jqXHR, ajaxOptions, thrownError) {
                        console.log('Server error occured');
                    });
            }   

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
                    var imagecaption = $(this).data("imagecaption");
                    var imagedescription = $(this).data("imagedescription");
                    var src = $(this).data("src");
      
                    var url_img = $('#base_url').val();
                    var src_img =  url_img+filename;
                    $('#lib_'+id_img).addClass("border-primary border-2 rounded");
                 
                    $('#lib_'+id_img).append($checklist);
                
                    
                    $tampil_img = '<div class="card"><img src="'+ src +'" height="160px" width="310px" class="img rounded"></div>';
                    
                    $('#img_detail').html('');
                    $('#img_detail').append($tampil_img);
                    $('#image_name').val(originalname);
                    $('#image_caption').val(imagecaption);
                    $('textarea#image_caption_text').val(imagecaption);
                    $('#image_description').val(imagedescription);
                    $('textarea#image_description').val(imagedescription);
                    $('#image_url').val(src);
            });
           
            
      </script>

@endpush