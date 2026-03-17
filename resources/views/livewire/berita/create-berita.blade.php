@extends('layouts.materialize')
    @push('css')
    <link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/quill/katex.css" />
    <link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/quill/editor.css" />
    <link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/flatpickr/flatpickr.css" />
    <link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/pickr/pickr-themes.css" />
    <link rel="stylesheet" href="{{ asset('') }}assets/vendor/libs/dropzone/dropzone.css" />
    <style>
        .tipe_news_css{
            padding-right: calc(var(--bs-gutter-x) * 0)!important; 
            padding-left: calc(var(--bs-gutter-x) * 0)!important;
        }
        
    </style>
    @endpush

        @section('content')
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-1 mb-3"><span class="text-muted fw-light">Berita /</span> Create</h4>

                <div class="row justify-content-center">
                    <div class="col-8">
                        <div class="card">
                          <h5 class="card-header">Form Berita</h5>
                          <div class="card-body">
                           
                            <button wire:click.prevent="storePost()" class="btn btn-primary btn-block">Update</button>
                            @livewire('berita.seo-berita')
                         
                           {{--  <div>
                                <livewire:berita.seo-berita />
                            </div> --}}
                            <div class="row mb-3">
                                <div class="col-6">
                                    
                                        {{-- <label for="author">Author</label> --}}
                                        <input type="hidden" class="form-control" id="id_pengguna" name="id_pengguna" value="{{ Auth::user()->id }}">
                                        <div class="form-floating form-floating-outline mb-3 mt-3">
                                            <select id="author-dropdown" class="select2 form-select" 
                                            wire:model="id_author" name="author">
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
                                        <input type="text" class="form-control" id="kota_berita" name="kota_berita" placeholder=" " aria-describedby="floatingInputKota">
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
                           
                            <div class="mb-3" wire:model="artikel_berita" id="full-editor" name="artikel_berita">
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
                      </div>

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
                                                                <select wire:model="status_berita" id="status_berita" class="select form-select" name="status_berita">
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
                                                                id="tgl-publish"  name="tgl-publish" wire:model="tgl-publish"/>
                                                            <label for="tgl-publish">Tanggal Publish</label>
                                                            </div>
                                                            <!-- /Datetime Picker-->

                                                            <!-- Date Picker-->
                                                            <div class="form-floating form-floating-outline mb-3">
                                                                <div class="form-floating form-floating-outline">
                                                                <input type="text" class="form-control flatpickr-date" placeholder="Tanggal Expired" 
                                                                id="tgl-expired" name="tgl-expired"  wire:model="tgl-expired"/>
                                                                <label for="tgl-expired">Tanggal Expired</label>
                                                                </div>
                                                            </div>
                                                            <!-- /Date Picker -->
                                                                                                     
                                                      </div>	
                                                      <div class="item-list-footer">
                                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                          <button type="button" class="btn btn-info btn-sm waves-effect" id="add-custom-link">Publish</button>
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
                                                    <select wire:model="menu" id="menu-dropdown" class="select2 form-select" name="menu">
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
                                                    <select wire:model="submenu" id="submenu-dropdown" name="submenu" class="select2 form-select">
                                                    </select>
                                                    <label for="submenu">Sub Menu</label>
                                                    @error('submenu') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                                                                     
                                                <div class="form-floating form-floating-outline mb-3">
                                                    <select wire:model="kategori" id="kategori-dropdown" name="kategori" class="select2 form-select">
                                                    </select>
                                                    <label for="kategori">Kategori</label>
                                                    @error('kategori') <span class="text-danger">{{ $message }}</span> @enderror
                                                </div>
                                                                                 
                                                <div class="form-floating form-floating-outline mb-3">
                                                    <select wire:model="subkategori" id="subkategori-dropdown" name="subkategori" class="select2 form-select">
                                                    </select>
                                                    <label for="subkategori">Sub Kategori</label>
                                                    @error('subkategori') <span class="text-danger">{{ $message }}</span> @enderror
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
                                                        <label class="form-check-label" style="padding: 0.8em;text-align: center;" for="customCheckboxIcon1">
                                                            <span class="custom-option-body">
                                                            {{-- <i class="mdi mdi-server"></i> --}}
                                                            <span class="custom-option-title" style="font-size:12px;"> Berita Utama </span>
                                                            {{-- <small> Cake sugar plum fruitcake I love sweet roll jelly-o. </small> --}}
                                                            </span>
                                                            <input
                                                            class="form-check-input"
                                                            type="checkbox"
                                                            value=""
                                                            id="customCheckboxIcon1"
                                                            checked />
                                                        </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 mb-md-0 mb-2 tipe_news_css">
                                                        <div class="form-check custom-option custom-option-icon">
                                                        <label class="form-check-label" style="padding: 0.8em;text-align: center;" for="customCheckboxIcon2">
                                                            <span class="custom-option-body">
                                                            {{-- <i class="mdi mdi-shield-outline"></i> --}}
                                                            <span class="custom-option-title" style="font-size:12px;"> Breaking News </span>
                                                            {{-- <small> Cake sugar plum fruitcake I love sweet roll jelly-o. </small> --}}
                                                            </span>
                                                            <input class="form-check-input" type="checkbox" value="" id="customCheckboxIcon2" />
                                                        </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4 tipe_news_css">
                                                        <div class="form-check custom-option custom-option-icon">
                                                        <label class="form-check-label" for="customCheckboxIcon3" style="padding: 0.8em;text-align: center;">
                                                            <span class="custom-option-body">
                                                            {{-- <i class="mdi mdi-lock-outline"></i> --}}
                                                            <span class="custom-option-title" style="font-size:12px;"> Berita Pilihan </span>
                                                            {{-- <small> Cake sugar plum fruitcake I love sweet roll jelly-o. </small> --}}
                                                            </span>
                                                            <input class="form-check-input" type="checkbox" value="" id="customCheckboxIcon3" />
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
                                <form action="/upload" class="dropzone needsclick" id="dropzone-basic">
                                    <div class="dz-message needsclick">
                                      Drop files here or click to upload
                                      {{-- <span class="note needsclick">(This is Selected files is <strong>cover news image</strong> actually uploaded.)</span> --}}
                                    </div>
                                    <div class="fallback">
                                      <input name="file" type="file" />
                                    </div>
                                  </form>

                                  <div class="form-floating form-floating-outline mb-3 mt-3">
                                    <input type="text" class="form-control" id="gambar_berita" name="gambar_berita" 
                                    placeholder=" " aria-describedby="floatingInputGambar">
                                    <label for="Gambar Berita">Caption Gambar Berita</label>
                                    <div id="floatingInputGambar" class="form-text">
                                    </div>
                                </div>
                                  
                                {{-- <form wire:submit.prevent="store">
                                    <div class="form-group mb-3">
                                        <label for="tags">Image Berita</label>
                                        <input type="text" wire:model="tags" wire:keyup='generateSlug' 
                                        class="form-control" id="status" placeholder="images">
                                        @error('tags') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    <button type="submit" class="btn btn-secondary">Upload</button>
                                </form> --}}
                              </div>
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
    <script src="{{ asset('') }}assets/vendor/libs/dropzone/dropzone.js"></script>
    <script src="{{ asset('') }}assets/js/forms-file-upload.js"></script>
   
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
            $("#submenu-dropdown").html('');
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
                        $('#submenu-dropdown').html('<option value="">-- Select Submenu --</option>');
                        $.each(result.submenu, function (key, value) {
                            $("#submenu-dropdown").append('<option value="' + value
                                .id_subnavbar + '">' + value.judul_subnavbar + '</option>');
                        });
                        $('#kategori-dropdown').html('<option value="">----</option>');
                        $('#subkategori-dropdown').html('<option value="">----</option>');
                    }else{
                        $('#submenu-dropdown').html('<option value="">----</option>');
                    }
                    
                }
            });
        });

        /*------------------------------------------
        --------------------------------------------
        submenu Dropdown Change Event
        --------------------------------------------
        --------------------------------------------*/
        $('#submenu-dropdown').on('change', function () {
            var idsubmenu = this.value;
            // $("#kategori-dropdown").html('');
            $.ajax({
                url: "{{url('api/fetch-kategori')}}",
                type: "POST",
                data: {
                    idsubmenu: idsubmenu,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (res) {
                    $('#kategori-dropdown').html('<option value="">-- Pilih Kategori --</option>');
                    $.each(res.kategori, function (key, value) {
                        $("#kategori-dropdown").append('<option value="' + value
                            .id_kategori_berita + '">' + value.nama_kategori_berita + '</option>');
                    });

                    $('#subkategori-dropdown').html('<option value="">----</option>');
                }
            });
        });

        /*------------------------------------------
        --------------------------------------------
        kategori Dropdown Change Event
        --------------------------------------------
        --------------------------------------------*/
        $('#kategori-dropdown').on('change', function () {
            var idkategori = this.value;
            // $("#kategori-dropdown").html('');
            $.ajax({
                url: "{{url('api/fetch-subkategori')}}",
                type: "POST",
                data: {
                    idkategori: idkategori,
                    _token: '{{csrf_token()}}'
                },
                dataType: 'json',
                success: function (res) {
                    $('#subkategori-dropdown').html('<option value="">-- Pilih SubKategori --</option>');
                    $.each(res.subkategori, function (key, value) {
                        $("#subkategori-dropdown").append('<option value="' + value
                            .id_subkategori + '">' + value.nama_subkategori+ '</option>');
                    });
                }
            });
        });

    });

</script>
@endpush 