@extends('layouts.materialize')
    @push('css')
    <link rel="stylesheet" href="{{ asset('') }}assets/vendor/css/rtl/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('') }}assets/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
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
<style>
       .shows{
        display: block!important;
        margin-left:5px;
        }

        .hides{
        display: none!important;
        margin-left:5px;
        }
        /* Unchecked state */

        .swal2-container {
        z-index: 10000;
        }
        .img-icon-brand{
            display: block;margin-left: auto;margin-right: auto;
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
                height: 120px;
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

        .form-check-input[type=checkbox] {
            border-radius: 6px;
            font-size: x-large;
        }

</style>

</head>

    @endpush

    @section('content')
        <div class="bg-white container-xxl flex-grow-1 container-p-y">
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
                        <h4 class="py-1 mb-3 fw-bold">
                            <span class="text-muted fw-light">Berita /</span>
                            @if ($status_form === "create_form")
                            Input Baru
                           @else
                            Edit
                            <a href="{{ route('berita.create') }}" target="_blank" type="button" class="btn btn-white btn-sm waves-effect"
                            data-bs-toggle="tooltip"  data-bs-placement="top" data-bs-original-title="Form Buat Berita Baru">Buat Baru</a>
                           @endif
                        </h4>

                        <h4 class="py-1 mb-3 fw-bold">
                            @if ($status_form === "create_form")
                            <button type="button" class="btn btn-primary btn-md waves-effect" id="storeBerita">Simpan</button>
                            <a href="{{ route('konten.berita') }}" type="button" class="btn btn-secondary btn-md waves-effect" id="backNews"><span class="mdi mdi-close-thick"></span></a>
                           @else
                           <button type="button" class="btn btn-primary btn-md waves-effect" id="updateStore">Update</button>
                           <a href="{{ route('konten.berita') }}" type="button" class="btn btn-grey btn-sm waves-effect" id="backNews"><span class="mdi mdi-close-thick"></span></a>
                           @endif

                        </h4>
                    </div>
                </div>

                <input type="hidden" class="form-control" id="nama_form"
                value="@if($status_form === "create_form"){{'create'}}@else{{'edit'}}@endif">

                <input type="hidden" class="form-control" id="id_berita" name="id_berita"
                value="@if($status_form === "edit_form"){{$item->id_berita}}@else{{''}}@endif">

                <div class="row justify-content-center">
                    <div class="mb-3 col-12 col-md-8">
                        <div class="">
                          <h5 class="card-header">Form Berita</h5>
                          <div class="">

                            <input type="hidden" name="id_pengguna" id="id_pengguna" class="form-control"
                                value="{{ $pengguna_data->id_pengguna }}">
                                @livewire('berita.seo-berita', ['data' => $item ?? null])

                            <div class="row g-2">
                                <div class="col-12 col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <select id="author-dropdown" class="select2 form-select"
                                        wire:model="id_author" name="id_author">
                                            <option value="">-- Pilih Author --</option>
                                            @foreach ($list_pengguna as $data)
                                            @if ($status_form === "create_form")
                                            <option value="{{$data['pengguna']->id_pengguna}}">
                                                {{$data['pengguna']->nama_pengguna}}
                                            </option>

                                            @elseif ($status_form === "edit_form")
                                                <option value="{{ $data['pengguna']->id_pengguna }}"
                                                    {{ $data['pengguna']->id_pengguna == $item->id_author ? 'selected' : '' }}>
                                                    {{$data['pengguna']->nama_pengguna}}
                                                </option>
                                            @endif
                                            @endforeach
                                        </select>
                                        <label for="author">Author</label>
                                    </div>
                                    @error('id_author')
                                    <span class="text-danger">{{ dd($message) }}</span> @enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="form-floating form-floating-outline">
                                        <select id="id_editor" class="select2 form-select" name="id_editor">
                                            <option value="">-- Pilih Editor --</option>

                                            @foreach ($list_editor as $data)
                                            @if ($status_form === "create_form")
                                            <option value="{{$data->pengguna->id_pengguna}}">
                                                {{$data->pengguna->nama_pengguna}}
                                            </option>

                                            @elseif ($status_form === "edit_form")
                                                <option value="{{ $data->pengguna->id_pengguna }}"
                                                    {{ $data->pengguna->id_pengguna == $item->editor_berita ? 'selected' : '' }}>
                                                    {{$data->pengguna->nama_pengguna}}
                                                </option>
                                            @endif
                                            @endforeach
                                        </select>
                                        <label for="author">Editor</label>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control" id="jabatan_author" name="jabatan_author"
                                    placeholder="Contoh: Rektor UPNVJT / Direktur BCA / Dosen Univ A"
                                    value="@if ($status_form === "edit_form"){{ $item->jabatan_author }}@endif">
                                    <label for="jabatan_author">Jabatan Author (Opsional)</label>
                                </div>
                            </div>

                                <div class="col-12 col-md-6">
                                    <div class="mt-1 mb-3 form-floating form-floating-outline">
                                        <input type="text" class="form-control" id="kota_berita" name="kota_berita"
                                        style="text-transform: uppercase" placeholder=" "
                                        aria-describedby="floatingInputKota" value="@if ($status_form === "edit_form"){{ $item->kota_berita }}@endif">
                                        <label for="Kota Berita">Kota Berita</label>
                                        <div id="floatingInputKota" class="form-text">
                                        </div>
                                        @error('kota_berita') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                            </div>

                            <div class="gap-2 mb-3 form-group d-flex">
                                <div class="div">
                                    <button type="button" class="btn btn-secondary btn-sm waves-effect waves-light"
                                    data-bs-target="#modalMediaLib" id="add_media" data-bs-toggle="tooltip"
                                    data-bs-placement="top" data-bs-original-title="Insert Image ke Artikel">
                                    <i class="tf-icons mdi mdi-image-multiple-outline me-1"></i>Tambah Media
                                </button>
                                </div>
                                <div class="div">
                                <button type="button" id="btn-embed-socmed" class="btn btn-light btn-sm waves-effect waves-light">
                                <i class="tf-icons mdi mdi-link me-1"></i>Social Media</button>
                                </div>

                            </div>


                           {{-- Editor ARTIKEL --}}
                            <div class="mb-3 quill-editor" id="editor" name="editor" spellcheck="false"></div>

                            <textarea name="artikel_berita" id="artikel_berita" style="display:none;" class="mb-3 form-control" cols="30" rows="10">@if($status_form == 'edit_form'){{ $item->artikel_berita}} @endif </textarea>


                            <div class="mb-3 row">
                                <div class="col-12">
                                    <div class="mb-2 form-group">
                                        {{-- <label for="author">Rangkuman Berita</label> --}}
                                        <div class="mb-3 form-group">
                                            <div class="mb-4 form-floating form-floating-outline">
                                                <textarea spellcheck="false" class="form-control text-dark" id="rangkuman_berita" rows="6" name="rangkuman_berita" wire:model="rangkuman_berita" placeholder="..." style="height: auto;color: black;"> @if($status_form == 'edit_form'){{ $item->rangkuman_berita}}
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

                    {{-- SETTING AREA --}}
                    <div class="col-12 col-md-4">
                        <div class="mb-3 d-flex justify-content-between">
                            <h5 class="card-action-title" style="margin-bottom: 0rem;">Setting</h5>
                            @if ($status_form === "edit_form")
                            <a href="{{ $link_news }}" target="_blank" type="button"
                            class="btn btn-sm btn-white waves-effect waves-light" data-bs-toggle="tooltip"
                            data-bs-placement="top" data-bs-original-title="Lihat Berita">Preview <span class="mdi mdi-web"></span></a>
                            @endif
                            </div>
                            <div class="mb-3 border rounded">
                              <div class="div">
                                <div class="d-block">
                                  <div class="p-0 accordion accordion-header-primary" id="accordionStyle1">
                                    <div class="my-0 accordion-item active shadow-0">
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
                                                    <div class="mb-3 form-floating form-floating-outline">
                                                        <select id="status_berita" class="select form-select" name="status_berita">
                                                            <option value="" @if($status_form == 'edit_form') {{ $item->status_berita == 'trash' ? 'selected' : '' }}  @endif > Pilih </option>
                                                            <option value="Draft"
                                                                @if($status_form == 'create_form')
                                                                    selected
                                                                @elseif($status_form == 'edit_form')
                                                                {{ $item->status_berita == 'Draft' ? 'selected' : '' }}
                                                                @endif
                                                                > Draft </option>
                                                            <option value="Schedule"
                                                                @if($status_form == 'edit_form')
                                                                    {{ $item->status_berita == 'Schedule' ? 'selected' : '' }}
                                                                 @endif>Schedule</option>

                                                            <option value="Publish"
                                                            @if($status_form == 'edit_form')
                                                                {{ $item->status_berita == 'Publish' ? 'selected' : '' }}
                                                                @endif>Publish</option>
                                                        </select>
                                                        <label for="status_berita">Status</label>
                                                        @error('status_berita') <span class="text-danger">{{ $message }}</span> @enderror
                                                    </div>


                                                    <!-- Datetime Picker-->
                                                    <div class="mb-3 form-floating form-floating-outline" id="div-tgl-publish">
                                                        <input
                                                            type="text"
                                                            class="form-control flatpickr-datetime"
                                                            placeholder="Tanggal Publish"
                                                            value="@if($status_form == 'edit_form'  )
                                                                @if($item->date_publish_berita !='0000-00-00 00:00:00')
                                                                {{ $item->date_publish_berita}}
                                                                @else 0000-00-00 00:00 @endif
                                                            @endif"
                                                            id="tgl-publish"  name="date_publish_berita"/>
                                                        <label for="tgl-publish">Tanggal Publish</label>
                                                        <div class="form-text" id="text_tgl_publish">Pilih Tanggal dan Waktu Schedule Publih Berita*</div>
                                                    </div>
                                                    <!-- /Datetime Picker-->

                                                    <div class="mb-3 form-floating form-floating-outline hides" id="div_edit_publish_berita">
                                                        <input type="text"
                                                            class="form-control" placeholder="Tanggal Publish"
                                                            value="@if($status_form == 'edit_form'){{  $item->date_publish_berita === "0000-00-00 00:00:00" ? "0000-00-00 00:00" : $item->date_publish_berita }}@endif"
                                                            id="edit_publish_berita"  name="edit_publish_berita"/>
                                                        <label for="edit_publish_berita">Edit Tanggal Publish</label>
                                                    </div>
                                                    <!-- Date Picker-->
                                                    <div class="mb-3 form-floating form-floating-outline hides">
                                                        <div class="form-floating form-floating-outline">
                                                        <input type="text" class="form-control"
                                                        value="@if($status_form == 'edit_form'){{ $item->expired_berita}} @else 0000-00-00 @endif"
                                                        placeholder="Tanggal Expired"
                                                        id="expired_berita" name="expired_berita"/>
                                                        <label for="expired_berita">Tanggal Expired</label>
                                                        </div>
                                                    </div>
                                                    <!-- /Date Picker -->

                                             </div>

                                                <div class="item-list-footer" id="div-add-publish-berita" style="display:none;">
                                                    <div class="gap-2 d-grid d-md-flex justify-content-md-end">
                                                        <button type="button" class="btn btn-info btn-sm waves-effect" id="add-publish-berita">Publish</button>
                                                    </div>
                                                </div>

                                                </div>
                                            </div>
                                          </div>
                                        </div>
                                      </div>

                                    <div class="my-0 accordion-item shadow-0">
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

                                                <div class="mb-4 form-floating form-floating-outline">
                                                    <select wire:model="id_kategori" id="id_kategori" name="id_kategori" class="select2 form-select">
                                                        <option value="0">-- Select Kategori --</option>
                                                        @foreach ($kategori_list as $data)
                                                        <option value="{{$data->id_kategori_berita}}">
                                                            {{$data->nama_kategori_berita}}
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                    <label for="id_kategori">Kategori</label>
                                                </div>

                                                <div class="mb-3 form-floating form-floating-outline"  style="display:none;">
                                                    <input type="text" class="form-control" id="val_id_kategori"
                                                    name="val_id_kategori"
                                                    value="@if($status_form == 'edit_form'){{$item->id_kategori}}@endif"
                                                    placeholder=" " aria-describedby="floatingValKategori">
                                                    <label for="Val Kategori">Val Kategori</label>
                                                    <div id="floatingValKategori" class="form-text">
                                                    </div>
                                                </div>

                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                    </div>

                                    <div class="my-0 accordion-item shadow-0">
                                      <h2 class="accordion-header">
                                        <button type="button" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#accordionStyle1-2" aria-expanded="false">
                                          Tags
                                        </button>
                                      </h2>
                                      <div id="accordionStyle1-2" class="accordion-collapse collapse show" data-bs-parent="#accordionStyle1">
                                        <div class="accordion-body">

                                            <div class="mb-3 form-floating form-floating-outline">
                                                <input
                                                  id="TagifyCustomInlineSuggestion"
                                                  name="tags_berita"
                                                  class="h-auto form-control"
                                                  placeholder="select"
                                                  value="@if($status_form == 'edit_form')
                                                  {{-- @foreach ($tags_berita as $data) --}}
                                                  {{ $tags_berita }}
                                                  {{-- @endforeach --}}
                                                  @endif" />
                                                <label for="TagifyCustomInlineSuggestion">Tags Berita</label>
                                              </div>

                                        </div>
                                      </div>
                                    </div>

                                    <div class="my-0 accordion-item shadow-0">
                                      <h2 class="accordion-header">
                                        <button type="button" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#accordionStyle1-3" aria-expanded="false">
                                          Tipe Berita
                                        </button>
                                      </h2>
                                      <div id="accordionStyle1-3" class="accordion-collapse collapse show" data-bs-parent="#accordionStyle1">
                                        <div class="accordion-body">
                                           <!-- Custom Icon Checkbox -->
                                            <div class="gap-2 d-flex tipe_news_css justify-content-between align-items-center">
                                                <div class="text-center flex-fill">
                                                    <div class="form-check custom-option custom-option-icon">
                                                    <label class="form-check-label" style="padding: 0.8em;text-align: center;" for="tipe_berita_utama">

                                                        <input
                                                        class="form-check-input"
                                                        type="checkbox"
                                                        id="tipe_berita_utama_cx" name="tipe_berita_utama_cx"
                                                        />
                                                        <span class="mt-2 custom-option-body">
                                                            {{-- <i class="mdi mdi-server"></i> --}}
                                                                <span class="custom-option-title" style="font-size:12px;"> Berita Utama </span>
                                                            {{-- <small> Cake sugar plum fruitcake I love sweet roll jelly-o. </small> --}}
                                                            </span>
                                                        <input class="form-control" type="text" name="tipe_berita_utama" style="display:none;"
                                                        value="@if($status_form == 'edit_form'){{$item->tipe_berita_utama}}@else {{ 0 }} @endif"
                                                        id="tipe_berita_utama" value="0">
                                                    </label>
                                                    </div>
                                                </div>

                                                <div class="text-center flex-fill">
                                                    <div class="form-check custom-option custom-option-icon">
                                                    <label class="form-check-label" for="tipe_berita_pilihan" style="padding: 0.8em;text-align: center;">
                                                        <input class="form-check-input" type="checkbox" id="tipe_berita_pilihan_cx" name="tipe_berita_pilihan_cx" />
                                                        <span class="mt-2 custom-option-body">
                                                            <span class="custom-option-title" style="font-size:12px;"> Berita Pilihan </span>
                                                        </span>
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


                          <div class="mb-4 border card card-action shadow-0">
                            <div class="card-header">
                                <h5 class="card-action-title" style="margin-bottom: 0rem;">Featured Image</h5>
                              <div class="card-action-element">
                                <ul class="mb-0 list-inline">
                                  <li class="list-inline-item">
                                    <a href="javascript:void(0);" class="card-collapsible"><i class="tf-icons mdi mdi-chevron-up"></i></a>
                                  </li>
                                </ul>
                              </div>
                            </div>
                            <div class="collapse show" style="">
                              <div class="card-body">
                                <div class="gap-2 mb-3 d-flex">
                                <button type="button" class="mb-2 btn btn-secondary w-80 get-image-berita"><i class="tf-icons mdi mdi-image-outline me-1"></i>Gambar</button>
                                <button type="button" class="mb-2 btn btn-light w-80 get-video-berita"><i class="tf-icons mdi mdi-youtube me-1"></i>Video</button>
                            </div>
                                <div id="my_form">
                                    <div class="image_area" style="display: block;">
                                        <div class="mb-2 rounded card border-primary"><img src="https://www.portaljtv.com/images/broken.webp" max-width: 90%; height="180px"/></div>
                                    </div>
                                </div>

                                <div class="mt-3 mb-3 form-floating form-floating-outline hides">
                                    <input type="text" class="form-control" id="caption_gambar_berita"
                                    name="caption_gambar_berita"
                                    value="@if($status_form == 'edit_form'){{$item->caption_gambar_berita}}@endif"
                                    placeholder=" " aria-describedby="floatingInputGambar">
                                    <label for="Gambar Berita">Caption Gambar Berita</label>
                                    <div id="floatingInputGambar" class="form-text">
                                    @error('caption_gambar_berita') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="mb-3 d-block form-floating form-floating-outline hides">
                                    <select id="tipe_gambar_utama" class="select form-select"
                                    name="tipe_gambar_utama">
                                        <option value=""
                                        @if($status_form == 'edit_form')
                                        {{ $item->tipe_gambar_utama == '' ? 'selected' : '' }}
                                        @endif> Pilih </option>
                                        <option value="image"
                                        @if($status_form == 'edit_form')
                                        {{ $item->tipe_gambar_utama == 'image' ? 'selected' : '' }}
                                        @endif> Image </option>
                                        <option value="video"
                                        @if($status_form == 'edit_form')
                                        {{ $item->tipe_gambar_utama == 'video' ? 'selected' : '' }}
                                        @endif> Video</option>
                                    </select>
                                    <label for="Tipe Gambar Utama">Tipe Gambar Utama</label>

                                </div>

                                <div class="mb-3 d-block form-floating form-floating-outline hides">
                                    <select wire:model="watermark_gambar_berita" id="watermark_gambar_berita" class="select form-select"
                                    name="watermark_gambar_berita">
                                        <option value="1"
                                        @if($status_form == 'edit_form')
                                        {{ $item->watermark_gambar_berita == '1' ? 'selected' : '' }}
                                        @endif
                                        > Watermark </option>
                                        <option value="0"
                                        @if($status_form == 'create_form')
                                        selected
                                            @elseif($status_form == 'edit_form')
                                            {{ $item->watermark_gambar_berita == '0' ? 'selected' : '' }}
                                            @endif
                                        >Tanpa Watermark</option>
                                    </select>
                                    <label for="watermark">Watermark</label>
                                    @error('status_berita') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>

                                {{-- Edit data Gambar Berita Utama --}}
                                <div class="mt-3 mb-3 form-floating form-floating-outline hides">
                                    <input type="text" class="form-control" id="edit_image_src"
                                    name="edit_image_src"
                                    value="@if($status_form == 'edit_form'){{$item->gambar_depan_berita}}@endif"
                                    placeholder=" " aria-describedby="floatingImageSrc">
                                    <label for="Image Src">Image Src</label>
                                    <div id="floatingImageSrc" class="form-text">
                                    </div>
                                </div>
                                    {{-- Edit data Caption Gambar Berita Utama --}}
                                <div class="mt-3 mb-3 form-floating form-floating-outline hides">
                                    <input type="text" class="form-control" id="edit_caption_image_src"
                                    name="edit_caption_image_src"
                                    value="@if($status_form == 'edit_form'){{$item->caption_gambar_berita}}@endif"
                                    placeholder=" " aria-describedby="floatingCaptionImageSrc">
                                    <label for="Caption Image Src">Caption Image Src</label>
                                    <div id="floatingCaptionImageSrc" class="form-text">
                                    </div>
                                </div>

                                {{--   url images  --}}
                                <div class="mt-3 mb-3 form-floating form-floating-outline hides">
                                    <input type="text" class="form-control" id="edit_cekimage_src"
                                    name="edit_cekimage_src"
                                    value="@if($status_form == 'edit_form'){{$img_utama}}@endif"
                                    placeholder=" " aria-describedby="floatingImageCekSrc">
                                    <label for="Cek Image Src">Cek Image Src</label>
                                    <div id="floatingImageCekSrc" class="form-text">
                                    </div>
                                </div>

                              </div>
                            </div>
                          </div>
                        </form>
                    </div>  {{-- !col-md4 --}}
                </div>

               <!-- Modal Trigger -->
             <div class="modal fade" id="modalMediaLib" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-fullscreen" role="document">
                  <div class="modal-content">
                    <div class="p-2 bg-white modal-body">
                        <button
                          type="button"
                          class="p-3 btn-close btn btn-light rounded-circle position-absolute"
                          data-bs-dismiss="modal"
                          aria-label="Close" style="right: 30px;z-index: 5;"></button>
                        <div class="mb-3 nav-align-top" style="height: calc(100vh - 34px);">
                            <ul class="mb-3 nav nav-pills" role="tablist">
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
                            <div class="p-2 tab-content shadow-0">
                              <div class="tab-pane fade active show" id="navs-pills-top-home" role="tabpanel">
                                    {{-- @foreach($images as $image)  --}}
                                    <div class="row gy-4">
                                        <div class="col-12 col-md-9">
                                            <div class="card shadow-0">
                                                {{-- Search Images --}}
                                                <div class="p-0 my-3 mb-3 input-wrapper input-group input-group-lg input-group-merge">
                                                    <span class="input-group-text" id="basic-addon1"><i class="mdi mdi-magnify mdi-20px"></i></span>
                                                    <input type="text" class="form-control search_image_data" placeholder="Search Image...."
                                                    id="search_image_data" aria-label="Search" aria-describedby="basic-addon1">
                                                    <button type="button" class="btn btn-secondary btn-xs waves-effect btn-section-block"  id="reload_data_gallery"><span class="mdi mdi-refresh"></span></button>
                                                </div>

                                                {{-- infinite loadmore --}}
                                                <div id="id_image_gallery" class="row px1" style="max-height: calc(100vh - 240px); overflow-x: hidden;overflow-y: scroll;">
                                                    <!-- Results -->
                                                </div>

                                                {{-- button loadmore --}}
                                                <div class="m-3 text-center">
                                                    <button class="btn btn-primary" id="load-more" data-paginate="2">Load more...</button>
                                                </div>

                                                <!-- Data Loader -->
                                                <div class="text-center auto-load">
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
                                            <div class="border card shadow-0">
                                                <div class="card-header">
                                                    <label for="Image" class="text-primary">Detail Image</label>
                                                </div>
                                                <div class="card-body">
                                                    <form id="image" action="" autocomplete="off">
                                                        <div id="img_detail" class="mb-3">
                                                        </div>

                                                        <div class="mt-3 mb-3 form-floating form-floating-outline">
                                                            <input type="text" class="form-control" id="image_name" name="image_name"
                                                            placeholder=" " aria-describedby="floatingImageName" readonly>
                                                            <label for="Image Name">Image Name</label>
                                                            <div id="floatingImageName" class="form-text">
                                                            </div>
                                                        </div>

                                                        <div class="mb-3 form-group">
                                                            <div class="mb-4 form-floating form-floating-outline">
                                                                <textarea class="form-control" id="image_caption_text" rows="3" placeholder="..."
                                                                name="image_caption_text" wire:model="image_caption_text" style="font-size: small;height: auto;" readonly></textarea>
                                                                <label for="image_caption_text">Image Caption</label>
                                                            </div>
                                                        </div>

                                                        <div class="mt-3 mb-3 form-floating form-floating-outline" hidden>
                                                            <input type="text" class="form-control" id="image_caption" name="image_caption"
                                                            placeholder=" " aria-describedby="floatingImageCaption" >
                                                            <label for="Image Caption">Image Caption</label>
                                                            <div id="floatingImageCaption" class="form-text">
                                                            </div>
                                                        </div>

                                                        <div class="mt-3 mb-3 form-floating form-floating-outline" hidden>
                                                            <input type="text" class="form-control" id="image_description" name="image_description"
                                                            placeholder=" " aria-describedby="floatingImageDescription">
                                                            <label for="Image Description">Image Description</label>
                                                            <div id="floatingImageDescription" class="form-text">
                                                            </div>
                                                        </div>

                                                        <div class="mb-3 form-group" style="display:none;">
                                                            <div class="mb-4 form-floating form-floating-outline">
                                                                <textarea class="form-control" id="image_description_text" rows="4" placeholder="..."
                                                                name="image_description_text" wire:model="image_description_text" style="font-size: small;height: auto;"></textarea>
                                                                <label for="image_description_text">Image Description</label>
                                                            </div>
                                                        </div>

                                                        <div class="mt-3 mb-3 form-floating form-floating-outline hides">
                                                            <input type="text" class="form-control" id="image_url" name="image_url"
                                                            placeholder=" " aria-describedby="floatingUrlEmbed" style="font-size: small;" readonly>
                                                            <label for="Image Url" >Image Url</label>
                                                            <div id="floatingUrlEmbed" class="form-text">
                                                            </div>
                                                        </div>
                                                        <div class="gap-2 d-grid d-md-flex justify-content-md-end" id="pilih_gambar_content">
                                                            <button type="button" class="btn btn-info btn-lg w-100 waves-effect" id="add-image-berita">Insert Into Post</button>
                                                           </div>

                                                        <div class="gap-2 d-grid d-md-flex justify-content-md-end" id="pilih_gambar_utama">
                                                            <button type="button" class="btn btn-info btn-lg w-100 waves-effect" id="add-getimage-berita">Pilih Gambar Utama</button>
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
                                      <div class="mb-3 col-12 col-md-6">
                                        <div class="card">
                                          <div class="mb-4 card-header">
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
                                      <div class="mb-4 col-12 col-md-6">
                                        <div class="border card shadow-0">
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
                                                          <img id="imgdemo" class="rounded img" alt="">
                                                      </div>
                                                    </div>

                                                    <div class="col-12 col-md-6">
                                                      <div class="mb-3 form-floating form-floating-outline">
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
                                                    <div class="mb-3 form-floating form-floating-outline">
                                                        <select wire:model="id_kategori_berita" id="id_kategori_berita" name="id_kategori_berita" class="select2modal form-select">
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
                                                        <label for="id_kategori_berita">Kategori</label>
                                                    </div>
                                                    </div>
                                                    <div class="col-12 col-md-6">
                                                      <div class="mb-3 form-floating form-floating-outline">
                                                        <select id="ukuran_gambar" class="select form-select"
                                                        name="ukuran_gambar">
                                                            <option value="1"> Default </option>
                                                            <option value="2"> Special</option>
                                                        </select>
                                                        <label for="Ukuran Gambar">Ukuran Gambar</label>
                                                    </div>
                                                    </div>

                                                    <div class="col-12 col-md-6">
                                                      <div class="mb-3 form-floating form-floating-outline">
                                                        <select wire:model="watermark_gambar" id="watermark_gambar" class="select form-select"
                                                        name="watermark_gambar">
                                                            <option value="1"> Watermark </option>
                                                            <option value="0" selected>Tanpa Watermark</option>
                                                        </select>
                                                        <label for="watermark">Watermark</label>
                                                    </div>
                                                    </div>
                                                    <div class="col-12">
                                                      <div class="mt-3 mb-3 form-floating form-floating-outline">
                                                        <input type="text" class="form-control" id="image_name_add" name="image_name_add"
                                                        placeholder=" " aria-describedby="floatingImageName">
                                                        <label for="Nama Gambar">Nama Gambar</label>
                                                        <div id="floatingImageName" class="form-text">
                                                        </div>
                                                      </div>
                                                    </div>

                                                    <div class="col-12">
                                                      <div class="mb-3 form-group">
                                                        <div class="mb-4 form-floating form-floating-outline">
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

             {{-- Modal Add Url Embed --}}
             <div class="modal fade" id="modalEmbed" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Embed Social media</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-4 row">

                                <div class="mb-2 col-md mb-md-0">
                                    <div class="form-check custom-option custom-option-icon cheked" id="div_embed_twitter">
                                    <label class="form-check-label custom-option-content" for="radio_embed_twitter">
                                        <span class="custom-option-body">

                                        <img style="display: block;margin-left: auto;margin-right: auto;"
                                            src="{{ asset('') }}assets/img/icons/brands/twitter.png"
                                            class="mb-2 w-px-40 img-icon-brand"
                                            alt="paypal" />
                                        <span class="custom-option-title"> Twitter </span>
                                        </span>
                                        <input
                                        name="radio_embed_socmed"
                                        class="form-check-input rd_embed_socmed"
                                        type="radio"
                                        value="twitter"
                                        id="radio_embed_twitter"
                                        checked />
                                    </label>
                                    </div>
                                </div>
                                <div class="mb-2 col-md mb-md-0">
                                    <div class="form-check custom-option custom-option-icon" id="div_embed_instagram">
                                    <label class="form-check-label custom-option-content" for="radio_embed_instagram">
                                        <span class="custom-option-body">
                                        <img
                                            src="{{ asset('') }}assets/img/icons/brands/instagram.png"
                                            class="mb-2 w-px-40 img-icon-brand"
                                            alt="wallet" />
                                        <span class="custom-option-title"> Instagram </span>
                                        </span>
                                        <input
                                        name="radio_embed_socmed"
                                        class="form-check-input rd_embed_socmed"
                                        type="radio"
                                        value="instagram"
                                        id="radio_embed_instagram" />
                                    </label>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="form-check custom-option custom-option-icon" id="div_embed_facebook">
                                    <label class="form-check-label custom-option-content" for="radio_embed_facebook">
                                        <span class="custom-option-body">
                                        <img
                                            src="{{ asset('') }}assets/img/icons/brands/facebook.png"
                                            class="mb-2 w-px-40 img-icon-brand"
                                            alt="cc-success" />
                                        <span class="custom-option-title"> Facebook </span>
                                        </span>
                                        <input
                                        name="radio_embed_socmed"
                                        class="form-check-input rd_embed_socmed"
                                        type="radio"
                                        value="facebook"
                                        id="radio_embed_facebook" />
                                    </label>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="form-check custom-option custom-option-icon" id="div_embed_youtube">
                                    <label class="form-check-label custom-option-content" for="radio_embed_youtube">
                                        <span class="custom-option-body">
                                        <img
                                            src="{{ asset('') }}assets/img/icons/brands/youtube.png"
                                            class="mb-2 w-px-40 img-icon-brand"
                                            alt="cc-success" />
                                        <span class="custom-option-title"> Youtube </span>
                                        </span>
                                        <input
                                        name="radio_embed_socmed"
                                        class="form-check-input rd_embed_socmed"
                                        type="radio"
                                        value="youtube"
                                        id="radio_embed_youtube" />
                                    </label>
                                    </div>
                                </div>

                          </div>
                         <!-- Custom Svg Icon Radios -->
                          <span id="ket_embed" class="mt-3">
                            <small> Silakan salin <strong>Nomor ID</strong> Status Postingan anda</small>
                          </span>

                         <div class="mt-3 mb-3 form-floating form-floating-outline">
                            <input type="text" class="form-control" id="url_embed" name="url_embed"
                            placeholder=" " aria-describedby="floatingUrlEmbed" style="font-size: small;">
                            <label for="Embed Url" >Embed Url</label>
                            <div id="floatingUrlEmbed" class="form-text">
                            </div>
                        </div>


                    <div class="mt-3 modal-footer">
                        <button type="button" class="btn btn-primary" id="embed-submit">Embed</button>

                    </div>
                  </div>
                </div>
              </div>
            </div>

              {{-- Modal Gambar Utama Video Youtube --}}
              <div class="modal fade" id="modalVideo" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-md" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Video gambar utama</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                         <div class="mt-3 mb-3 form-floating form-floating-outline">
                            <input type="text" class="form-control" id="url_gambar_video" name="url_gambar_video"
                            placeholder=" " aria-describedby="floatingUrlGambarVideo" style="font-size: small;">
                            <label for="Url Video Youtube" >Url Video Youtube</label>
                            <div id="floatingUrlGambarVideo" class="form-text">
                            </div>
                        </div>

                        <div class="mt-3 mb-4 form-floating form-floating-outline">
                            <input type="text" class="form-control" id="thumbnail" name="thumbnail" placeholder=" " readonly
                            aria-describedby="floatingInputThumbnail" value="">
                            <label for="Link Thumbnail Video Youtube">Link Thumbnail Video Youtube</label>
                            <div id="floatingInputThumbnail" class="form-text">
                            </div>
                            @error('thumbnail') <span class="text-danger">{{ $message }}</span> @enderror
                          </div>


                    <div class="mt-3 modal-footer">
                        <button type="button" class="btn btn-primary" id="submit-gambarvideo">Submit</button>

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
    <script src="https://cdn.jsdelivr.net/blazy/latest/blazy.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill-blot-formatter@1.0.5/dist/quill-blot-formatter.min.js"></script>
    <script src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>


    <script>
        //  TagifyCustomInlineSuggestion
        const TagifyCustomInlineSuggestionEl = document.querySelector('#TagifyCustomInlineSuggestion');
        var whitelist = [];
        //tags berita
        $.ajax({
            url: "{{ route('whitelist.tag') }}",
            method: 'GET',
            data: {},
            success: function(response) {
                var suggestions = response;
                   suggestions.map((item)=> {
                    whitelist.push(item.nama_tag);
                     })

                let TagifyCustomInlineSuggestion = new Tagify(TagifyCustomInlineSuggestionEl, {
                    whitelist: whitelist,
                    maxTags: 10,
                    dropdown: {
                    maxItems: 20,
                    classname: 'tags-inline',
                    enabled: 0,
                    closeOnSelect: false
                    }
                });
            }
        });

         // -------------DOCUMENT READY FUNCTION-------------------------------------------------------
         $(document).ready(function () {
            $('.select2').select2();

            $(".select2modal").select2({
                dropdownParent: $("#modalMediaLib")
            });
            tampil_editor_quill();
            tampil_gambar_berita_edit();
            pilih_kategori();
            cek_tipe_berita();
            disabled_form_data_gallery();
            $('#simpan_data_gallery').addClass('hides');
            // $('#div_edit_publish_berita').addClass('hides');
        });
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
            function cek_tipe_berita(){
                var namaform = $('#nama_form').val();
                check_tipe_news();
            }

            function check_tipe_news(){
                var tbu = $('#tipe_berita_utama').val();
                var tbp = $('#tipe_berita_pilihan').val();

                tbu == 1 ? $('#tipe_berita_utama_cx').prop('checked', true):$('#tipe_berita_utama_cx').prop('checked', false);
                tbp == 1 ? $('#tipe_berita_pilihan_cx').prop('checked', true):$('#tipe_berita_pilihan_cx').prop('checked', false);
            }

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

            //onchange checkbox tipe berita
            $(document).on('change', '#tipe_berita_utama_cx', function () {
                if(this.checked) {
                    $('#tipe_berita_utama').val(1);
                }else{
                    $('#tipe_berita_utama').val(0);
                }
            });

            $('#tipe_berita_pilihan_cx').change(function() {
                if(this.checked) {
                    $('#tipe_berita_pilihan').val(1);
                }else{
                    $('#tipe_berita_pilihan').val(0);
                }
            });

             //edit data img gallery
             $(document).on('click', '#reload_data_gallery', function () {
              var page =1;
              infinteLoadMore(page);
              clearFormMedia();
              $('#search_image_data').val('');
            });

            /*------------------------------------------
            kategori Dropdown Change Event
            --------------------------------------------*/
        $('#id_kategori').on('change', function () {
            var idkategori = this.value;
            var id_kategori = $('#val_id_kategori').val();
        });

        //INFINITE LOAD
        var ENDPOINT = "{{url('/getimages_gallery')}}";
        var page = 1;

        $(document).on('click', '#updateStore', function () {
            save_edit_berita();
        });

        //Pilih Get Image Berita
        $(document).on('click', '.get-image-berita', function () {
            var page = 1;
            infinteLoadMore(page);

            $('#pilih_gambar_content').attr("style", "display: none !important");
            $('#modalMediaLib ').modal('show');
        });

        $(document).on('click', '.get-video-berita', function () {
            $('#modalVideo ').modal('show');
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
        $('#url_gambar_video').on('keyup', function(){
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

        $(document).on('click', '#submit-gambarvideo', function () {
            tampil_gambar_video_edit();
        });

        function tampil_gambar_video_edit()
        {
            $('#my_form').html('');
            var formname = $('#nama_form').val();
            var url_img = $('#base_url').val();
            var url_gambar_video  = $('#url_gambar_video').val();
            var edit_src_img  = $('#thumbnail').val();
            // var tipe_gambar_utama_select  = $('#tipe_gambar_utama_select').val();

            if(edit_src_img!=''){
                $('.get-image-berita').addClass('disabled');
            // if(formname=='edit' && edit_src_img!=''){
                $('#my_form').append(`<div class="mb-2 rounded card border-primary"><img src="`+edit_src_img+`" onerror="this.src='https://www.portaljtv.com/images/broken.webp'" max-width: 80%; height="180px" class="rounded"/>
                                </div>`);
                $('#my_form').append('<input type="hidden" name="addimage_berita" class="form-control" value="'+url_gambar_video+'" />');
                $('#my_form').append(`<div class="d-m d-flex justify-content-md-end">
                                <button type="button" class="btn xs btn-danger clear-preview-getimage">
                                cancel</button> </div>`)
                $('#caption_gambar_berita').val("https://www.youtube.com/embed/"+url_gambar_video+"");
                $('#tipe_gambar_utama').val('video');
                $('#tipe_gambar_utama').trigger('video');
            }else{
                $('#my_form').append(`<div class="mb-2 rounded card border-primary"><img src="https://www.portaljtv.com/images/broken.webp" onerror="this.src='https://www.portaljtv.com/images/broken.webp'" max-width: 80%; height="180px" class="rounded"/>
                                </div>`);
            }
            $('#modalVideo').modal('hide');
        }

        $('#modalVideo').on('hidden.bs.modal', function () {
            $('#url_gambar_video').val('');
            $('#thumbnail').val('');
        });

        //Pilih Gambar Utama
        $(document).on('click', '#add-getimage-berita', function () {

            var url_img = $('#base_url').val();
            var filename =  $('#image_name').val();
            var image_caption_text =  $('#image_caption_text').val();
            var img_url =  $('#image_url').val();
            var src_img =  url_img+ 'assets/upload-gambar/' + filename;
            var image_name = 'assets/upload-gambar/' + filename;

            $('#my_form').html('');

            $('.get-video-berita').addClass('disabled');
            $('#my_form').append(`<div class="mb-2 rounded card border-primary"><img src="`+img_url+`" max-width: 90%; height="180px" class="rounded img"/>
                                </div>`);
            $('#my_form').append('<input type="hidden" name="addimage_berita" class="form-control" value="'+filename+'" />');
            $('#my_form').append(`<div class="d-m d-flex justify-content-md-end">
                                <button type="button" class="btn xs btn-danger clear-preview-getimage">
                                cancel</button> </div>`)
            $('#caption_gambar_berita').val(image_caption_text);
            $('#tipe_gambar_utama').val('image');
            $('#tipe_gambar_utama').trigger('change');
            $('#modalMediaLib').modal('hide');
        });

        //clear preview data
        $(document).on('click', '.clear-preview-getimage', function () {
            $('#my_form').html('');
            $('.get-video-berita').removeClass('disabled');
            $('.get-image-berita').removeClass('disabled');
            $('#tipe_gambar_utama').val('');
            $('#tipe_gambar_utama').trigger('change');

            $('#my_form').append(`<div class="mb-2 rounded card border-primary"><img src="https://www.portaljtv.com/images/broken.webp" max-width: 90%; height="180px" class="rounded img"/>
                                </div>`);
            var val_img_src =  $('#edit_image_src').val();
            var val_caption_img_src =  $('#edit_caption_image_src').val();
            $('#caption_gambar_berita').val(val_caption_img_src);
            $('#my_form').append('<input type="hidden" name="addimage_berita" class="form-control" value="'+val_img_src+'" />');

        });
    </script>
    <script type="text/javascript">
    /* QUILL JS */
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
            ['image'],
            ['video'],
            ['link']
            ];

            let BlockEmbed = Quill.import('blots/block/embed');
            let Delta = Quill.import('delta');
            class VideoBlot extends BlockEmbed {
                static create(url) {
                    let node = super.create();
                    var wrapper = document.createElement('p');
                    node.setAttribute('src', url);
                    node.setAttribute('frameborder', '0');
                    node.setAttribute('height', '330');
                    node.setAttribute('width', '600');
                    node.setAttribute('allowfullscreen', true);
                    node.setAttribute('data-blot-formatter-unclickable-bound', true);
                    // node.insertBefore(wrapper, node);
                    return node;
                }

                static formats(node) {
                    let format = {};
                    if (node.hasAttribute('height')) {
                    format.height = node.getAttribute('height');
                    }
                    if (node.hasAttribute('width')) {
                    format.width = node.getAttribute('width');
                    }

                    return format;
                }

                static value(node) {
                    return node.getAttribute('src');
                }

                format(name, value) {
                    if (name === 'height' || name === 'width') {
                    if (value) {
                        this.domNode.setAttribute(name, value);
                    } else {
                        this.domNode.removeAttribute(name, value);
                    }
                    } else {
                    super.format(name, value);
                    }
                }
            }
            VideoBlot.blotName = 'video';
            VideoBlot.tagName = 'iframe';

            window.twttr = (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0],
            t = window.twttr || {};
            if (d.getElementById(id)) return t;
            js = d.createElement(s);
            js.id = id;
            js.src = "https://platform.twitter.com/widgets.js";
            fjs.parentNode.insertBefore(js, fjs);

            t._e = [];
            t.ready = function(f) {
            t._e.push(f);
            };

            return t;
            }(document, "script", "twitter-wjs"));
            class TweetBlot extends BlockEmbed {
                static create(id) {
                    let node = super.create();
                    node.dataset.id = id;
                    // Allow twitter library to modify our contents
                    twttr.widgets.createTweet(id, node);
                    return node;
                }
                static value(domNode) {
                    return domNode.dataset.id;
                }
            }

            TweetBlot.blotName = 'tweet';
            TweetBlot.tagName = 'div';
            TweetBlot.className = 'tweet';

            Quill.register(TweetBlot);
            Quill.register(VideoBlot);

            Quill.register('modules/blotFormatter', QuillBlotFormatter.default);

            var quill = new Quill('#editor', {
            theme: 'snow',
            modules: {
            imageResize: {
            displaySize: true
            },
            toolbar: {
                    container: toolbarOptions,
                },
            blotFormatter: {
                    // see config options below
                }
            }
            });

            // TEXT CHANGE IN EDITOR TEXT
            quill.on('text-change',function (delta, oldDelta, source){
                const { ops } = quill.getContents();
                change_text_quill();
            });

            function change_text_quill(){
                const quillContent  = quill.root.innerHTML;
                document.getElementById('artikel_berita').value = quillContent;
            }

            function imageHandler() {
            var range = this.quill.getSelection();
            var value = prompt('What is the image URL');
                if(value){
                    this.quill.insertEmbed(range.index, 'image', value, Quill.sources.USER);
                }
            }

            // TESTING EMBED QUILL
            $('#btn-embed-socmed').click(function() {
                $('#modalEmbed ').modal('show');
            });

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
                    Swal.fire('Maaf!',  'silakan tempatkan kursor anda pada text editor!','warning');
                    var data = '0';
                    return data;
                }
            }

            // PILIH MEDIA INSERT TO POST
            $('#add_media').on('click', function () {
                var page =1;
                infinteLoadMore(page);
                $('#pilih_gambar_utama').attr("style", "display: none!important");
                $('#modalMediaLib ').modal('show');
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
                $checklist =  '<span class="text-white position-absolute top-60 start-100 translate-middle badge rounded-pill bg-primary"><i class="mdi mdi-check"></i></span>';
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

                    $tampil_img = '<div class="card"><img src="'+ src +'" height="180px" width="310px" class="rounded img"></div>';

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
            $(document).on('click', '#add-image-berita', function () {
                var url_img = $('#base_url').val();
                var filename =  $('#image_name').val();
                var src_img =  url_img+ 'assets/upload-gambar/' + filename;
                var embed_src = '<iframe class="ql-video" frameborder="0" allowfullscreen="true" src="https://www.youtube.com/embed/rg6CiPI6h2g" height="196" width="391"></iframe>';
                imageHandler2(src_img);
                $('#modalMediaLib').modal('hide');
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

            $.fn.cropper.noConflict();
            $('.modal_upload').on('hidden.bs.modal', function () {
                $(this).find(".image-container").html('');
            });

            //close modal Scrollable
            $('#modalMediaLib').on('hidden.bs.modal', function () {
                clearFormMedia();
                $('#pilih_gambar_content').attr("style", "display: block");
                $('#pilih_gambar_utama').attr("style", "display: block");
                $('#multi_upload').attr("style", "display: block");
            });

            function clearFormMedia(){
                $('#img_detail').html('');
                $('#image_name').val('');
                $('#image_caption_text').val('');
                $('#image_description_text').val('');
                $('#image_url').val('');
                $('#search_image_data').val('');
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
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

            function pilih_kategori(){
                var formname = $('#nama_form').val();
                $id_kategori = $('#val_id_kategori').val();
                if(formname=="edit"){
                    $('#id_kategori').val($id_kategori).trigger('change');
                }
            }

            //edit form
            function tampil_gambar_berita_edit()
            {
                var formname = $('#nama_form').val();
                var url_img = $('#base_url').val();
                var edit_src_img  = $('#edit_image_src').val();
                var edit_cekimage_src  = $('#edit_cekimage_src').val();
                var full_path_img = url_img + 'assets/upload-gambar/' + edit_src_img;
                if(formname=='edit'){
                    $('#my_form').html('');
                    $('#my_form').append(`<div class="mb-2 rounded card border-primary"><img src="`+edit_cekimage_src+`" max-width: 90%; height="180px" class="rounded img"/>
                                    </div>`);
                    $('#my_form').append('<input type="hidden" name="addimage_berita" class="form-control" value="'+edit_src_img+'" />');
                    $('#my_form').append(`<div class="d-m d-flex justify-content-md-end">
                                        <button type="button" class="btn xs btn-danger clear-preview-getimage">
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
                $('#updateStore').addClass('disabled');
                $.ajax({
                url:`{{ route("berita.editstore") }}`,
                method:'POST',
                data: formdata,
                dataType : "JSON",
                    success:function(data)
                    {
                        $('#updateStore').removeClass('disabled');
                    if (data.status == "success") {
                        Swal.fire('Saved!',  data.message + ' !','success');
                            setTimeout(function(){// wait for 5 secs(2)
                                location.reload(); // then reload the page.(3)
                            }, 1000);
                        }
                            else{
                                Swal.fire('Warning!',  data.message + ' !','warning');
                            }
                    },
                    error: function (request, status, error) {
                        jsonValue = jQuery.parseJSON( request.responseText );
                        $.each(jsonValue.errors, function(i, item) {
                            console.log('hasil',i);
                            if(i=='judul_berita'){
                                var message_err = 'Judul Berita';
                            }else if(i=='id_author'){
                                var message_err = 'ID Author';
                            }else if(i=='id_editor'){
                                var message_err = 'ID Editor';
                            }else if(i=='rangkuman_berita'){
                                var message_err = 'Rangkuman Berita';
                            }else{
                                var message_err = i;
                            }
                            Swal.fire('Gagal Menyimpan', 'Silakan isi kolom ' + message_err , 'info');
                            $('#updateStore').removeClass('disabled');
                            return false;
                        })

                        console.log(jsonValue.message);
                    }
                });
            }

            $(document).on('click', '#storeBerita', function () {
                var namaform            = $('#nama_form').val();
                var judul_berita        = $('#judul_berita').val();
                var id_author           = $('#id_author').val();
                var editor_berita       = $('#editor_berita').val();
                var rangkuman_berita    = $('#rangkuman_berita').val();
                var tags                = $('#TagifyCustomInlineSuggestion').val();
                save_berita_baru();
            });

             //SAVE BERITA BARU
             function save_berita_baru(){
                var formdata = $('#form_create_berita').serializeIncludeDisabled();
                 $('#storeBerita').addClass('disabled');
                $.ajax({
                url:`{{ route("berita.store") }}`,
                method:'POST',
                data: formdata,
                dataType : "JSON",
                    success:function(data)
                    {
                        $('#storeBerita').removeClass('disabled');
                    if (data.status == "success") {
                        Swal.fire('Saved!',  data.message + ' !','success');
                        setTimeout(function(){// wait for 5 secs(2)
                            // location.reload(); // then reload the page.(3)
                               window.location=data.url;
                        }, 1000);
                        } else{
                        Swal.fire('Berita Tidak Tersimpan', '', 'info');
                        location.reload();
                        }
                    },
                    error: function (request, status, error) {
                        jsonValue = jQuery.parseJSON( request.responseText );
                        $.each(jsonValue.errors, function(i, item) {
                            console.log('hasil',i);
                            if(i=='judul_berita'){
                                var message_err = 'Judul Berita';
                            }else if(i=='id_author'){
                                var message_err = 'ID Author';
                            }else if(i=='id_editor'){
                                var message_err = 'ID Editor';
                            }else if(i=='rangkuman_berita'){
                                var message_err = 'Rangkuman Berita';
                            }else{
                                var message_err = i;
                            }
                            Swal.fire('Gagal Menyimpan', 'Silakan isi kolom ' + message_err , 'info');
                            $('#storeBerita').removeClass('disabled');
                            return false;

                        })

                        console.log(jsonValue.message);
                    }
                });
            }

            cek_status_berita();
            //status berita
            $(document).on('change', '#status_berita', function(){
                cek_status_berita();
            });

            function cek_status_berita(){
                let val_status = $('#status_berita').val();
                let edit_publish_berita = $('#edit_publish_berita').val();

                let d = new Date();
                let tanggal = d.getFullYear()+"-"+(d.getMonth()+1)+"-"+d.getDate()+" "+d.getHours()+":"+d.getMinutes();

                var formname = $('#nama_form').val();
                if(formname=='edit'){
                    if(val_status=='Schedule' || val_status=='Publish'){
                        document.getElementById('div-tgl-publish').style.display = 'block';
                        document.getElementById('div-add-publish-berita').style.display = 'none';
                        let tgl_publish_edit = '';
                        tgl_publish_edit = (edit_publish_berita == "0000-00-00 00:00") ?  tanggal : edit_publish_berita ;

                        $('#tgl-publish').flatpickr({
                        disableMobile: true,
                        enableTime: true,
                        time_24hr: true,
                        defaultDate: tgl_publish_edit,
                        dateFormat: 'Y-m-d H:i'
                        });
                    } else if(val_status=='Draft' || val_status==''){
                        document.getElementById('div-tgl-publish').style.display = 'none';
                        document.getElementById('div-add-publish-berita').style.display = 'none';
                        $('#tgl-publish').val('0000-00-00 00:00');
                        $('#expired_berita').val('0000-00-00');
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
                    }else if(val_status=='Draft' || val_status==''){
                        document.getElementById('div-tgl-publish').style.display = 'none';
                        document.getElementById('div-add-publish-berita').style.display = 'none';
                        $('#tgl-publish').val('0000-00-00 00:00');
                        $('#expired_berita').val('0000-00-00');
                    }
                }

            }

                const contentDivs = document.querySelectorAll('.custom-option-icon');
                const radioButtons = document.querySelectorAll('.rd_embed_socmed');

                // Add event listener to each radio button
                radioButtons.forEach(button => {
                    button.addEventListener('change', function() {
                        let val = $(this).val();

                        $('.custom-option').removeClass('checked');
                        setTimeout(function(){// wait for 5 secs(2)
                            $('#div_embed_'+val).addClass('checked');
                        }, 100);

                        if(val=='twitter'){
                            $('#ket_embed').html(' <small>Silakan salin <strong>Nomor ID</strong> Status Postingan anda.</small>');
                        }else if(val=='facebook'){
                            $('#ket_embed').html(' <small>Silakan Klik <strong>Sematkan</strong>, kemudian <strong>Salin Kode </strong> Postingan anda.</small>');

                        }else if(val=='instagram'){
                            $('#ket_embed').html(' <small>Silakan salin <strong> URL Instagram</strong> Postingan anda.</small>');
                        }else if(val=='youtube'){
                            $('#ket_embed').html(' <small>Silakan salin <strong>ID Video Youtube</strong> anda.</small>');
                        }

                    });
                });

            $('#embed-submit').click(function() {
                var v_src = $('#url_embed').val();
                var val_socmed = $('input[name="radio_embed_socmed"]:checked').val();
                if(v_src != ''){
                    if(val_socmed=='twitter'){
                        insertEmbedTwitter(v_src);
                    }else if(val_socmed=='facebook'){
                        insertEmbedFB(v_src);
                    }else if(val_socmed=='instagram'){
                        insertEmbedIG(v_src);
                    }else if(val_socmed=='youtube'){
                        insertEmbedYT(v_src);
                    }
                }

            });

            //youtube embed
            function insertEmbedYT(v_src) {
                let range = quill.getSelection(true);
                // quill.insertText(range.index, '\n', Quill.sources.USER);
                let url = 'https://www.youtube.com/embed/'+ v_src +'?showinfo=0';
                // quill.clipboard.dangerouslyPasteHTML(range.index, '<p>');
                var isiembed = quill.insertEmbed(range.index , 'video', url, Quill.sources.USER);

                // quill.formatText(range.index + 1, 1, { height: '315', width: '560' });
                quill.setSelection(range.index + 2, Quill.sources.SILENT);
                // quill.clipboard.dangerouslyPasteHTML(range.index + 2, '</p>');
                $('#url_embed').val('');
                $('#modalEmbed ').modal('hide');
            };

            //twitter embed
            function insertEmbedTwitter(v_src) {
                if(v_src==''){
                    Swal.fire('Maaf!', 'Id Twitter Tidak Boleh Kosong !','warning');
                }else{
                    // console.log(v_src);
                    let range = quill.getSelection(true);
                    //  let id = '1682014533144162304';
                    let id = v_src;
                    quill.insertText(range.index, '\n', Quill.sources.USER);
                    var act_insert = quill.insertEmbed(range.index + 1, 'tweet', id, Quill.sources.USER);
                    quill.setSelection(range.index + 2, Quill.sources.SILENT);

                    if(act_insert){
                        console.log('Tweet embedded successfully');
                        setTimeout(function(){// wait for 5 secs(2)
                                change_text_quill();
                                $('#url_embed').val('');
                                $('#modalEmbed ').modal('hide');
                            }, 1000);
                    }else{
                        console.error('Failed to embed tweet');;
                    }
                }
            };

            //instagram embed
            function insertEmbedIG(v_src) {
                let range = quill.getSelection(true);
                quill.insertText(range.index, '\n', Quill.sources.USER);
                // const html = '<iframe src="https://www.instagram.com/p/Cvpsv0DtVtn/embed" width="550" height="550" frameborder="0" scrolling="no" allowtransparency="true"></iframe>';
                const html = '<p><iframe src="'+ v_src +'embed" width="550" height="550" frameborder="0" scrolling="no" allowtransparency="true"></iframe></p>';
                const delta = quill.clipboard.convert(html);
                quill.clipboard.dangerouslyPasteHTML(range.index, html);
                quill.clipboard.convert();
                $('#url_embed').val('');
                $('#modalEmbed ').modal('hide');
            };

            function insertEmbedFB(v_src) {
                let range = quill.getSelection(true);
                quill.insertText(range.index, '\n', Quill.sources.USER);
                const html = v_src;
                const delta = quill.clipboard.convert(html);
                quill.clipboard.dangerouslyPasteHTML(range.index, html);
                quill.clipboard.convert();
                $('#url_embed').val('');
                $('#modalEmbed ').modal('hide');
            };
            /* --------------------------------------------------------------------------------------- */
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
                    $('#myform').append('<input type="text" class="form-control" name="original_filename" value="'+response.image_name+'" />');
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
                    // alert(a);
                    if(a==1){
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
                    $image.cropper({

                        // aspectRatio: 16 / 9,
                        // autoCropArea: 1,
                            viewMode: 1,
                            // aspectRatio: 2,
                            scalable:true,
                            movable: false,
                            cropBoxResizable: true,
                            data:{ //define cropbox size
                                height: height_img,
                                width:  width_img,
                            },
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

    </script>

@endpush
