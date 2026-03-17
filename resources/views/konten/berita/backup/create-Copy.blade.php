@extends('layouts.materialize')
    @push('css')
    <link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/quill/katex.css" />
    <link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/quill/editor.css" />
    <link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/flatpickr/flatpickr.css" />
    <link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/pickr/pickr-themes.css" />
    {{-- <link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/dropzone/dropzone.css" /> --}}

    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.0.1/min/dropzone.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.2.0/min/dropzone.min.js"></script>
    {{-- <link href="https://unpkg.com/dropzone/dist/dropzone.css" rel="stylesheet"/> --}}
    
    <link href="https://unpkg.com/cropperjs/dist/cropper.css" rel="stylesheet"/>
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
</head>
    <style>
        .tipe_news_css{
            padding-right: calc(var(--bs-gutter-x) * 0)!important; 
            padding-left: calc(var(--bs-gutter-x) * 0)!important;
        }
        
    </style>
    @endpush

    @section('content')
        <div class="container-xxl flex-grow-1 container-p-y">
             {{-- check if there is a notif.success flash session --}}
             @if (Session::has('notif.success'))
             <div class="bg-blue-300 mt-2 p-4">
                 {{-- if it's there then print the notification --}}
                 <span class="text-white">{{ Session::get('notif.success') }}</span>
             </div>
             @endif

             @if ($status_form === "create_form")
             <form action="{{ route('berita.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
            @else
                <form action="{{ route('berita.update', $item->id_berita) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
            @endif
   
         
                <div>
                    <div class="d-flex justify-content-between">
                        <h4 class="fw-bold py-1 mb-3">
                            <span class="text-muted fw-light">Berita /</span>
                            @if ($status_form === "create_form")
                            Create
                           @else
                            Edit
                           @endif
                  
                        </h4>
                        <h4 class="fw-bold py-1 mb-3">
                            @if ($status_form === "create_form")
                            <button type="submit" class="btn btn-primary btn-md waves-effect">Save</button>
                           @else
                           <button type="submit" class="btn btn-primary btn-md waves-effect">Update</button>
                           @endif   
                      
                        </h4>
                    </div>
                </div>

                <div class="row justify-content-center">
                    <div class="col-8">
                        <div class="card">
                          <h5 class="card-header">Form Berita</h5>
                          <div class="card-body">
                          
                          {{--   @if ($status_form === "create_form" ) --}}
                              {{--   @livewire('berita.seo-berita')
                           @else --}}
                                @livewire('berita.seo-berita', ['data' => $item ?? null])
                          {{--  @endif   --}}

                           

                            <div class="row mb-3">
                                <div class="col-6">
                                    
                                        {{-- <label for="author">Author</label> --}}
                                        <input type="hidden" class="form-control" id="id_pengguna" name="id_pengguna" value="{{ Auth::user()->id }}">
                                        <div class="form-floating form-floating-outline mb-3 mt-3">
                                            <select id="author-dropdown" class="select2 form-select" 
                                            wire:model="id_author" name="id_author">
                                                <option value="">-- Pilih Author --</option>
                                                @foreach ($list_pengguna as $data)
                                                <option value="{{$data->id_pengguna}}">
                                                    {{$data->nama_pengguna}}
                                                </option>
                                                @endforeach
                                            </select>
                                            <label for="author">Author</label>
                                        </div>
                                  
                                </div>
                                <div class="col-6">
                                    <div class="form-floating form-floating-outline mb-3 mt-3">
                                        <input type="text" class="form-control" id="kota_berita" name="kota_berita" placeholder=" " 
                                        aria-describedby="floatingInputKota" value="@if ($status_form === "edit_form"){{ $item->kota_berita }}@endif">
                                        <label for="Kota Berita">Kota Berita</label>
                                        <div id="floatingInputKota" class="form-text">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <button type="button" class="btn btn-secondary waves-effect waves-light">
                                    <i class="tf-icons mdi mdi-multimedia me-1"></i>Add Media
                                  </button>
                            </div>
                           
                            <div class="mb-3" id="full-editor" name="artikel_berita">
                            </div>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        {{-- <label for="author">Rangkuman Berita</label> --}}
                                        <div class="form-group mb-3">
                                            <div class="form-floating form-floating-outline mb-4">
                                                <textarea class="form-control h-px-100" id="rangkuman_berita" 
                                                name="rangkuman_berita" wire:model="rangkuman_berita" placeholder="..." style="height: 271px;"></textarea>
                                               
                                                <label for="rangkuman_berita">Rangkuman Berita</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                          </div>
                        </div>
                    </div>{{-- !col-md8 --}}

                    <div class="col-md-4">
                       
                            <div class="card mb-3">
                              <div class="card-header">
                                <h5 class="card-action-title" style="margin-bottom: 0rem;">Setting</h5>
                              </div>
                              <div class="card-body">
                                <div class="d-flex align-items-end row">
                                  <div class="accordion accordion-header-primary" id="accordionStyle1">
                                    <div class="accordion-item active">
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
                        
                                                        <form wire:submit.prevent="store">
                                                            <div class="form-floating form-floating-outline mb-3">
                                                                
                                                                <select wire:model="status_berita" id="status_berita" class="select form-select" name="status_berita">
                                                                    <option value=""> Pilih </option>
                                                                    <option value="Draft" selected> Draft </option>
                                                                    <option value="Publish">Publish</option>
                                                                </select>
                                                                <label for="status_berita">Status</label>
                                                                @error('status_berita') <span class="text-danger">{{ $message }}</span> @enderror
                                                            </div>
                    
                                                            <div class="form-floating form-floating-outline mb-3">
                                                                <select wire:model="watermark_gambar_berita" id="watermark_gambar_berita" class="select form-select" 
                                                                name="watermark_gambar_berita">
                                                                    <option value="1"> Watermark </option>
                                                                    <option value="0">Tanpa Watermark</option>
                                                                </select>
                                                                <label for="watermark">Watermark</label>
                                                                @error('status_berita') <span class="text-danger">{{ $message }}</span> @enderror
                                                            </div>
                    
                                                        <!-- Datetime Picker-->
                                                            <div class="form-floating form-floating-outline mb-3">
                                                            <input
                                                                type="text"
                                                                class="form-control flatpickr-datetime"
                                                                placeholder="Tanggal Publish"
                                                                id="tgl-publish"  name="date_publish_berita" wire:model="tgl-publish"/>
                                                            <label for="tgl-publish">Tanggal Publish</label>
                                                            </div>
                                                            <!-- /Datetime Picker-->

                                                            <!-- Date Picker-->
                                                            <div class="form-floating form-floating-outline mb-3">
                                                                <div class="form-floating form-floating-outline">
                                                                <input type="text" class="form-control flatpickr-date" placeholder="Tanggal Expired" 
                                                                id="expired_berita" name="expired_berita" wire:model="expired_berita"/>
                                                                <label for="expired_berita">Tanggal Expired</label>
                                                                </div>
                                                            </div>
                                                            <!-- /Date Picker -->
                                                                                                     
                                                      </div>	
                                                      <div class="item-list-footer">
                                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                          <button type="button" class="btn btn-info btn-sm waves-effect" id="add-publish-berita">Publish</button>
                                                        </div>
                                                     
                                                      </div>
                                                    </div>
                                                </div>
                                          </div>
                                        </div>
                                      </div>

                                    <div class="accordion-item ">
                                      <h2 class="accordion-header">
                                        <button type="button" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#accordionStyle1-1" aria-expanded="true">
                                          Categories
                                        </button>
                                      </h2>
                                    
                                      
                                      <div id="accordionStyle1-1" class="accordion-collapse collapse show" data-bs-parent="#accordionStyle1">
                                        <div class="accordion-body">
                                            <div class="panel panel-default">
                                                <div class="panel-body">						
                                                <div class="item-list-body">
                                                <div class="form-floating form-floating-outline mb-3">
                                                    <select wire:model="menu" id="menu-dropdown" class="select2 form-select" name="id_menu_berita">
                                                        <option value="">-- Select Menu --</option>
                                                        @foreach ($list_menu as $data)
                                                        <option value="{{$data->id_navbar}}">
                                                            {{$data->judul_navbar}}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                    <label for="menu">Menu</label>
                                                    @error('menu') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
            
                                                <div class="form-floating form-floating-outline mb-3">
                                                    <select wire:model="id_sub_menu_berita" id="id_sub_menu_berita" name="id_sub_menu_berita" class="select2 form-select">
                                                    </select>
                                                    <label for="id_sub_menu_berita">Sub Menu</label>
                                                    @error('id_sub_menu_berita') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                                                                     
                                                <div class="form-floating form-floating-outline mb-3">
                                                    <select wire:model="id_kategori" id="id_kategori" name="id_kategori" class="select2 form-select">
                                                    </select>
                                                    <label for="id_kategori">Kategori</label>
                                                    @error('id_kategori') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                                                                 
                                                <div class="form-floating form-floating-outline mb-3">
                                                    <select wire:model="id_subkategori_berita" id="id_subkategori_berita" name="id_subkategori_berita" class="select2 form-select">
                                                    </select>
                                                    <label for="id_subkategori_berita">Sub Kategori</label>
                                                    @error('id_subkategori_berita') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                          
                                        </div>
                                    
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    </div>
                                    
                                    <div class="accordion-item">
                                      <h2 class="accordion-header">
                                        <button type="button" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#accordionStyle1-2" aria-expanded="false">
                                          Tags
                                        </button>
                                      </h2>
                                      <div id="accordionStyle1-2" class="accordion-collapse collapse show" data-bs-parent="#accordionStyle1">
                                        <div class="accordion-body">
                                            <form wire:submit.prevent="store">
                                                <div class="form-floating form-floating-outline mb-3">
                                                  
                                                    <input type="text" wire:model="tags" class="form-control" id="status" 
                                                    placeholder="tags" aria-describedby="floatingInputTags">
                                                    <label for="tags">Tags</label>
                                                    <div id="floatingInputTags" class="form-text">
                                                    </div>
                                                    @error('tags') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>

                                            </form>
                                        </div>
                                      </div>
                                    </div>

                                    <div class="accordion-item">
                                      <h2 class="accordion-header">
                                        <button type="button" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#accordionStyle1-3" aria-expanded="false">
                                          Tipe Berita
                                        </button>
                                      </h2>
                                      <div id="accordionStyle1-3" class="accordion-collapse collapse show" data-bs-parent="#accordionStyle1">
                                        <div class="accordion-body">
                                           <!-- Custom Icon Checkbox -->
                                                <div class="row tipe_news_css">
                                                    <div class="col-md-4 mb-md-0 mb-2 tipe_news_css">
                                                        <div class="form-check custom-option custom-option-icon">
                                                        <label class="form-check-label" style="padding: 0.8em;text-align: center;" for="tipe_berita_utama">
                                                            <span class="custom-option-body">
                                                            {{-- <i class="mdi mdi-server"></i> --}}
                                                            <span class="custom-option-title" style="font-size:12px;"> Berita Utama </span>
                                                            {{-- <small> Cake sugar plum fruitcake I love sweet roll jelly-o. </small> --}}
                                                            </span>
                                                            <input
                                                            class="form-check-input"
                                                            type="checkbox"
                                                            value="1"
                                                            id="tipe_berita_utama" name="tipe_berita_utama"
                                                            checked />
                                                        </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 mb-md-0 mb-2 tipe_news_css">
                                                        <div class="form-check custom-option custom-option-icon">
                                                        <label class="form-check-label" style="padding: 0.8em;text-align: center;" for="tipe_berita_breaking">
                                                            <span class="custom-option-body">
                                                            {{-- <i class="mdi mdi-shield-outline"></i> --}}
                                                            <span class="custom-option-title" style="font-size:12px;"> Breaking News </span>
                                                            {{-- <small> Cake sugar plum fruitcake I love sweet roll jelly-o. </small> --}}
                                                            </span>
                                                            <input class="form-check-input" type="checkbox" value="1" id="tipe_berita_breaking" name="tipe_berita_breaking" />
                                                        </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 tipe_news_css">
                                                        <div class="form-check custom-option custom-option-icon">
                                                        <label class="form-check-label" for="tipe_berita_pilihan" style="padding: 0.8em;text-align: center;">
                                                            <span class="custom-option-body">
                                                            {{-- <i class="mdi mdi-lock-outline"></i> --}}
                                                            <span class="custom-option-title" style="font-size:12px;"> Berita Pilihan </span>
                                                            {{-- <small> Cake sugar plum fruitcake I love sweet roll jelly-o. </small> --}}
                                                            </span>
                                                            <input class="form-check-input" type="checkbox" value="1" id="tipe_berita_pilihan" name="tipe_berita_pilihan" />
                                                        </label>
                                                        </div>
                                                    </div>
                                                    </div>
                                               
                                            <!-- /Custom Icon Checkbox -->
                                        </div>
                                      </div>
                                    </div>

                                  </div>
                                </div>
                              </div>
                            </div>
                        </form>
                          <div class="card card-action mb-4">
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

                                {{-- <div class="dropzone" id="myDropzone"></div> --}}


                                <form action="{{ route('dropzone.store') }}" method="post" enctype="multipart/form-data" id="dropzone" class="dropzone">
                                    @csrf
                                    <div>
                                        <div class="dz-message">
                                            <H5> Klik atau Drop logo disini</h5>
                                        </div>
                                    </div>
                                </form>

                               {{--  <div id="upload_logo" class="dropzone">
                                    <div class="dz-message">
                                    <H5> Klik atau Drop logo disini</h5>
                                    </div>
                                </div>
                                <div id="myform_logo"></div> --}}


                                <div class="image_area">
                                    <form method="post" enctype="multipart/form-data">
                                        <label for="upload_image">
                                            <img src="{{ asset('')}}assets\img\avatars\1.png" id="uploaded_image" class="img-responsive img-circle" />
                                            <div class="overlay">
                                                <div class="text">Click to Change Profile Image</div>
                                            </div>
                                            <input type="file" name="image" class="image" id="upload_image" style="display:none" />
                                        </label>
                                    </form>
                                </div>
                                
                               
                                  <div class="form-floating form-floating-outline mb-3 mt-3">
                                    <input type="text" class="form-control" id="caption_gambar_berita" name="caption_gambar_berita" 
                                    placeholder=" " aria-describedby="floatingInputGambar">
                                    <label for="Gambar Berita">Caption Gambar Berita</label>
                                    <div id="floatingInputGambar" class="form-text">
                                    @error('caption_gambar_berita') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                {{-- <form action="{{ route('uploadFile') }}" method="post" class="dropzone needsclick" 
                                id="dropzone-basic" enctype="multipart/form-data">
                                    <div class="dz-message needsclick" >
                                      Drop files here or click to upload
                                     
                                    </div>
                                    <div class="fallback">
                                      <input id="gambar_depan_berita" name="gambar_depan_berita" type="file" />
                                    </div>
                                    @csrf
                                  </form> --}}

                            
                              </div>
                            </div>
                          </div>

                    </div>  {{-- !col-md4 --}}
                </div>
             {{--    <div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                      <div class="modal-content">
                            <div class="modal-header">
                              <h5 class="modal-title">Crop Image Before Upload</h5>
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                              </button>
                            </div>
                            <div class="modal-body">
                              <div class="img-container">
                                  <div class="row">
                                      <div class="col-md-8">
                                          <img src="" id="sample_image" />
                                      </div>
                                      <div class="col-md-4">
                                          <div class="preview"></div>
                                      </div>
                                  </div>
                              </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" id="crop" class="btn btn-primary">Crop</button>
                              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                      </div>
                    </div>
              </div>	 --}}

              
<div class="modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modalLabel">Laravel 10 Image Croper Upload Example - Laravelia </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="img-container">
              <div class="row">
                  <div class="col-md-8">
                      <img id="image" src="https://avatars0.githubusercontent.com/u/3456749">
                  </div>
                  <div class="col-md-4">
                      <div class="preview"></div>
                  </div>
              </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
          <button type="button" class="btn btn-primary" id="crop">Crop</button>
        </div>
      </div>
    </div>
  </div>
            
        </div>
        @endsection

        @push('js')
       
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="{{ asset('') }}assets/vendor/libs/quill/katex.js"></script>
    <script src="{{ asset('') }}assets/vendor/libs/quill/quill.js"></script>

    <script src="{{ asset('') }}assets/js/forms-editors.js"></script>
    <script src="{{ asset('') }}assets/js/forms-selects.js"></script>

    <script src="{{ asset('') }}assets/vendor/libs/moment/moment.js"></script>
    <script src="{{ asset('') }}assets/vendor/libs/flatpickr/flatpickr.js"></script>
    <script src="{{ asset('') }}assets/vendor/libs/pickr/pickr.js"></script>
    {{-- <script src="{{ asset('') }}assets/vendor/libs/dropzone/dropzone.js"></script>
    <script src="https://unpkg.com/dropzone"></script> --}}
    <script src="https://unpkg.com/cropperjs"></script>
    {{-- <script src="{{ asset('') }}assets/js/forms-file-upload.js"></script> --}}
   
    <script>
        Dropzone.options.dropzone =
         {
                maxFiles: 5, 
                maxFilesize: 4,
                //~ renameFile: function(file) {
                    //~ var dt = new Date();
                    //~ var time = dt.getTime();
                //~ return time+"-"+file.name;    // to rename file name but i didn't use it. i renamed file with php in controller.
                //~ },
                acceptedFiles: ".jpeg,.jpg,.png,.gif",
                addRemoveLinks: true,
                timeout: 50000,
                init:function() {

                    // Get images
                    var myDropzone = this;
                    $.ajax({
                        url: {{ route('dropzone.getImages')}},
                        type: 'GET',
                        dataType: 'json',
                        success: function(data){
                        //console.log(data);
                        $.each(data, function (key, value) {

                            var file = {name: value.name, size: value.size};
                            myDropzone.options.addedfile.call(myDropzone, file);
                            myDropzone.options.thumbnail.call(myDropzone, file, value.path);
                            myDropzone.emit("complete", file);
                        });
                        }
                    });
                },
                removedfile: function(file) 
                {
                    if (this.options.dictRemoveFile) {
                    return Dropzone.confirm("Are You Sure to "+this.options.dictRemoveFile, function() {
                        if(file.previewElement.id != ""){
                            var name = file.previewElement.id;
                        }else{
                            var name = file.name;
                        }
                        //console.log(name);
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                            type: 'POST',
                            url: {{route('dropzone.destroy')}},
                            data: {filename: name},
                            success: function (data){
                                alert(data.success +" File has been successfully removed!");
                            },
                            error: function(e) {
                                console.log(e);
                            }});
                            var fileRef;
                            return (fileRef = file.previewElement) != null ? 
                            fileRef.parentNode.removeChild(file.previewElement) : void 0;
                    });
                    }		
                },
        
                success: function(file, response) 
                {
                    alert(file);
                    file.previewElement.id = response.success;
                    //console.log(file); 
                    // set new images names in dropzone’s preview box.
                    var olddatadzname = file.previewElement.querySelector("[data-dz-name]");   
                    file.previewElement.querySelector("img").alt = response.success;
                    olddatadzname.innerHTML = response.success;
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
            };

        </script>

    <script>

        /* Dropzone.options.imageUpload = {
            previewTemplate: previewTemplate,
            maxFilesize         :       1,
            acceptedFiles: ".jpeg,.jpg,.png,.gif"
        };
 */
    

       

    /* Dropzone.autoDiscover = false;
	var logo_upload= new Dropzone("#upload_logo",{
	// url: "{{ route('image.store') }}",
    url: '/uploadFile',
    previewTemplate: previewTemplate,
	//maxFilesize: 3,
	maxFiles: 1,
	method:"post",
	acceptedFiles: "image/jpeg,image/png,image/gif",
	paramName:"userfile_logo",
	dictInvalidFileType:"Type file ini tidak dizinkan",
	addRemoveLinks:true,
	dictDefaultMessage: "Drop files here to upload",
	init: function () {
		this.on("success", function (file, response) {
				var myArray = Object.values(file);
				var data = JSON.parse(response);
				 console.log(file);
				console.log(response); 
				alert(data.nama_file);
				$('#myform_logo').append('<input type="text" name="addimage_logo" value="'+data.src+'" />');
				$('#myform_logo').append('<input type="text" name="token_logo" value="'+file.token+'" />');
			});
	}
	});
 */
	//Event ketika Memulai mengupload logo
	/* logo_upload.on("sending",function(a,b,c){
		a.token=Math.random();
		c.append("token_logo",a.token); //Menmpersiapkan token untuk masing masing foto
	}); */

	//Event ketika logo dihapus
	/* logo_upload.on("removedfile",function(file){
		var token=file.token;
		$.ajax({
			type:"post",
			data:{token:token},
			url:"{{ route('image.store') }}",
			cache:false,
			dataType: 'json',
			success: function(response){
			//alert(response.status);
				console.log("Foto terhapus");
				$('input[name="addimage_logo"][value="'+response.src+'"]').remove(); 
				$('input[name="token_logo"][value="'+response.token+'"]').remove();    
			},
			error: function(){
				console.log("Error");

			}
		});
	}); */
        </script>

    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        </script>

    <script>
      /*   var $modal = $('#modal');
        var image = document.getElementById('image');
        var cropper;
          
        $("body").on("change", ".image", function(e){
            var files = e.target.files;
            var done = function (url) {
              image.src = url;
              $modal.modal('show');
            };
            var reader;
            var file;
            var url;
            if (files && files.length > 0) {
              file = files[0];
              if (URL) {
                done(URL.createObjectURL(file));
              } else if (FileReader) {
                reader = new FileReader();
                reader.onload = function (e) {
                  done(reader.result);
                };
                reader.readAsDataURL(file);
              }
            }
        });
        $modal.on('shown.bs.modal', function () {
            cropper = new Cropper(image, {
              aspectRatio: 1,
              viewMode: 3,
              preview: '.preview'
            });
        }).on('hidden.bs.modal', function () {
           cropper.destroy();
           cropper = null;
        });
        $("#crop").click(function(){
           
            canvas = cropper.getCroppedCanvas({
                width:400,
                height:400
              });
           
            canvas.toBlob(function(blob) {
                url = URL.createObjectURL(blob);
                var reader = new FileReader();
                 reader.readAsDataURL(blob); 
                 reader.onloadend = function() {
                    var base64data = reader.result; 
                    $.ajax({
                        type: "POST",
                        dataType: "json",
                        url: "{{ route('image.store') }}",
                        data: { 'image': base64data},
                        success: function(data){
                            console.log(data.image_full_path);
                          $modal.modal('hide');
                          alert("Image successfully uploaded");
                           $('#uploaded_image').attr('src', data.image_full_path);
                        }
                      });
                 }
            });
        }) */
        </script>

 


    <script>
    'use strict';

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

    // Datetime
    if (flatpickrDateTime) {
        flatpickrDateTime.flatpickr({
        enableTime: true,
        dateFormat: 'Y-m-d H:i'
        });
    }
    
    })();

     // Basic Dropzone
    // --------------------------------------------------------------------
    $(document).ready(function () {
        /* const previewTemplate = `<div class="dz-preview dz-file-preview">
                                <div class="dz-details">
                                <div class="dz-thumbnail">
                                    <img data-dz-thumbnail>
                                    <span class="dz-nopreview">No preview</span>
                                    <div class="dz-success-mark"></div>
                                    <div class="dz-error-mark"></div>
                                    <div class="dz-error-message"><span data-dz-errormessage></span></div>
                                    <div class="progress">
                                    <div class="progress-bar progress-bar-primary" role="progressbar" aria-valuemin="0" aria-valuemax="100" data-dz-uploadprogress></div>
                                    </div>
                                </div>
                                <div class="dz-filename" data-dz-name></div>
                                <div class="dz-size" data-dz-size></div>
                                </div>
                                </div>`;
      
        const myDropzone = new Dropzone('#dropzone-basic', {
            url: '/uploadFile',
            previewTemplate: previewTemplate,
            parallelUploads: 1,
            maxFilesize: 5,
            addRemoveLinks: true,
            maxFiles: 1
        }); */
        
        $('.select2').select2();
        window.livewire.on('updateModal', () => {
            $('#updateModal').modal('show');
        });
        /*------------------------------------------
        --------------------------------------------
        menu Dropdown Change Event
        --------------------------------------------
        --------------------------------------------*/
        $('#menu-dropdown').on('change', function () {
            var idmenu = this.value;
            // alert(idmenu);
            $("#id_sub_menu_berita").html('');
            $.ajax({
                url: "{{url('api/fetch-submenu')}}",
                type: "POST",
                data: {
                    idmenu: idmenu,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (result) {
                    if(result.submenu){
                        $('#id_sub_menu_berita').html('<option value="">-- Select Submenu --</option>');
                        $.each(result.submenu, function (key, value) {
                            $("#id_sub_menu_berita").append('<option value="' + value
                                .id_subnavbar + '">' + value.judul_subnavbar + '</option>');
                        });
                        $('#id_kategori').html('<option value="">----</option>');
                        $('#id_subkategori_berita').html('<option value="">----</option>');
                    }else{
                        $('#id_sub_menu_berita').html('<option value="">----</option>');
                    }
                    
                }
            });
        });

        /*------------------------------------------
        --------------------------------------------
        submenu Dropdown Change Event
        --------------------------------------------
        --------------------------------------------*/
        $('#id_sub_menu_berita').on('change', function () {
            var idsubmenu = this.value;
            $.ajax({
                url: "{{url('api/fetch-kategori')}}",
                type: "POST",
                data: {
                    idsubmenu: idsubmenu,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (res) {
                    $('#id_kategori').html('<option value="">-- Pilih Kategori --</option>');
                    $.each(res.kategori, function (key, value) {
                        $("#id_kategori").append('<option value="' + value
                            .id_kategori_berita + '">' + value.nama_kategori_berita + '</option>');
                    });

                    $('#id_subkategori_berita').html('<option value="">----</option>');
                }
            });
        });

        /*------------------------------------------
        --------------------------------------------
        kategori Dropdown Change Event
        --------------------------------------------
        --------------------------------------------*/
        $('#id_kategori').on('change', function () {
            var idkategori = this.value;
            $.ajax({
                url: "{{url('api/fetch-subkategori')}}",
                type: "POST",
                data: {
                    idkategori: idkategori,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (res) {
                    $('#id_subkategori_berita').html('<option value="">-- Pilih SubKategori --</option>');
                    $.each(res.subkategori, function (key, value) {
                        $("#id_subkategori_berita").append('<option value="' + value
                            .id_subkategori + '">' + value.nama_subkategori+ '</option>');
                    });
                }
            });
        });

    });

</script>
@endpush 