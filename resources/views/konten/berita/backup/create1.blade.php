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
                 <span class="text-bold">{{ Session::get('notif.success') }}</span>
             </div>
             @endif

             @if ($status_form === "create_form")
             <form action="{{ route('berita.store') }}" method="POST" enctype="multipart/form-data" autocomplete="off"
                id="form_create_berita">
                @csrf
            @else
                <form action="{{ route('berita.update', $item->id_berita) }}" method="POST"  autocomplete="off"
                    id="form_edit_berita" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
            @endif
                    <input type="hidden" id="base_url" name="base_url" value="{{ url('/') }}/" class="form-control">
                <div>
                    <div class="d-flex justify-content-between">
                        <h4 class="fw-bold py-1 mb-3">
                            <span class="text-muted fw-light">Berita /</span>
                            @if ($status_form === "create_form")
                            Input Baru
                           @else
                            Edit
                           @endif
                  
                        </h4>
                        <h4 class="fw-bold py-1 mb-3">
                            @if ($status_form === "create_form")
                            <button type="button" class="btn btn-primary btn-md waves-effect" id="storeBerita">Simpan</button>
                            <a href="{{ route('konten.berita') }}" type="button" class="btn btn-secondary btn-md waves-effect" id="backNews"><span class="mdi mdi-close-thick"></span></a>
                           @else
                           <button type="button" class="btn btn-primary btn-md waves-effect" id="updateStore">Simpan Perubahan</button>
                           <a href="{{ route('konten.berita') }}" type="button" class="btn btn-secondary btn-md waves-effect" id="backNews"><span class="mdi mdi-close-thick"></span></a>
                           @endif   
                      
                        </h4>
                    </div>
                </div>
                
                <input type="hidden" class="form-control" id="nama_form" 
                value="@if($status_form === "create_form"){{'create'}}@else{{'edit'}}@endif">

                <input type="hidden" class="form-control" id="id_berita" name="id_berita"
                value="@if($status_form === "edit_form"){{$item->id_berita}}@else{{''}}@endif">
               
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                          <h5 class="card-header">Form Berita</h5>
                          <div class="card-body">
                          {{-- {{ $id_pengguna->name }} --}}

                            <input type="hidden" name="id_pengguna" id="id_pengguna" class="form-control"
                                value="{{ $pengguna_data->id_pengguna }}">
                                @livewire('berita.seo-berita', ['data' => $item ?? null])
           
                            <div class="row mb-3">
                                <div class="col-6">
                                  
                                        {{-- <label for="author">Author</label> --}}
                                        {{-- <input type="hidden" class="form-control" id="id_pengguna" name="id_pengguna" value="{{ Auth::user()->id }}"> --}}
                                        <div class="form-floating form-floating-outline mb-3 mt-3">
                                            <select id="author-dropdown" class="select2 form-select" 
                                            wire:model="id_author" name="id_author">
                                                <option value="">-- Pilih Author --</option>
                                                @foreach ($list_pengguna as $data)
                                                @if ($status_form === "create_form")
                                                <option value="{{$data->id_pengguna}}">
                                                    {{$data->nama_pengguna}}
                                                </option>
                                                
                                                @elseif ($status_form === "edit_form")
                                                    <option value="{{ $data->id_pengguna }}" 
                                                        {{ $data->id_pengguna == $item->id_author ? 'selected' : '' }}>
                                                        {{$data->nama_pengguna}}
                                                    </option>
                                                @endif
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
                                        @error('kota_berita') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <button type="button" class="btn btn-secondary waves-effect waves-light"
                                data-bs-toggle="modal" data-bs-target="#modalScrollable"
                                id="add_media">
                                    <i class="tf-icons mdi mdi-image-multiple-outline me-1"></i>Tambah Media
                                  </button>
                            </div>
                           
                            <div class="mb-3 quill-editor" id="editor" name="editor" spellcheck="false">
                            </div>

                        
                            <textarea name="artikel_berita" id="artikel_berita" 
                            style="display:none;"
                            class="form-control mb-3" cols="30" 
                            rows="10">@if($status_form == 'edit_form'){{ $item->artikel_berita}} @endif</textarea>

                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        {{-- <label for="author">Rangkuman Berita</label> --}}
                                        <div class="form-group mb-3">
                                            <div class="form-floating form-floating-outline mb-4">
                                                <textarea class="form-control" id="rangkuman_berita" rows="6"
                                                name="rangkuman_berita" wire:model="rangkuman_berita" placeholder="..." style="height: auto;"> @if($status_form == 'edit_form'){{ $item->rangkuman_berita}}
                                                @endif</textarea>
                                              

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
                                  <div class="accordion accordion-header-primary p-0" id="accordionStyle1">
                                    <div class="accordion-item active shadow-0">
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
                                                                
                                                                <select wire:model="status_berita" id="status_berita" class="select form-select" name="status_berita">
                                                                    <option value=""> Pilih </option>
                                                                    <option value="Draft"  
                                                                        @if($status_form == 'create_form')
                                                                            selected
                                                                        @elseif($status_form == 'edit_form')
                                                                        {{ $item->status_berita == 'Draft' ? 'selected' : '' }} 
                                                                        @endif
                                                                       
                                                                       {{--  {{ $status_form == 'create_form' ? 'selected':'' }} --}}> Draft </option>
                                                                    <option value="Publish" 
                                                                    @if($status_form == 'edit_form')
                                                                        {{ $item->status_berita == 'Publish' ? 'selected' : '' }} 
                                                                        @endif>Publish</option>
                                                                </select>
                                                                <label for="status_berita">Status</label>
                                                                @error('status_berita') <span class="text-danger">{{ $message }}</span> @enderror
                                                            </div>
                    
                                                       
                                                            <!-- Datetime Picker-->
                                                            <div class="form-floating form-floating-outline mb-3">
                                                                <input
                                                                    type="text"
                                                                    class="form-control flatpickr-datetime"
                                                                    placeholder="Tanggal Publish"
                                                                    value="@if($status_form == 'edit_form')
                                                                    {{ $item->date_publish_berita}}
                                                                    @endif"
                                                                    id="tgl-publish"  name="date_publish_berita" wire:model="tgl-publish"/>
                                                                <label for="tgl-publish">Tanggal Publish</label>
                                                            </div>
                                                            <!-- /Datetime Picker-->
                                                            
                                                           
                                                            <!-- Date Picker-->
                                                            <div class="form-floating form-floating-outline mb-3">
                                                                <div class="form-floating form-floating-outline">
                                                                <input type="text" class="form-control flatpickr-date" 
                                                                value="@if($status_form == 'edit_form')
                                                                {{ $item->expired_berita}}
                                                                @endif"
                                                                placeholder="Tanggal Expired" 
                                                                id="expired_berita" name="expired_berita" wire:model="expired_berita"/>
                                                                <label for="expired_berita">Tanggal Expired</label>
                                                                </div>
                                                            </div>
                                                            <!-- /Date Picker -->
                                                                                                     
                                                      </div>	
                                                      {{-- <div class="item-list-footer">
                                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                          <button type="button" class="btn btn-info btn-sm waves-effect" id="add-publish-berita">Publish</button>
                                                        </div>
                                                     
                                                      </div> --}}
                                                    </div>
                                                </div>
                                          </div>
                                        </div>
                                      </div>

                                    <div class="accordion-item shadow-0">
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
                                                    <select wire:model="menu" id="menu-dropdown" class="select2 form-select" 
                                                        name="id_menu_berita">
                                                        <option value="">-- Select Menu --</option>
                                                        @foreach ($list_menu as $data)
                                                        <option value="{{$data->id_navbar}}"
                                                            {{-- @if ($status_form === "edit_form")
                                                                {{ $data->id_navbar == $item->id_menu_berita ? 'selected' : '' }}
                                                            @endif --}}
                                                            >
                                                            {{$data->judul_navbar}}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                    <label for="menu">Menu</label>
                                                    @error('menu') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>

                                                <div class="form-floating form-floating-outline mb-3" style="display:none;">
                                                    <input type="text" class="form-control" id="val_id_menu_berita" 
                                                    name="val_id_menu_berita" 
                                                    value="@if($status_form == 'edit_form'){{$item->id_menu_berita}}@endif"
                                                    placeholder=" " aria-describedby="floatingValMenu">
                                                    <label for="Val Menu">Val Menu</label>
                                                    <div id="floatingValMenu" class="form-text">
                                                    </div>
                                                </div>
            
                                                <div class="form-floating form-floating-outline mb-3">
                                                    <select wire:model="id_sub_menu_berita" id="id_sub_menu_berita" name="id_sub_menu_berita" class="select2 form-select">
                                                        <option value="0">-- Select Sub Menu --</option>
                                                    </select>
                                                    <label for="id_sub_menu_berita">Sub Menu</label>
                                                  {{--   @error('id_sub_menu_berita') <span class="text-danger">{{ $message }}</span> @enderror --}}
                                                    
                                                </div>

                                                <div class="form-floating form-floating-outline mb-3" style="display:none;">
                                                    <input type="text" class="form-control" id="val_id_submenu_berita" 
                                                    name="val_id_submenu_berita" 
                                                    value="@if($status_form == 'edit_form'){{$item->id_sub_menu_berita}}@endif"
                                                    placeholder=" " aria-describedby="floatingValSubMenu">
                                                    <label for="Val SubMenu">Val SubMenu</label>
                                                    <div id="floatingValSubMenu" class="form-text">
                                                    </div>
                                                </div>
                                                                                     
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
                                                   {{--  @error('id_kategori') <span class="text-danger">{{ $message }}</span> @enderror --}}
                                                </div>

                                                <div class="form-floating form-floating-outline mb-3"  style="display:none;">
                                                    <input type="text" class="form-control" id="val_id_kategori" 
                                                    name="val_id_kategori" 
                                                    value="@if($status_form == 'edit_form'){{$item->id_kategori}}@endif"
                                                    placeholder=" " aria-describedby="floatingValKategori">
                                                    <label for="Val Kategori">Val Kategori</label>
                                                    <div id="floatingValKategori" class="form-text">
                                                    </div>
                                                </div>
                                                                                 
                                                <div class="form-floating form-floating-outline mb-3">
                                                    <select wire:model="id_subkategori_berita" id="id_subkategori_berita" name="id_subkategori_berita" class="select2 form-select">
                                                        <option value="0">-- Select Sub Kategori --</option>
                                                    </select>
                                                    <label for="id_subkategori_berita">Sub Kategori</label>
                                                    {{-- @error('id_subkategori_berita') <span class="text-danger">{{ $message }}</span> @enderror --}}
                                                </div>

                                                <div class="form-floating form-floating-outline mb-3" style="display:none;">
                                                    <input type="text" class="form-control" id="val_id_subkategori_berita" 
                                                    name="val_id_subkategori_berita" 
                                                    value="@if($status_form == 'edit_form'){{$item->id_subkategori_berita}}@endif"
                                                    placeholder=" " aria-describedby="floatingValSubKategori">
                                                    <label for="Val SubKategori">Val SubKategori</label>
                                                    <div id="floatingValSubKategori" class="form-text">
                                                    </div>
                                                </div>
                                          
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
                                                  
                                                    <input type="text" wire:model="tag_berita" class="form-control" id="tag_berita" name="tag_berita"
                                                    placeholder="tag_berita" 
                                                    value="@if($status_form == 'edit_form'){{$item->tag_berita}}@endif"
                                                    aria-describedby="floatingInputTags">
                                                    <label for="tag_berita">Tags</label>
                                                    <div id="floatingInputTags" class="form-text">
                                                    </div>
                                                    @error('tags') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>

                                           
                                        </div>
                                      </div>
                                    </div>

                                    <div class="accordion-item shadow-0">
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
                                                        id="tipe_berita_utama_cx" name="tipe_berita_utama_cx"
                                                        />
                                                        <input class="form-control" type="text" name="tipe_berita_utama" style="display:none;"
                                                        value="@if($status_form == 'edit_form'){{$item->tipe_berita_utama}}@else {{ 0 }} @endif"
                                                        id="tipe_berita_utama" value="0">
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
                                                        <input class="form-check-input" type="checkbox" id="tipe_berita_breaking_cx" name="tipe_berita_breaking_cx" />
                                                        <input class="form-control" type="text" name="tipe_berita_breaking" style="display:none;"
                                                        value="@if($status_form == 'edit_form'){{$item->tipe_berita_breaking}}@else {{ 0 }} @endif"
                                                        id="tipe_berita_breaking">
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
                                                        <input class="form-check-input" type="checkbox" id="tipe_berita_pilihan_cx" name="tipe_berita_pilihan_cx" />
                                                        <input class="form-control" type="text" name="tipe_berita_pilihan" style="display:none;"
                                                        value="@if($status_form == 'edit_form'){{$item->tipe_berita_pilihan}}@else {{ 0 }} @endif"
                                                        id="tipe_berita_pilihan">
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

                                <button type="button" class="btn btn-secondary w-100 get-image-berita mb-2">Pilih gambar</button>
                                {{-- action="{{ route('dropzone.store') }}" --}}
                                <div class="image_area">
                                    <div enctype="multipart/form-data" id="dropzone" class="dropzone">
                                        {{-- @csrf --}}
                                        <div>
                                            <div class="dz-message">
                                                <H5> Klik atau Drop Gambar Berita disini</h5>
                                            </div>
                                            {{-- <div class="fallback">
                                                <input name="file" type="file">
                                            </div> --}}
                                            <div id="myform"></div>
                                        </div>
                                    </div>
                                </div>
                              
                               
                                <div class="form-floating form-floating-outline mb-3 mt-3">
                                    <input type="text" class="form-control" id="caption_gambar_berita" 
                                    name="caption_gambar_berita" 
                                    value="@if($status_form == 'edit_form'){{$item->caption_gambar_berita}}@endif"
                                    placeholder=" " aria-describedby="floatingInputGambar">
                                    <label for="Gambar Berita">Caption Gambar Berita</label>
                                    <div id="floatingInputGambar" class="form-text">
                                    @error('caption_gambar_berita') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="form-floating form-floating-outline mb-3 mt-3">
                                    <input type="hidden" class="form-control" id="edit_image_src" 
                                    name="edit_image_src" 
                                    value="@if($status_form == 'edit_form'){{$item->gambar_depan_berita}}@endif"
                                    placeholder=" " aria-describedby="floatingImageSrc">
                                    <label for="Image Src">Image Src</label>
                                    <div id="floatingImageSrc" class="form-text">
                                    </div>
                                </div>

                                {{-- watermark area --}}
                                <div class="d-none" id="area_watermark">
                                    <canvas id="demo"></canvas>
                                     <img id="watermark" alt="" crossOrigin="Anonymous" src="{{ asset('') }}assets/admin/upload_logo/jtv1-01.png">
                                     <input type="text" id="hasildemo"> 
                                     <img id="imgdemo" alt="">
                                 </div>
             


                                <div class="form-floating form-floating-outline mb-3">
                                    <select wire:model="watermark_gambar_berita" id="watermark_gambar_berita" class="select form-select" 
                                    name="watermark_gambar_berita">
                                        <option value="1"
                                        @if($status_form == 'create_form')
                                            selected
                                        @elseif($status_form == 'edit_form')
                                        {{ $item->watermark_gambar_berita == '1' ? 'selected' : '' }} 
                                        @endif
                                        > Watermark </option>
                                        <option value="0"
                                            @if($status_form == 'edit_form')
                                            {{ $item->watermark_gambar_berita == '0' ? 'selected' : '' }} 
                                            @endif
                                        >Tanpa Watermark</option>
                                    </select>
                                    <label for="watermark">Watermark</label>
                                    @error('status_berita') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                              
                              </div>
                            </div>
                          </div>
                        </form>
                    </div>  {{-- !col-md4 --}}
                </div>
        
               <!-- Modal Trigger -->
             <div class="modal fade" id="modalScrollable" tabindex="-1" aria-hidden="true">
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
                              <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link waves-effect waves-light " role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-profile" aria-controls="navs-pills-top-profile" aria-selected="true">
                                  Upload
                                </button>
                              </li>
                            
                            </ul>
                            <div class="tab-content">
                              <div class="tab-pane fade active show" id="navs-pills-top-home" role="tabpanel">
                                {{-- @foreach($images as $image)  --}}
                                <div class="">
                                    <div class="row row gy-4">
                                        <div class="col-9">
                                            <div class="card shadow-0">
                                                <div class="input-wrapper my-3 input-group input-group-lg input-group-merge px-5">
                                                    <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-magnify mdi-20px"></i></span>
                                                    <input type="text" class="form-control search_image_data" placeholder="Search Image...." 
                                                    id="search_image_data" aria-label="Search" aria-describedby="basic-addon1">
                                                </div>
                                         {{--    <div id="id_image_gallery" class="row px-3"></div>
                                            <div id="linkid_image_gallery" class="row px-3"></div> --}}
                                            {{-- infinite loadmore --}}
                                            <div id="id_image_gallery" class="row px-3" style="height: 650px; overflow-y: scroll;">
                                                <!-- Results -->
                                            </div>
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
                                                            placeholder=" " aria-describedby="floatingImageName">
                                                            <label for="Image Name">Image Name</label>
                                                            <div id="floatingImageName" class="form-text">
                                                            </div>
                                                        </div>

                                                        <div class="form-floating form-floating-outline mb-3 mt-3">
                                                            <input type="text" class="form-control" id="image_caption" name="image_caption" 
                                                            placeholder=" " aria-describedby="floatingImageCaption">
                                                            <label for="Image Caption">Image Caption</label>
                                                            <div id="floatingImageCaption" class="form-text">
                                                            </div>
                                                        </div>


                                                        <div class="form-floating form-floating-outline mb-3 mt-3">
                                                            <input type="text" class="form-control" id="image_description" name="image_description" 
                                                            placeholder=" " aria-describedby="floatingImageDescription">
                                                            <label for="Image Description">Image Description</label>
                                                            <div id="floatingImageDescription" class="form-text">
                                                            </div>
                                                        </div>

                                                        <div class="form-floating form-floating-outline mb-3 mt-3">
                                                            <input type="text" class="form-control" id="image_url" name="image_url" 
                                                            placeholder=" " aria-describedby="floatingImageUrl">
                                                            <label for="Image Url">Image Url</label>
                                                            <div id="floatingImageUrl" class="form-text">
                                                            </div>
                                                        </div>
                                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                            <button type="button" class="btn btn-info btn-lg w-100 waves-effect" id="add-image-berita">Insert Into Post</button>
                                                          </div>
                                                    </form>

                                                </div>
                                            
                                            </div>
                                        </div>
    
                                    </div>
                                </div>
                             
                                {{-- @endforeach --}}
                              </div>
                              <div class="tab-pane fade " id="navs-pills-top-profile" role="tabpanel">
                             
                                      <div class="form-group">
                                          <label for="document">Documents File Upload</label>
                                          <div class="dropzone" id="dropzoneImage">
                                      </div>
                                      <div id="form_id">

                                      </div>
                              </div>
                            
                            </div>
                          </div>
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
                    <div class="nav-align-top mb-3">
                        <ul class="nav nav-pills mb-3" role="tablist">
                          <li class="nav-item" role="presentation">
                            <button type="button" class="nav-link waves-effect waves-light active media_lib" role="tab" data-bs-toggle="tab" data-bs-target="#navs-pills-top-home" aria-controls="navs-pills-top-home" aria-selected="false" tabindex="-1">
                              Media Library
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
                                                <input type="text" class="form-control search_getimage_data" placeholder="Search Image...." 
                                                id="search_getimage_data" aria-label="Search" aria-describedby="basic-addon1">
                                            </div>
                                        <div id="id_getimage_gallery" class="row px-3" style="height: 650px; overflow-y: scroll;"></div>
                                        <div class="text-center m-3">
                                            <button class="btn btn-primary" id="load-more-img" data-paginate="2">Load more...</button>
                                           
                                        </div>
                                        <!-- Data Loader -->
                                        <div class="auto-load-img text-center">
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

                                    <div class="col-md-3">
                                        <div class="card">
                                            <div class="card-header">
                                                <label for="Image" class="text-primary">Detail Image</label>
                                            </div>
                                            <div class="card-body">
                                                <form id="getimage_form" action="" autocomplete="off">
                                                    <div id="getimg_detail" class="mb-4">
                                                    </div>

                                                    <div class="form-floating form-floating-outline mb-3 mt-3">
                                                        <input type="text" class="form-control" id="getimage_name" name="getimage_name" 
                                                        placeholder=" " aria-describedby="floatingGetImageName">
                                                        <label for="Image Name">Image Name</label>
                                                        <div id="floatingGetImageName" class="form-text">
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

                                                    <div class="form-floating form-floating-outline mb-3 mt-3">
                                                        <input type="text" class="form-control" id="getimage_url" name="getimage_url" 
                                                        placeholder=" " aria-describedby="floatingImageGetUrl">
                                                        <label for="Image Url">Image Url</label>
                                                        <div id="floatingImageGetUrl" class="form-text">
                                                        </div>
                                                    </div>
                                                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                        <button type="button" class="btn btn-info btn-sm waves-effect" id="add-getimage-berita">Pilih Gambar Utama</button>
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
               
              </div>
            </div>
          </div>

        
        </div>

   

        @endsection

        @push('js')
       
    {{-- <script src="//unpkg.com/alpinejs" defer></script> --}}
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
    <script src="https://cdn.jsdelivr.net/blazy/latest/blazy.min.js"></script>

    <script>

         // -------------DOCUMENT READY FUNCTION-------------------------------------------------------
         $(document).ready(function () {
            $('.select2').select2();
            tampil_editor_quill()
            tampil_gambar_berita_edit();

            $(function () {
            $('[data-toggle="tooltip"]').tooltip()
            })

           //clear disabled serialize form function
            $.fn.serializeIncludeDisabled = function () {
                let disabled = this.find(":input:disabled").removeAttr("disabled");
                let serialized = this.serialize();
                disabled.attr("disabled", "disabled");
                return serialized;
            };

            window.livewire.on('updateModal', () => {
            $('#updateModal').modal('show');
            });
            check_tipe_news();
            function check_tipe_news(){
                var tbu = $('#tipe_berita_utama').val();
                var tbb = $('#tipe_berita_breaking').val();
                var tbp = $('#tipe_berita_pilihan').val();
                if(tbu==1)
                { 
                    $('#tipe_berita_utama_cx').prop('checked', true);
                }
                else{
                    $('#tipe_berita_utama_cx').prop('checked', false);}

                if(tbb==1)
                { $('#tipe_berita_breaking_cx').prop('checked', true);}
                else{$('#tipe_berita_utama_cx').prop('checked', false);}

                if(tbp==1)
                { $('#tipe_berita_pilihan_cx').prop('checked', true);}
                else{$('#tipe_berita_pilihan_cx').prop('checked', false);}
                
            }
           
            //onchange checkbox tipe berita
            $(document).on('change', '#tipe_berita_utama_cx', function () {
                if(this.checked) {
                    $('#tipe_berita_utama').val(1);
                }else{
                    alert(0);
                    $('#tipe_berita_utama').val(0);
                }
            });

            $('#tipe_berita_breaking_cx').change(function() {
                if(this.checked) {
                    $('#tipe_berita_breaking').val(1);
                }else{
                    $('#tipe_berita_breaking').val(0);
                }
            });

            $('#tipe_berita_pilihan_cx').change(function() {
                if(this.checked) {
                    $('#tipe_berita_pilihan').val(1);
                }else{
                    $('#tipe_berita_pilihan').val(0);
                }
            });

            /*------------------------------------------
            --------------------------------------------
            menu Dropdown Change Event
            --------------------------------------------
            --------------------------------------------*/
            $('#menu-dropdown').on('change', function () {
                var idmenu = this.value;
                var id_menu = $('#val_id_menu_berita').val();
                var id_submenu = $('#val_id_submenu_berita').val();
                var namaform = $('#nama_form').val();
                // console.log(namaform);
         
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

                         if(namaform=='edit' && idmenu==id_menu)
                         {
                            $.each(result.submenu, function (key, value) {
                             $("#id_sub_menu_berita").append('<option value="' + value
                                 .id_subnavbar + '">' + value.judul_subnavbar + '</option>');
                            });
                          /*   $('#id_kategori').html('<option value="">----</option>');
                            $('#id_subkategori_berita').html('<option value="">----</option>'); */
                            $('#id_sub_menu_berita').val(id_submenu).trigger('change');
                         }
                         else
                         {
                            $.each(result.submenu, function (key, value) {
                             $("#id_sub_menu_berita").append('<option value="' + value
                                 .id_subnavbar + '">' + value.judul_subnavbar + '</option>');
                            });
                          /*   $('#id_kategori').html('<option value="">----</option>');
                            $('#id_subkategori_berita').html('<option value="">----</option>'); */
                         }

                     }else{
                         $('#id_sub_menu_berita').html('<option value="">----</option>');
                     }
                 }

             });

             });

            pilih_menu();
            // $("#menu-dropdown").val('18').trigger('change');
            
            /*------------------------------------------
            --------------------------------------------
            submenu Dropdown Change Event
            --------------------------------------------
            --------------------------------------------*/
            /* $('#id_sub_menu_berita').on('change', function () {
            var idsubmenu = this.value;
            var id_kategori = $('#val_id_kategori').val();
            var id_submenu = $('#val_id_submenu_berita').val();
            var namaform = $('#nama_form').val();

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
                    if(namaform=='edit' && idsubmenu==id_submenu)
                         {
                            $.each(res.kategori, function (key, value) {
                            $("#id_kategori").append('<option value="' + value
                                .id_kategori_berita + '">' + value.nama_kategori_berita + '</option>');
                            });

                            $('#id_subkategori_berita').html('<option value="">----</option>');
                            $('#id_kategori').val(id_kategori).trigger('change');
                        }
                        else{
                            $.each(res.kategori, function (key, value) {
                            $("#id_kategori").append('<option value="' + value
                                .id_kategori_berita + '">' + value.nama_kategori_berita + '</option>');
                            });

                            $('#id_subkategori_berita').html('<option value="">----</option>');
                        }
                   
                    
                }
              
            });
            }); */

            /*------------------------------------------
            --------------------------------------------
            kategori Dropdown Change Event
            --------------------------------------------
            --------------------------------------------*/
            pilih_kategori();

            $('#id_kategori').on('change', function () {
            var idkategori = this.value;
            var id_subkategori_berita = $('#val_id_subkategori_berita').val();
            var id_kategori = $('#val_id_kategori').val();
            var namaform = $('#nama_form').val();

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
                    if(namaform=='edit' && idkategori==id_kategori)
                         {
                            $.each(res.subkategori, function (key, value) {
                                $("#id_subkategori_berita").append('<option value="' + value
                                    .id_subkategori + '">' + value.nama_subkategori+ '</option>');
                            });
                            $('#id_subkategori_berita').val(id_subkategori_berita).trigger('change');
                         }
                    else{
                        $.each(res.subkategori, function (key, value) {
                                $("#id_subkategori_berita").append('<option value="' + value
                                    .id_subkategori + '">' + value.nama_subkategori+ '</option>');
                            });
                        }

                }
            });
            });

        });
            //INFINITE LOAD
            var ENDPOINT = "{{url('/getimages_gallery')}}";
            var page = 1;

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
  

            $(document).on('click', '#backNews', function () {
                window.location = window.close();
            });

            $(document).on('click', '#updateStore', function () {
                save_edit_berita();
            });

            $(document).on('click', '#storeBerita', function () {
                save_berita_baru();
            });


        //Pilih Get Image Berita 
          $(document).on('click', '.get-image-berita', function () {
                var page = 1;
                imageLoadMore(page);
                $('#modalGetImage').modal('show');
            });

            
            $(document).on('click', '#add-getimage-berita', function () {
                $('#myform').html('');
                const note = document.querySelector('.dz-message');
                note.style.display = 'none';

                var url_img = $('#base_url').val();
                var filename =  $('#getimage_name').val();
                var src_img =  url_img+ 'assets/upload-gambar/' + filename;
                var image_name = 'assets/upload-gambar/' + filename;

                // imageHandler2(src_img);
                $('#myform').append(`<div class="card border-primary rounded mb-2"><img src="`+src_img+`" max-width: 90%; height="120px"/>
                                    </div>`);
                $('#myform').append('<input type="hidden" name="addimage_berita" class="form-control" value="'+filename+'" />');
                $('#myform').append(`<div class="d-m d-flex justify-content-md-end">
                                    <button type="button" class="btn btn xs btn-danger clear-preview-getimage">
                                    cancel</button> </div>`)
                $('#modalGetImage').modal('hide');

            });

            $(document).on('click', '.clear-preview-getimage', function () {
                $('#myform').html('');
                var val_img_src =  $('#edit_image_src').val();
                $('#myform').append('<input type="hidden" name="addimage_berita" class="form-control" value="'+val_img_src+'" />');
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
                        // alert(data.name +" File has been successfully removed!"); 
                                    console.log("Foto terhapus");
                          
                            $('#form_id').find('input[name="document[]"][value="' + data.name + '"]').remove()
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
            var toolbarOptions = [
            ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
            ['blockquote', 'code-block'],

            [{ 'header': 1 }, { 'header': 2 }],               // custom button values
            [{ 'list': 'ordered' }, { 'list': 'bullet' }],
            [{ 'script': 'sub' }, { 'script': 'super' }],      // superscript/subscript
            [{ 'indent': '-1' }, { 'indent': '+1' }],          // outdent/indent
            [{ 'direction': 'rtl' }],                         // text direction

            [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

            [{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
            [{ 'font': [] }],
            [{ 'align': [] }],

            ['clean'],                                         // remove formatting button
            ['image']
            ];


            var quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
            imageResize: {
            displaySize: true
            },
            toolbar: {
                    container: toolbarOptions,
                    // handlers: {
                    //     image: imageHandler
                    // }
                }
            }
            });

            quill.on('text-change', () => {
            const { ops } = quill.getContents();

            const justHtml = quill.root.innerHTML;

            $(`textarea[name="artikel_berita"]`).val(justHtml);
            // $(`textarea[name="artikel_berita"]`).val(JSON.stringify(ops));
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
                    Swal.fire('Maaf!',  'silakan tempatkan kursor anda pada text editor!','wagning');  
                    var data = '0';
                    return data;
                } 
            }

            $('#add_media').on('click', function () {
                var page =1;
                infinteLoadMore(page);
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
                    var src = $(this).data("imagedescription");

                    var url_img = $('#base_url').val();
                    var src_img =  url_img+filename;
                    $('#lib_'+id_img).addClass("border-primary border-2 rounded");
                 
                    $('#lib_'+id_img).append($checklist);
                
                    
                    $tampil_img = '<div class="card"><img src="'+ src_img +'" height="160px" width="310px" class="img rounded"></div>';
                    
                    $('#img_detail').html('');
                    $('#img_detail').append($tampil_img);
                    $('#image_name').val(originalname);
                    $('#image_caption').val(imagecaption);
                    $('#image_description').val(imagedescription);
                    $('#image_url').val(src_img);
            });
            
            //klik pilih gambar image utama
            $(document).on('click', '.getimg_gallerys', function (e) {
                $('#lib_'+id_img).find('span').remove();
                var header = document.getElementById("id_getimage_gallery");
                var btnso = header.getElementsByClassName("crd-getimg");
                $checklist =  '<span class="position-absolute top-60 start-100 translate-middle badge rounded-pill bg-primary text-white"><i class="mdi mdi-check"></i></span>';
                
                for (var i = 0; i < btnso.length; i++) {
                    btnso[i].addEventListener("click", function() {
                var current = document.getElementsByClassName("border-primary border-2 rounded");
                current[0].className = current[0].className.replace(" border-primary border-2 rounded", "");
                $('#lib_'+id_img).find('span').remove();
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
                $('#lib_'+id_img).append($checklist);

                $gettampil_img = '<div class="card"><img src="'+ src_img +'" height="160px" width="310px" class="img rounded"></div>';

                $('#getimg_detail').html('');
                $('#getimg_detail').append($gettampil_img);
                $('#getimage_name').val(originalname);
                $('#getimage_caption').val(imagecaption);
                $('#getimage_description').val(imagedescription);
                $('#getimage_url').val(src_img);

            });
            

            $(document).on('click', '#add-image-berita', function () {
                var url_img = $('#base_url').val();
                var filename =  $('#image_name').val();
                var src_img =  url_img+ 'assets/upload-gambar/' + filename;

                imageHandler2(src_img);
                $('#modalScrollable').modal('hide');
            });
            
            $(document).on('click', '.close_crop', function () {
                $('.modal').find('.image-container').html('');
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
                       /*  */

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

            //CARI IMAGE GALLERY
            $('#search_getimage_data').on('keyup', function(){
                var page = 1;
                search_getimage_data(page);
            });
            
            function search_getimage_data(page){
                var keyword = $('#search_getimage_data').val();
                if(keyword!=''){
                    $.ajax({
                     
                        url: '{{ route("getImageSearch") }}' + "/?page=" + page + "&mode=1",
                        datatype: "html",
                        type: "post",
                        data:{
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            keyword:keyword,
                            page : page,
                            mode : 1
                        },
                        beforeSend: function () {
                            $('.auto-load-img').show();
                        }
                    })
                    .done(function (response) {
                       /*  */

                        if (response.html == '') {
                            $('.auto-load-img').html("");
                            $('.auto-load-img').html("We don't have more data to display :(");
                            // $('#load-more').hide();
                            document.getElementById("load-more-img").style.visibility="hidden";
                            return;
                        }else if(response.html != '' && page==1){
                            if (response.list_getimages.total <= 25) {
                                document.getElementById("load-more-img").style.visibility="hidden";
                              
                            }else{
                                document.getElementById("load-more-img").style.visibility="visible";
                                document.getElementById('load-more-img').setAttribute('data-paginate', parseInt(page)+1);
                                $('#load-more').text('Load more...');
                            }
                                $('#id_getimage_gallery').html('');
                                $('.auto-load-img').html("");
                                 $('#load-more-img').text('Load more...');
                                $('#id_getimage_gallery').append(response.html);
                        }else {

                            $('#id_getimage_gallery').html('');
                            if (response.list_getimages.total <= 25) {
                                document.getElementById("load-more-img").style.visibility="hidden";
                                document.getElementById('load-more-img').setAttribute('data-paginate', parseInt(page));
                                $('#load-more').text('Load more...');
                              
                            }else{
                                document.getElementById("load-more-img").style.visibility="visible";
                                document.getElementById('load-more-img').setAttribute('data-paginate', parseInt(page)+1);
                                $('#load-more-img').text('Load more...');
                            }
                            $('.auto-load-img').html("");
                               $('#id_getimage_gallery').append(response.html);
                            }
                      
                    })
                    .fail(function (jqXHR, ajaxOptions, thrownError) {
                        console.log('Server error occured');
                    });

                }else{
                    imageLoadMore(page);
                }

              
                /* if(keyword!=''){
                    $.post('{{ route("getImageSearch") }}',
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
                                            <div class="w-100 badge bg-label-primary rounded-bottom img_name_preview">`+ value.original_filename   +`</div>
                                            </div>
                                        </div>
                                    </div>
                                    `);
                                });
                            }
                        });
                }else{
                    imageLoadMore(page);
                } */
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

            $('#load-more-img').click(function() {
                var paginate = $(this).data('paginate');
                const typeId = document.getElementById('load-more-img').dataset.paginate;    
                imageLoadMore(typeId);
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
            

            function imageLoadMore(page) {
                $.ajax({
                        url: ENDPOINT + "/?page=" + page + "&mode=1",
                        datatype: "html",
                        type: "get",
                        beforeSend: function () {
                            $('.auto-load-img').show();
                        }
                    })
                    .done(function (response) {
                        if (response.html == '') {
                            $('.auto-load-img').html("");
                            $('.auto-load-img').html("We don't have more data to display :(");
                            // $('#load-more').hide();
                            document.getElementById("load-more-img").style.visibility="hidden";
                            $('#load-more-img').text('Load more...');
                            document.getElementById('load-more-img').setAttribute('data-paginate', 2);
                            return;
                        }else if(response.html != '' && page==1){
                                $('#id_getimage_gallery').html('');
                                $('.auto-load-img').html("");
                                document.getElementById("load-more-img").style.visibility="visible";
                                $('#load-more-img').text('Load more...');
                                document.getElementById('load-more-img').setAttribute('data-paginate', parseInt(page)+1);
                                $('#id_getimage_gallery').append(response.html);
                        }else {
                                $('.auto-load').html("");
                                document.getElementById("load-more-img").style.visibility="visible";
                                $('#load-more-img').text('Load more...');
                                document.getElementById('load-more-img').setAttribute('data-paginate', parseInt(page)+1);
                                $('#id_getimage_gallery').append(response.html);
                            }
                
                    })
                    .fail(function (jqXHR, ajaxOptions, thrownError) {
                        console.log('Server error occured');
                    });
            }


            //load data gallery image
            /* function load_image_gallery(){
            $.ajax({
                url: "{{url('/getimages_gallery')}}",
                type: "GET",
                dataType: 'json',
                success: function (result) {
                    // var photosObj = $.parseJSON(result.images);
                    $("#id_image_gallery").html('');
                    $("#linkid_image_gallery").html('');
                  
                    var varian = result.images.data;
                    var linked = result.images.links;
                    console.log( linked);
                    if(varian){
                        $.each(varian, function (key, value) {
                            $("#id_image_gallery").append( 
                            `<div class="col-2 mb-2">
                                <div class="card crd-img gap-2 d-grid border-light" id="lib_img_`+ value.id +`" >
                                    <div id="image_`+ value.id +`">
                                    <img class="b-lazy img_gallerys img rounded-top" 
                                    data-id="img_`+ value.id +`" 
                                    data-originalname="`+ value.original_filename +`"
                                    data-filename="`+ value.filename +`"
                                    data-imagecaption="`+ value.caption +`"
                                    data-imagedescription="`+ value.description +`"
                                    src="{{ url('') }}/`+ value.filename +`" max-width: 90%; height="120px">
                                    <div class="w-100 badge bg-label-light text-muted rounded-bottom img_name_preview">`+ value.original_filename    +`</div>
                                    </div>
                                </div>
                            </div>
                            `);
                        });
                        $('#linkid_image_gallery').html(result.pagination);
                      

                    }else{
                        $("#id_image_gallery").append('No Image Found');
                    }
                        
                }
            });
            }

            //load data gallery getimage
            function load_getimage_gallery(){
                $.ajax({
                    url: "{{url('/getimages_gallery')}}",
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
                                                <div class="w-100 badge bg-label-light text-muted rounded-bottom img_name_preview">`+ value.original_filename     +`</div>
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
            } */


            $.fn.cropper.noConflict();
            $('.modal_upload').on('hidden.bs.modal', function () {
                $(this).find(".image-container").html('');
            });

            //close modal Scrollable
            $('#modalScrollable').on('hidden.bs.modal', function () {
                $('#img_detail').html('');
                $('#image_name').val('');
                $('#image_caption').val('');
                $('#image_description').val('');
                $('#image_url').val('');
                $('#search_image_data').val('');
            });

            //close modal
            $('#modalGetImage').on('hidden.bs.modal', function () {
                $('#getimg_detail').html('');
                $('#getimage_name').val('');
                $('#getimage_caption').val('');
                $('#getimage_description').val('');
                $('#getimage_url').val('');
                $('#search_getimage_data').val('');
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
                url:'/dropzone/store',
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
                        url: '/dropzone/delete',
                        data: { filename: filename,
                                name: name},
                        success: function (data){
                        // alert(data.name +" File has been successfully removed!"); 
                                    console.log("Foto terhapus");
                            // $('input[name="addimage_berita"][value="'+data.name+'"]').remove(); 
                           var val_img_src =  $('#edit_image_src').val();
                            $('input[name="addimage_berita"]').val(val_img_src); 
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
                /* console.log(file); 
                console.log(response);  */
                // set new images names in dropzone’s preview box.
                var olddatadzname = file.previewElement.querySelector("[data-dz-name]");   
                file.previewElement.querySelector("img").alt = response.success;
                olddatadzname.innerHTML = response.success;
              
                file.previewElement.querySelector("img").style.width = "600px";
                file.previewElement.querySelector("img").src = url_img + response.image_path;
                    $('#myform').html('');
                    $('#myform').append('<input type="hidden" class="form-control" name="addimage_berita" value="'+response.image_name+'" />');
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
                    
                    // initialize cropper for uploaded image
                    var $image = $('#img');
                    $image.cropper({
                        // aspectRatio: 16 / 9,
                        // autoCropArea: 1,
                        movable: false,
                        cropBoxResizable: false,
                        data:{ //define cropbox size
                            height: 720,
                            width:  1280,
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
                    var hasil = $img.cropper('getCroppedCanvas');
                    var blob = hasil.toDataURL("image/jpeg");

                    var wtmk = document.getElementById("watermark").src;
                    var wtmk_status = document.getElementById("watermark_gambar_berita").value;
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

                // Datetime
                if (flatpickrDateTime) {
                flatpickrDateTime.flatpickr({
                enableTime: true,
                dateFormat: 'Y-m-d H:i'
                });
                }

            })();

        
            function pilih_menu(){
                $id_menu = $('#val_id_menu_berita').val();
                $('#menu-dropdown').val($id_menu).trigger('change');
            }

            function pilih_kategori(){
                $id_kategori = $('#val_id_kategori').val();
                $('#id_kategori').val($id_kategori).trigger('change');
            }
            
            function tampil_gambar_berita_edit()
            {
                $('#myform').html('');
                const note = document.querySelector('.dz-message');
             

                var formname = $('#nama_form').val();
                var url_img = $('#base_url').val();
                var edit_src_img  = $('#edit_image_src').val();
                var full_path_img = url_img + 'assets/upload-gambar/' + edit_src_img;

                if(formname=='edit'){
                    note.style.display = 'none';
                    $('#myform').append(`<div class="card border-primary rounded mb-2"><img src="`+full_path_img+`" max-width: 90%; height="120px"/>
                                    </div>`);
                    $('#myform').append('<input type="hidden" name="addimage_berita" class="form-control" value="'+edit_src_img+'" />');
                    $('#myform').append(`<div class="d-m d-flex justify-content-md-end">
                                        <button type="button" class="btn btn xs btn-danger clear-preview-getimage">
                                        cancel</button> </div>`)
              }
            }

            function tampil_editor_quill()
            {
                var artikel_berita = $('#artikel_berita').val();
                var formname = $('#nama_form').val();
                
                if(formname=='edit'){
                    quill.root.innerHTML=artikel_berita;
                }
            }

            //SAVE EDIT BERITA
            function save_edit_berita(){
                var formdata = $('#form_edit_berita').serializeIncludeDisabled();

                $.ajax({  
                url:`{{ route("berita.editstore") }}`,
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

             //SAVE BERITA BARU
             function save_berita_baru(){
                var formdata = $('#form_create_berita').serializeIncludeDisabled();

                $.ajax({  
                url:`{{ route("berita.store") }}`,
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
       

    </script>
@endpush 