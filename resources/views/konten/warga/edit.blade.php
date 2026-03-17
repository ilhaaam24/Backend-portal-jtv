@extends('layouts.materialize')
    @push('css')
    <!-- Theme included stylesheets -->
    <link href="{{ asset('') }}assets/vendor/libs/quill/quill.snow.css" rel="stylesheet">
    <link href="{{ asset('') }}assets/vendor/libs/quill/quill.bubble.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/quill/katex.css" />
    <link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/quill/editor.css" />
    <link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/flatpickr/flatpickr.css" />
    <link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/pickr/pickr-themes.css" />
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

        .dropzone .dz-preview .dz-image {
                border-radius: 20px;
                overflow: hidden;
                width: auto;
                height: 120px;
                position: relative;
                display: block;
                z-index: 10;
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
        .tipe_news_css{
            padding-right: calc(var(--bs-gutter-x) * 0)!important; 
            padding-left: calc(var(--bs-gutter-x) * 0)!important;
        }
        
    </style>
</head>
    
    @endpush

    @section('content')
        <div class="container-xxl flex-grow-1 container-p-y">
             {{-- check if there is a notif.success flash session --}}
             @if (Session::has('notif.success'))
             <div class="bg-blue-300 mt-2 p-4">
                 {{-- if it's there then print the notification --}}
                 <span class="text-bold">{{ Session::get('notif.success') }}</span>
             </div>
             @endif

             @if ($status_form === "create_form")
             <form id="form_create_jurnalismewarga" method="POST" autocomplete="off" enctype="multipart/form-data">
                @csrf
            @else
                <form  autocomplete="off"  id="form_edit_jurnalismewarga" enctype="multipart/form-data">
                    @csrf
                  
            @endif
                    <input type="hidden" id="base_url" name="base_url" value="{{ url('/') }}/" class="form-control">
                <div>
                    <div class="d-flex justify-content-between">
                        <h4 class="fw-bold py-1 mb-3">
                            <span class="text-muted fw-light">Opini /</span>
                            @if ($status_form === "create_form")
                            Input Baru
                           @else
                            Edit
                           @endif
                  
                        </h4>
                        <h4 class="fw-bold py-1 mb-3">
                            @if ($status_form === "create_form")
                            <button type="button" id="createStore" class="btn btn-primary btn-md waves-effect">Simpan</button>
                            <a href="{{ route('konten.jurnalismewarga') }}" type="button" class="btn btn-secondary btn-md waves-effect" id="backNews"><span class="mdi mdi-close-thick"></span></a>
                           @else
                           <button type="button" class="btn btn-primary btn-md waves-effect" id="updateStore">Update</button>
                           <a href="{{ route('konten.jurnalismewarga') }}" type="button" class="btn btn-secondary btn-sm waves-effect" id="backNews"><span class="mdi mdi-close-thick"></span></a>
                           @endif   
                      
                        </h4>
                    </div>
                </div>
                
                <input type="hidden" class="form-control" id="nama_form" 
                value="@if($status_form === "create_form"){{'create'}}@else{{'edit'}}@endif">

                <input type="hidden" class="form-control" id="id_opini" name="id_opini"
                value="@if($status_form === "edit_form"){{$item->id_opini}}@else{{''}}@endif">
               
                <div class="row justify-content-center">
                    <div class="col-12 col-md-8 mb-3">
                        <div class="card">
                          <h5 class="card-header">Form Opini</h5>
                          <div class="card-body">
                          {{-- {{ $id_pengguna->name }} --}}

                            <input type="hidden" name="id_pengguna" id="id_pengguna" class="form-control"
                                value="{{ $pengguna_data->id_pengguna }}">
                                @livewire('opini.seo-opini', ['data' => $item ?? null])
           
                            <div class="row mb-3">
                                <div class="col-12 col-md-6">
                                  
                                        {{-- <label for="author">Author</label> --}}
                                        {{-- <input type="hidden" class="form-control" id="id_pengguna" name="id_pengguna" value="{{ Auth::user()->id }}"> --}}
                                        <div class="form-floating form-floating-outline mb-3 mt-3">
                                            <select id="author-dropdown" class="select2 form-select" 
                                            id="tipe_opini"  style="text-transform: uppercase"
                                            wire:model="tipe_opini" name="tipe_opini">
                                                <option value="">-- Pilih Tipe Tulisan --</option>
                                                @foreach ($list_tipetulisan as $data)
                                                @if ($status_form === "create_form")
                                                <option value="{{$data->seo}}">
                                                    {{$data->judul}}
                                                </option>
                                                
                                                @elseif ($status_form === "edit_form")
                                                    <option value="{{ $data->seo }}" 
                                                        {{ $data->seo == $item->tipe_opini ? 'selected' : '' }}>
                                                        {{ Str::of($data->kategori)->upper().' - '.$data->judul}}
                                                    </option>
                                                @endif
                                                @endforeach
                                            </select>
                                            <label for="author">Tipe Tulisan</label>
                                        </div>
                                  
                                </div>
                              
                            </div>

                            <div class="form-group mb-3">
                                <button type="button" class="btn btn-secondary waves-effect waves-light"
                                data-bs-toggle="modal" data-bs-target="#modalMediaLib"
                                id="add_media">
                                    <i class="tf-icons mdi mdi-image-multiple-outline me-1"></i>Tambah Media
                                  </button>
                            </div>
                           
                            <div class="mb-3 quill-editor" id="editor" name="editor" spellcheck="false">
                            </div>

                        
                            <textarea name="artikel_opini" id="artikel_opini" 
                            style="display:none;"
                            class="form-control mb-3" cols="30" 
                            rows="10">@if($status_form == 'edit_form'){{ $item->artikel_opini}} @endif</textarea>

                        
                          </div>
                        </div>
                    </div>{{-- !col-md8 --}}

                    <div class="col-12 col-md-4">
                       
                            <div class="card mb-3">
                              <div class="card-header">
                                <h5 class="card-action-title" style="margin-bottom: 0rem;">Setting</h5>
                              </div>
                              <div class="card-body">
                                <div class="d-flex align-items-end row">
                                  <div class="accordion accordion-header-primary p-0" id="accordionStyle1">
                                    <div class="accordion-item shadow-0 active">
                                        <h2 class="accordion-header">
                                          <button type="button" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#accordionStyle1-0" aria-expanded="false">
                                            Publish
                                          </button>
                                        </h2>
                                        <div id="accordionStyle1-0" class="accordion-collapse collapse show" data-bs-parent="#accordionStyle1">
                                          <div class="accordion-body">
                                                <div class="panel panel-default">
                                                    <div class="panel-body">						
                                                    <div class="item-list-body">
                                                            <div class="form-floating form-floating-outline mb-3">
                                                                <select wire:model="status_opini" id="status_opini" class="select form-select" name="status_opini">
                                                                    <option value=""> Pilih </option>
                                                                    <option value="Draft"  
                                                                        @if($status_form == 'create_form')
                                                                            selected
                                                                        @elseif($status_form == 'edit_form')
                                                                        {{ $item->status_opini == 'Draft' ? 'selected' : '' }} 
                                                                        @endif
                                                                       
                                                                       {{--  {{ $status_form == 'create_form' ? 'selected':'' }} --}}> Draft </option>
                                                                    <option value="Publish" 
                                                                    @if($status_form == 'edit_form')
                                                                        {{ $item->status_opini == 'Publish' ? 'selected' : '' }} 
                                                                        @endif>Publish</option>
                                                                </select>
                                                                <label for="status_opini">Status</label>
                                                             
                                                            </div>
                    
                                                             <!-- Datetime Picker-->
                                                    <div class="form-floating form-floating-outline mb-3" id="div-tgl-publish">
                                                        <input
                                                            type="text"
                                                            class="form-control flatpickr-datetime"
                                                            placeholder="Tanggal Publish"
                                                            value="@if($status_form == 'edit_form'  )
                                                                @if($item->date_publish_opini !='0000-00-00 00:00:00')
                                                                {{ $item->date_publish_opini}}
                                                                @else
                                                                2100-01-01 00:00 @endif
                                                            @endif"
                                                            id="tgl-publish"  name="date_publish_opini"/>
                                                        <label for="tgl-publish">Tanggal Publish</label>
                                                        <div class="form-text" id="text_tgl_publish">Pilih Tanggal dan Waktu Schedule Publish Opini*</div>
                                                    </div>
                                                    <!-- /Datetime Picker-->
                                                                                                                                                             
                                                      </div>	
                                                    
                                                    </div>
                                                </div>
                                          </div>
                                        </div>
                                      </div>

                                    <div class="accordion-item shadow-0">
                                      <h2 class="accordion-header">
                                        <button type="button" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#accordionStyle1-2" aria-expanded="false">
                                          Tags
                                        </button>
                                      </h2>
                                      <div id="accordionStyle1-2" class="accordion-collapse collapse show" data-bs-parent="#accordionStyle1">
                                        <div class="accordion-body">
                                            <div class="form-floating form-floating-outline mb-3">
                                                <input type="text" wire:model="tag_opini" class="form-control" id="tag_opini" name="tag_opini"
                                                placeholder="Tag Opini" 
                                                value="@if($status_form == 'edit_form'){{$item->tag_opini}}@endif"
                                                aria-describedby="floatingInputTags">
                                                <label for="tag_opini">Tags</label>
                                                <div id="floatingInputTags" class="form-text">
                                                </div>
                                            </div>                                           
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                     
                          <div class="card card-action mb-3">
                            <div class="card-header">
                                <h5 class="card-action-title" style="margin-bottom: 0rem;">Featured Image</h5>
                              <div class="card-action-element">
                                <ul class="list-inline mb-0">
                                  <li class="list-inline-item">
                                    <a href="javascript:void(0);" class="card-collapsible"><i class="tf-icons mdi mdi-chevron-up"></i></a>
                                  </li>
                                </ul>
                              </div>
                            </div>
                            <div class="collapse show" style="">
                              <div class="card-body">

                                <button type="button" class="btn btn-secondary w-100 get-image-berita mb-2">Pilih gambar</button>
                                {{-- action="{{ route('dropzone.store') }}" --}}
                                <div class="image_area">
                                    <div enctype="multipart/form-data" id="dropzone" class="dropzone">
                                        {{-- @csrf --}}
                                        <div>
                                            <div class="dz-message">
                                               Klik atau Drop Gambar Opini disini
                                            </div>
                                            {{-- <div class="fallback">
                                                <input name="file" type="file">
                                            </div> --}}
                                            <div id="myform"></div>
                                        </div>
                                    </div>
                                </div>
                               
                                <div class="form-floating form-floating-outline mb-3 mt-3">
                                    <input type="text" class="form-control" id="caption_gambar_opini" 
                                    name="caption_gambar_opini" 
                                    value="@if($status_form == 'edit_form'){{$item->caption_gambar_opini}}@endif"
                                    placeholder=" " aria-describedby="floatingInputGambar">
                                    <label for="Gambar Opini">Caption Gambar Opini</label>
                                    <div id="floatingInputGambar" class="form-text">
                                    </div>
                                </div>

                                <div class="form-floating form-floating-outline mb-3 mt-3"
                                style="display: none;">
                                    <input type="text" class="form-control" id="edit_image_src" 
                                    name="edit_image_src" 
                                    value="@if($status_form == 'edit_form'){{$item->gambar_opini}}@endif"
                                    placeholder=" " aria-describedby="floatingImageSrc">
                                    <label for="Image Src">Image Src</label>
                                    <div id="floatingImageSrc" class="form-text">
                                    </div>
                                </div>                            
                              
                              </div>
                            </div>
                          </div>
                        </form>
                    </div>  {{-- !col-md4 --}}
                </div>

            <!-- Modal Trigger -->
            <div class="modal fade" id="modalMediaLib" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-fullscreen" role="document">
                  <div class="modal-content">
                    <div class="modal-body" style="background:#f7f7f9;">
                        <button
                          type="button"
                          class="btn-close btn btn-light p-3 rounded-circle position-absolute"
                          data-bs-dismiss="modal"
                          aria-label="Close" style="right: 30px;z-index: 5;"></button>
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
                            <div class="tab-content">
                              <div class="tab-pane fade active show" id="navs-pills-top-home" role="tabpanel">
                                    {{-- @foreach($images as $image)  --}}
                                    <div class="row gy-2">
                                        <div class="col-12 col-md-9">
                                            <div class="card shadow-0">
                                                {{-- Search Images --}}
                                                <div class="input-wrapper my-2 input-group input-group-lg input-group-merge px-5 mb-3">
                                                    <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-magnify mdi-20px"></i></span>
                                                    <input type="text" class="form-control search_image_data" placeholder="Search Image...." 
                                                    id="search_image_data" aria-label="Search" aria-describedby="basic-addon1">
                                                </div>
                                            
                                                {{-- infinite loadmore --}}
                                                <div id="id_image_gallery" class="row px-1" style="max-height: 510px; overflow-y: scroll;">
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
    
                                        <div class="col-12 col-md-3">
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

                                                        <div class="form-floating form-floating-outline mb-3 mt-3 hides">
                                                            <input type="text" class="form-control" id="image_caption" name="image_caption" 
                                                            placeholder=" " aria-describedby="floatingImageCaption">
                                                            <label for="Image Caption">Image Caption</label>
                                                            <div id="floatingImageCaption" class="form-text">
                                                            </div>
                                                        </div>

                                                        <div class="form-floating form-floating-outline mb-3 mt-3 hides">
                                                            <input type="text" class="form-control" id="image_description" name="image_description" 
                                                            placeholder=" " aria-describedby="floatingImageDescription">
                                                            <label for="Image Description">Image Description</label>
                                                            <div id="floatingImageDescription" class="form-text">
                                                            </div>
                                                        </div>

                                                        <div class="form-group mb-3 hides">
                                                            <div class="form-floating form-floating-outline mb-4">
                                                                <textarea class="form-control" id="image_caption_text" rows="3" placeholder="..." 
                                                                name="image_caption_text" wire:model="image_caption_text" style="font-size: small;height: auto;"></textarea>
                                                                <label for="image_caption_text">Image Caption</label>
                                                            </div>
                                                        </div>

                                                        <div class="form-group mb-3 hides">
                                                            <div class="form-floating form-floating-outline mb-4">
                                                                <textarea class="form-control" id="image_description_text" rows="4" placeholder="..."
                                                                name="image_description_text" wire:model="image_description_text" style="font-size: small;height: auto;"></textarea>
                                                                <label for="image_description_text">Image Description</label>
                                                            </div>
                                                        </div>

                                                        <div class="form-floating form-floating-outline mb-3 mt-3">
                                                            <input type="text" class="form-control" id="image_url" name="image_url" readonly
                                                            placeholder=" " aria-describedby="floatingImageUrl" style="font-size: small;">
                                                            <label for="Image Url">Image Url</label>
                                                            <div id="floatingImageUrl" class="form-text">
                                                            </div>
                                                        </div>
                                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end" id="pilih_gambar_content">
                                                            <button type="button" class="btn btn-info btn-lg w-100 waves-effect" id="add-image-opini">Insert Into Post</button>
                                                           </div>

                                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end" id="pilih_gambar_utama">
                                                            <button type="button" class="btn btn-info btn-lg w-100 waves-effect" id="add-getimage-opini">Pilih Gambar Utama</button>
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
                                        <label for="document">Documents File Upload</label>
                                        <div class="dropzone" id="dropzoneImage"></div>
                                        <div id="form_id"></div>
                                    </div>
                                </div>
                            </div>
                            {{-- END TAB OF CONTENT --}}
                        </div>
                    </div>
                  </div>
                </div>
             </div>


        @endsection

        @push('js')

    <script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>
    <script src="{{ asset('assets/js/forms-selects.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/flatpickr/flatpickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/pickr/pickr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cropper/cropper.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cropper/jquery-cropper.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cropper/jquery-cropper.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/image-resize.min.js') }}"></script>
    <script>
        //form edit
          //INFINITE LOAD
          var ENDPOINT = "{{ route('getImages_galleryOpini') }}";
          var page = 1;

          //Pilih Get Image Berita 
          $(document).on('click', '.get-image-berita', function () {
                var page = 1;
                infinteLoadMore(page);
                
                $('#pilih_gambar_content').attr("style", "display: none !important");
                $('#multi_upload').attr("style", "display: none !important");
                $('#modalMediaLib ').modal('show');
            });

            //Pilih Gambar Utama
            $(document).on('click', '#add-getimage-opini', function () {
                $('#myform').html('');
                const note = document.querySelector('.dz-message');
                note.style.display = 'none';

                $('#modalMediaLib').modal('hide');

                var url_img = $('#base_url').val();
                var filename =  $('#image_name').val();
                var src_img =  url_img+ 'assets/upload-gambar/' + filename;
                var image_name = 'assets/upload-gambar/' + filename;

                // imageHandler2(src_img);
                $('#myform').append(`<div class="card border-primary rounded mb-2"><img src="`+src_img+`" max-width: 90%; height="120px"/>
                                    </div>`);
                $('#myform').append('<input type="hidden" name="addimage_berita" class="form-control" value="'+filename+'" />');
                $('#myform').append(`<div class="d-m d-flex justify-content-md-end">
                                    <button type="button" class="btn btn xs btn-danger clear-preview-getimage">
                                    cancel</button> </div>`)
        
            });

            //clear preview data
            $(document).on('click', '.clear-preview-getimage', function () {
                $('#myform').html('');
                var val_img_src =  $('#edit_image_src').val();
                $('#myform').append('<input type="hidden" name="addimage_berita" class="form-control" value="'+val_img_src+'" />');
                const note = document.querySelector('.dz-message');
                note.style.display = 'block';
            });


            // PILIH MEDIA INSERT TO POST
            $('#add_media').on('click', function () {
                var page =1;
                infinteLoadMore(page);
                $('#pilih_gambar_utama').attr("style", "display: none!important");
            });

            $(document).on('click', '.media_lib', function () {
                var page =1;
                infinteLoadMore(page);
            });
            
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
            
            // Button insert into post
            $(document).on('click', '#add-image-opini', function () {
                var url_img = $('#base_url').val();
                var filename =  $('#image_name').val();
                var src_img =  url_img+ 'assets/upload-gambar/' + filename;

                imageHandler2(src_img);
                $('#modalMediaLib').modal('hide');
            });
            
        
            //CARI IMAGE GALLERY
            $('#search_image_data').on('keyup', function(){
                var page = 1;
                search_image_data(page);
            });
            
            function search_image_data(page){
                var keyword = $('#search_image_data').val();
                if(keyword!=''){
                    $.ajax({
                        url: '{{ route("getImageSearchOpini") }}' + "/?page=" + page,
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
                                // alert(response.html);
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
           


        //Pilih Get Image Berita 
            /*   $(document).on('click', '.get-image-berita', function () {
                    load_getimage_gallery();
                    $('#modalGetImage').modal('show');
                });
            */
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
            $('#getimage_caption').val(imagecaption);
            $('#getimage_description').val(imagedescription);

            });
            
            $(document).on('click', '#add-getimage-opini', function () {
                $('#myform').html('');
                const note = document.querySelector('.dz-message');
                note.style.display = 'none'; 

                var url_img = $('#base_url').val();
                var filename =  $('#image_name').val();
                var image_url =  $('#image_url').val();
                var src_img =  url_img+ 'assets/upload-gambar/' + filename;
                var image_name = 'assets/upload-gambar/' + filename;
                
                $('#myform').append(`<div class="card border-primary rounded mb-2"><img src="`+src_img+`" max-width: 90%; height="120px"/>
                                    </div>`);
                $('#myform').append('<input type="hidden" name="gambar_opini" class="form-control" value="'+image_name+'" />');
                $('#myform').append(`<div class="d-m d-flex justify-content-md-end">
                                    <button type="button" class="btn btn xs btn-danger clear-preview-getimage">
                                    cancel</button> </div>`)
                $('#modalGetImage').modal('hide');

            });

            $(document).on('click', '.clear-preview-getimage', function () {
                $('#myform').html('');
                var val_img_src =  $('#edit_image_src').val();
                $('#myform').append('<input type="hidden" name="gambar_opini" class="form-control" value="'+val_img_src+'" />');
                const note = document.querySelector('.dz-message');
                note.style.display = 'block';
            });

        var uploadedDocumentMap = {}
        var myDropzonex = new Dropzone(
            "#dropzoneImage", {
          url: "{{ route('uploads') }}",
          maxFilesize: 6, // MB
          addRemoveLinks: true,
          headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function (file, response) {
            file.previewElement.id = response.image_path;
                file.previewElement.name = response.image_name;
                var olddatadzname = file.previewElement.querySelector("[data-dz-name]");   
                file.previewElement.querySelector("img").alt = response.image_name;
                olddatadzname.innerHTML = response.image_name;

            $('#form_id').append('<input type="hidden" name="document[]" value="' + file.previewElement.id + '">')
            uploadedDocumentMap[file.name] = response.image_name
          },
          removedfile: function (file) {
            if (this.options.dictRemoveFile) 
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
                        url: '/dropzone/delete',
                        data: { filename: filename,
                                name: name},
                        success: function (data){
                            const note = document.querySelector('.dz-message');
                            note.style.display = 'block';
                        // alert(data.name +" File has been successfully removed!"); 
                                    console.log("Foto terhapus");
                          
                            $('#form_id').find('input[name="document[]"][value="' + data.src + '"]').remove()
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
                    $('#form_id').find('input[name="document[]"][value="' + data.src + '"]').remove()
                }
         
          },
          init: function () {
            @if(isset($project) && $project->document)
              var files =
                {!! json_encode($project->document) !!}
              for (var i in files) {
                var file = files[i]
                this.options.addedfile.call(this, file)
                file.previewElement.classList.add('dz-complete')
                $('#form_id').append('<input type="hidden" name="document[]" value="' + file.file_name + '">')
              }
            @endif
          }
        }
        )
      </script>
    <script type="text/javascript">
        /* setup quill js */
            var toolbarOptions = [
                ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
                [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
                [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                [{ 'font': [] }],
                [{ 'align': [] }],
                ['clean'],                                         // remove formatting button
                ['image'],
                ['link'] 
            ];


            var quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
                imageResize: {
                        displaySize: true
                        },
                toolbar: {
                        container: toolbarOptions,
                    }
            }
            });

            quill.on('text-change', () => {
            const { ops } = quill.getContents();

            const justHtml = quill.root.innerHTML;

            $(`textarea[name="artikel_opini"]`).val(justHtml);
            // $(`textarea[name="artikel_opini"]`).val(JSON.stringify(ops));
            });

            function imageHandler() {
            var range = this.quill.getSelection();
            var value = prompt('What is the image URL');
            if(value){
                this.quill.insertEmbed(range.index, 'image', value, Quill.sources.USER);
            }
            }

            function imageHandler2(val) {
            var range = this.quill.getSelection();
            var value = val;
                if(value!=null && range!=null){
                    try{
                    this.quill.insertEmbed(range.index, 'image', value, Quill.sources.USER);
                    var data = '1';
                    return data;
                    } catch(err){
                        console.log(err);
                    }
                }else{
                    Swal.fire('Maaf!',  'silakan tempatkan kursor anda pada text editor!','suWARNINGccess');  
                    var data = '0';
                    return data;
                } 
            }

           /*  $('#add_media').on('click', function () {
                load_image_gallery();
            }); */

          /*   $(document).on('click', '.media_lib', function () {
                load_image_gallery();
            }); */
            
            $(document).on('click', '.img_gallerys', function (e) {
                var header = document.getElementById("id_image_gallery");
                var btns = header.getElementsByClassName("crd-img");
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

                    $('#image_name').val(originalname);
                    $('#image_caption').val(imagecaption);
                    $('#image_description').val(imagedescription);
            });
            
       
            $(document).on('click', '.close_crop', function () {
                $('.modal').find('.image-container').html('');
            });
            
            $('#search_getimage_data').on('keyup', function(){
                $id = 'single';
                search_getimage_data($id);
            });

            $('#search_image_data').on('keyup', function(){
                $id = 'multi';
                search_getimage_data($id);
            });
            
            function search_getimage_data($id){
              
                if($id=="single"){
                    var keyword = $('#search_getimage_data').val();
                }else{
                    var keyword = $('#search_image_data').val();
                }
             
                if(keyword!=''){
                    $.post('{{ route("getImageSearchOpini") }}',
                        {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            keyword:keyword
                        },
                        function(data){
                            // console.log(data);
                            
                            if($id=="single"){
                                $("#id_getimage_gallery").html('');
                            }else{
                               $("#id_image_gallery").html('');
                            }
                           
                            if(data.list_getimages){
                                $.each(data.list_getimages, function (key, value) {
                                    var isi_multi_gallery = `<div class="col-2 mb-2">
                                        <div class="card crd-img gap-2 d-grid" id="lib_img_`+ value.id +`" >
                                        <div id="image_`+ value.id +`">
                                            <img class="img_gallerys img rounded-top" 
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
                                    `;
                                    var isi_gallery = `<div class="col-2 mb-2">
                                    <div class="card crd-getimg gap-2 d-grid" id="lib_img_`+ value.id +`">
                                        <div id="image_`+ value.id +`">
                                            <img class="getimg_gallerys img rounded-top" 
                                            data-id="img_`+ value.id +`" 
                                            data-originalname="`+ value.original_filename +`"
                                            data-filename="`+ value.filename +`"
                                            data-imagecaption="`+ value.caption +`"
                                            data-imagedescription="`+ value.description +`"
                                            src="{{ url('') }}/`+ value.filename +`" max-width: 90%; height="120px">
                                            <div class="w-100 badge bg-label-light text-muted rounded-bottom mb-2 img_name_preview">`+ ellipsify(value.original_filename)     +`</div>
                                            </div>
                                        </div>
                                    </div>
                                    `;
                                    if($id=="single"){
                                        $("#id_getimage_gallery").append(isi_gallery);
                                    }else{
                                        $("#id_image_gallery").append(isi_multi_gallery);
                                    }
                                });
                            }
                        });
                }else{
                    if($id=="single"){
                        load_getimage_gallery();
                    }else{
                        load_image_gallery();
                    }
                    
                }
                
            }

            //load data gallery image
            function load_image_gallery(){
            $.ajax({
                url: "{{route('getImages_galleryOpini')}}",
                type: "GET",
                dataType: 'json',
                success: function (result) {
                    // var photosObj = $.parseJSON(result.images);
                    $("#id_image_gallery").html('');
                    // console.log(result.images);
                    if(result.images){
                        $.each(result.images, function (key, value) {
                            $("#id_image_gallery").append( 
                            `<div class="col-2 mb-2">
                                <div class="card crd-img gap-2 d-grid" id="lib_img_`+ value.id +`" >
                                <div id="image_`+ value.id +`">
                                    <img class="img_gallerys img rounded-top" 
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
                        $("#id_image_gallery").append('No Image Found');
                    }
                        
                }
            });
            }

            //load data gallery getimage
            function load_getimage_gallery(){
                $.ajax({
                    url: "{{route('getImages_galleryOpini')}}",
                    type: "GET",
                    dataType: 'json',
                    success: function (result) {
                        // var photosObj = $.parseJSON(result.images);
                        $("#id_getimage_gallery").html('');
                        // console.log(result.images);
                        if(result.images){
                            $.each(result.images, function (key, value) {
                                $("#id_getimage_gallery").append( 
                                `<div class="col-2 mb-2">
                                <div class="card crd-getimg gap-2 d-grid" id="lib_img_`+ value.id +`" >
                                    <div id="image_`+ value.id +`">
                                        <img class="getimg_gallerys img rounded-top" 
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


            $.fn.cropper.noConflict();
            $('.modal_upload').on('hidden.bs.modal', function () {
                $(this).find(".image-container").html('');
            });

            //close modal Scrollable
            $('#modalMediaLib').on('hidden.bs.modal', function () {
                $('#img_detail').html('');
                $('#image_name').val('');
                $('#image_caption').val('');
                $('#image_description').val('');
                $('#image_url').val('');
                $('#search_image_data').val('');
                $('#pilih_gambar_content').attr("style", "display: block");
                $('#pilih_gambar_utama').attr("style", "display: block");
                $('#multi_upload').attr("style", "display: block");
            });

            //close modal Scrollable
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


            // initialize DROPZONE JS BEGIN GAMBAR OPINI
            Dropzone.autoDiscover = false;
            var c = 0;

            var myDropzone = new Dropzone(
            "#dropzone",
            {
                url:'/dropzone/store_opini',
                autoProcessQueue: false,
                maxFiles: 1, 
                maxFilesize: 6,
                acceptedFiles: ".jpeg,.jpg,.png,.gif",
                addRemoveLinks: true,
                uploadMultiple: false,
                thumbnailWidth: 250,
                thumbnailHeight: 120,
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
                        url: '/dropzone/delete_opini',
                        data: { filename: filename,
                                name: name},
                        success: function (data){
                            const note = document.querySelector('.dz-message');
                            note.style.display = 'block';
                            // alert(data.name +" File has been successfully removed!"); 
                                    console.log("Foto terhapus");
                            // $('input[name="gambar_opini"][value="'+data.src+'"]').remove(); 
                            var val_img_src =  $('#edit_image_src').val();
                            $('input[name="gambar_opini"]').val(val_img_src); 
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
              /*   console.log(file); 
                console.log(response);  */
                // set new images names in dropzone’s preview box.
              
        
                var olddatadzname = file.previewElement.querySelector("[data-dz-name]");   
                file.previewElement.querySelector("img").alt = response.success;
                olddatadzname.innerHTML = response.success;
                    $('#myform').html('');
                    $('#myform').append('<input type="hidden" class="form-control" name="gambar_opini" value="'+response.image_name+'" />');
             
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

            // listen to thumbnail event PROSES CROPING DAN SIMPAN KE GALLERY OPINI 
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
                        // autoCropArea: 1,
                        viewMode: 1,
                        movable: false,
                        cropBoxResizable: true,
                        rotatable: true,
                        minContainerWidth: 250
                        
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

            'use strict';

            //datetime picker
            (function () {
                // Flat Picker
                // --------------------------------------------------------------------
                const flatpickrDate = document.querySelector('.flatpickr-date'),
                flatpickrDateTime = document.querySelector('.flatpickr-datetime');

                //date
                if (flatpickrDate) {
                flatpickrDate.flatpickr({
                monthSelectorType: 'static'
                });
                }

                let d = new Date();
                let tanggal = d.getFullYear()+"-"+(d.getMonth()+1)+"-"+d.getDate()+" "+d.getHours()+":"+d.getMinutes();

                var formname = $('#nama_form').val();
                
                if(formname=='edit'){
                    if (flatpickrDateTime) {
                       
                        flatpickrDateTime.flatpickr({
                        disableMobile: true,
                        enableTime: true,
                        time_24hr: true,
                        dateFormat: 'Y-m-d H:i'
                        });
                    }
                }else{
                    // Datetime
                    if (flatpickrDateTime) {
                        flatpickrDateTime.flatpickr({
                        disableMobile: true,
                        enableTime: true,
                        time_24hr: true,
                        defaultDate: tanggal,
                        dateFormat: 'Y-m-d H:i'
                        });
                    }
                }

            })();

           
            //status berita
            $('#status_opini').on('change', function() {
                cek_status_opini();
            });

            function cek_status_opini(){
                let val_status = $('#status_opini').val();
                let edit_publish_berita = $('#edit_publish_berita').val();
                let d = new Date();
                let tanggal = d.getFullYear()+"-"+(d.getMonth()+1)+"-"+d.getDate()+" "+d.getHours()+":"+d.getMinutes();          
                var formname = $('#nama_form').val();
                
            
                if(formname=='edit'){
                    if(val_status=='Schedule' || val_status=='Publish'){
                        document.getElementById('div-tgl-publish').style.display = 'block';
                        document.getElementById('div-add-publish-berita').style.display = 'none';

                        $('#tgl-publish').flatpickr({
                        disableMobile: true,
                        enableTime: true,
                        time_24hr: true,
                        defaultDate: edit_publish_berita,
                        dateFormat: 'Y-m-d H:i'
                        });
                    }
                    
                }else{
                    if(val_status=='Schedule' || val_status=='Publish'){
                        document.getElementById('div-tgl-publish').style.display = 'block';
                        document.getElementById('div-add-publish-berita').style.display = 'none';
                         // Datetime
                            $('#tgl-publish').flatpickr({
                            disableMobile: true,
                            enableTime: true,
                            time_24hr: true,
                            defaultDate: tanggal,
                            dateFormat: 'Y-m-d H:i'
                            });
                    }
                }
                if(val_status=='Draft' || val_status==''){
                    document.getElementById('div-tgl-publish').style.display = 'none';
                    document.getElementById('div-add-publish-berita').style.display = 'none';
                    $('#tgl-publish').val('2100-01-01 00:00');
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

            //CLOSE TAB
            $(document).on('click', '#backNews', function () {
                // window.location = window.close();
            });
            
            
            function tampil_gambar_opini_edit()
            {
       
                $('#myform').html('');
                const note = document.querySelector('.dz-message');
                note.style.display = 'none';

                var formname = $('#nama_form').val();
                var url_img = $('#base_url').val();
                var edit_src_img  = $('#edit_image_src').val();
                var full_path_img = url_img +'assets/upload-gambar/'+ edit_src_img;

                if(formname=='edit'){
                    $('#myform').append(`<div class="card border-primary rounded mb-2"><img src="`+full_path_img+`" max-width: 90%; height="120px"/>
                                    </div>`);
                    $('#myform').append('<input type="hidden" name="gambar_opini" class="form-control" value="'+edit_src_img+'" />');
                    $('#myform').append(`<div class="d-m d-flex justify-content-md-end">
                                        <button type="button" class="btn btn xs btn-danger clear-preview-getimage">
                                        cancel</button> </div>`)
              }
            }

           


           
        // -------------DOCUMENT READY FUNCTION-------------------------------------------------------
        $(document).ready(function () {
            $('.select2').select2();

            var formname = $('#nama_form').val();
            if(formname =='edit'){
                var artikel_opini = $('#artikel_opini').val();
                quill.root.innerHTML=artikel_opini;

                tampil_gambar_opini_edit();
            }


            cek_status_opini();
            disabled_form_data_gallery();

        });

        function show_editor_quill()
            {
                var artikel_opini = $('#artikel_opini').val();
                var formname = $('#nama_form').val();
                if(formname =='edit'){
                    quill.root.innerHTML=artikel_opini;
                }
            }


        function disabled_form_data_gallery(){
            // $('#form_data_gallery *').prop('disabled', true);
            $('#image_name').prop('disabled', true);
            $('#image_url').prop('disabled', true);
         };

        //clear disabled serialize form function
        $.fn.serializeIncludeDisabled = function () {
                let disabled = this.find(":input:disabled").removeAttr("disabled");
                let serialized = this.serialize();
                disabled.attr("disabled", "disabled");
                return serialized;
            };
         
         $(document).on('click', '#updateStore', function () {
            var formname = $('#nama_form').val();
                save_edit_opini();
            });

            $(document).on('click', '#createStore', function () {
            var formname = $('#nama_form').val();
                save_opini_baru();
            });

            //SAVE BERITA BARU
            function save_opini_baru(){
                var formdata = $('#form_create_jurnalismewarga').serializeIncludeDisabled();
                 $('#createStore').addClass('disabled');
                $.ajax({  
                url:`{{ route("jurnalismewarga.store") }}`,
                method:'POST',  
                data: formdata,  
                dataType : "JSON",  
                    success:function(data)  
                    {  
                        $('#createStore').removeClass('disabled');
                    if (data.status == "success") {  
                        Swal.fire('Saved!',  data.message + ' !','success');  
                        setTimeout(function(){// wait for 5 secs(2)
                            // location.reload(); // then reload the page.(3)
                               window.location=data.url;
                        }, 1000); 
                        } else{
                        Swal.fire('Opini Tidak Tersimpan', '', 'info');
                        location.reload();
                        }
                    },
                    error: function (request, status, error) {
                        jsonValue = jQuery.parseJSON( request.responseText );
                        $.each(jsonValue.errors, function(i, item) {
                            console.log('hasil',i);
                            if(i=='judul_opini'){
                                var message_err = 'Judul Berita';
                            }else{
                                var message_err = i; 
                            }
                            Swal.fire('Gagal Menyimpan', 'Silakan isi kolom ' + message_err , 'info');
                            return false;
                        })
               
                        console.log(jsonValue.message);
                    }
                });  
            }

          //SAVE EDIT BERITA
          function save_edit_opini(){
                var formdata = $('#form_edit_jurnalismewarga').serializeIncludeDisabled();
                $.ajax({  
                url:`{{ route("jurnalismewarga.editstore") }}`,
                method:'POST',  
                data: formdata,  
                dataType : "JSON",  
                    success:function(data)  
                    {  
                    if (data.status == "success") {  
                        Swal.fire('Saved!',  data.message + ' !','success');  
                            setTimeout(function(){
                                location.reload();
                            }, 5000); 
                        } 
                            else{
                        Swal.fire('Changes are not saved', '', 'info');
                        }
                    },
                    
                    error: function(){
                        Swal.fire('Changes are not saved', 'error!', 'danger');
                     
                    }
                });  
            }

    </script>
@endpush 