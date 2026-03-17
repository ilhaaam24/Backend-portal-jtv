  @extends('layouts.materialize')
      @push('css')
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
      .swal2-container {
      z-index: 10000;
      }

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
                  height: 180px;
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
      @endpush

          @section('content')
          <div class="container-xxl flex-grow-1 container-p-y">
            <div class="d-flex justify-content-between">
              <h5 class="card-header align-items-center mb-2">
              
              {{--    <button class="btn btn-sm btn-secondary waves-effect" 
                  data-bs-toggle="tooltip"  data-bs-placement="bottom" data-bs-original-title="Reload Table Berita"
                  id="reload_table"> 
                    <span class="mdi mdi-reload"></span>  </button> --}}
                    <span class="fw-bold mb-4" style="font-size:large;">
                      <span class="text-muted fw-light">Master /</span> Pengguna</span>
              
              </h5>
              <h5 class="card-header align-items-center mb-2">
                <a href="#newModal" class="btn btn-md btn-primary waves-effect pull-right" data-bs-toggle="modal">
                  <i class="fas fa-plus"></i> &nbsp; Add New
                  </a>
            </h5>
            </div>
            
              <div class="row gy-4">
                <div class="col-md-12 col-lg-12">
                <div class="card">
                  <div class="card-body">
                    @livewire('user-table')
                  </div>
                  </div>
                </div>
                </div>
              </div>

      <!-- Add Role Modal -->
      <div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-add-new-role">
          <div class="modal-content p-3 p-md-5">
            <button
              type="button"
              class="btn-close btn-pinned"
              data-bs-dismiss="modal"
              aria-label="Close"></button>
            <div class="modal-body p-md-0">
              <div class="text-center mb-4">
                <h3 class="role-title mb-2 pb-0">Add New Role</h3>
                <p>Set role permissions</p>
              </div>
              <!-- Add role form -->
              <form id="addRoleForm" class="row g-3" onsubmit="return false">
                <div class="col-12 mb-4">
                  <div class="form-floating form-floating-outline">
                    <input
                      type="text"
                      id="modalRoleName"
                      name="modalRoleName"
                      class="form-control"
                      placeholder="Enter a role name"
                      tabindex="-1" />
                    <label for="modalRoleName">Role Name</label>
                  </div>
                </div>

                <div class="col-12 mb-4">
                  <div class="form-floating form-floating-outline">
                    <select name="modalRoleId" id="modalRoleId"  class="select2 form-select">
                      <option value="">--Pilih Role--</option>
                      @foreach ($roles as $data)
                      <option value="{{$data->id}}">
                        {{$data->name}}
                    </option>
                      @endforeach
                    </select>
                    <label for="modalRoleName">Role Name</label>
                  </div>
                </div>
                <div class="col-12">
                  <h5>Role Permissions</h5>
                  <!-- Permission table -->
                  <div class="table-responsive">
                    <table class="table table-flush-spacing">
                      <tbody>
                        <tr>
                          <td class="text-nowrap fw-semibold">
                            Administrator Access
                            <i
                              class="mdi mdi-information-outline"
                              data-bs-toggle="tooltip"
                              data-bs-placement="top"
                              title="Allows a full access to the system"></i>
                          </td>
                          <td>
                            <div class="form-check">
                              <input class="form-check-input" type="checkbox" id="selectAll" />
                              <label class="form-check-label" for="selectAll"> Select All </label>
                            </div>
                          </td>
                        </tr>
                          @foreach ($menu as $data)                      
                        <tr>
                          <td class="text-nowrap fw-semibold">{{ $data->name }}</td>
                          <td>
                            <div class="d-flex">
                              <div class="form-check me-3 me-lg-5">
                                <input class="form-check-input" type="checkbox" id="{{ $data->name }}_Read" />
                                <label class="form-check-label" for="{{ $data->name }}_Read"> Read </label>
                              </div>
                              <div class="form-check me-3 me-lg-5">
                                <input class="form-check-input" type="checkbox" id="{{ $data->name }}_Write" />
                                <label class="form-check-label" for="{{ $data->name }}_Write"> Write </label>
                              </div>
                              <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="{{ $data->name }}_Create" />
                                <label class="form-check-label" for="{{ $data->name }}_Create"> Create </label>
                              </div>
                            </div>
                          </td>
                        </tr>
                        @endforeach
                        
                      </tbody>
                    </table>
                  </div>
                  <!-- Permission table -->
                </div>
                <div class="col-12 text-center">
                  <button type="submit" class="btn btn-primary me-sm-3 me-1">Submit</button>
                  <button
                    type="reset"
                    class="btn btn-outline-secondary"
                    data-bs-dismiss="modal"
                    aria-label="Close">
                    Cancel
                  </button>
                </div>
              </form>
              <!--/ Add role form -->
            </div>
          </div>
        </div>
      </div>
      <!--/ Add Role Modal -->

        <!-- Create new pengguna Modal -->
        <div class="modal fade" id="newModal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title"><span id="title_form">Create</span> Pengguna</h5>
                  <button
                  type="button"
                  class="btn-close btn btn-light p-2 rounded-circle position-absolute"
                  data-bs-dismiss="modal"
                  aria-label="Close" style="right: 30px;z-index: 5;"></button>
              </div>

                  <div class="modal-body" style="background:#f7f7f9;">
          
                      <form id="form_pengguna" action="" autocomplete="off">
                        <div class="row">
                          <div class="col-12 col-md-8 mb-3">
                            <div class="card">
                              <div class="card-header">
                                <label for="Image" class="text-primary">Form</label>
                              </div>
                              <div class="card-body">
                                <div class="form-floating form-floating-outline mb-3">
                                  <input type="text" class="form-control" id="username" name="username" 
                                  placeholder=" " aria-describedby="floatingUsername">
                                  <label for="Username">Username</label>
                                  <div id="floatingUsername" class="form-text">
                                  </div>
                                </div>

                                <div class="form-floating form-floating-outline mb-3">
                                  <input type="text" class="form-control" id="nama_pengguna" name="nama_pengguna" 
                                  placeholder=" " aria-describedby="floatingNamaPengguna">
                                  <label for="Nama Pengguna">Nama Pengguna</label>
                                  <div id="floatingNamaPengguna" class="form-text">
                                  </div>
                                </div>

                                <div class="form-floating form-floating-outline mb-3 mt-3">
                                    <input type="email" class="form-control" id="email" name="email" 
                                    placeholder=" " aria-describedby="floatingEmail">
                                    <label for="Email">Email</label>
                                    <div id="floatingEmail" class="form-text">
                                    </div>
                                </div>

                                <div class="form-floating form-floating-outline mb-3 mt-3">
                                    <input type="password" class="form-control" id="password" 
                                    name="password" placeholder=" " 
                                    aria-describedby="floatingPassword">
                                    <label for="Password">Password</label>
                                    <div id="floatingPassword" class="form-text">
                                    </div>
                                </div>

                                <div class="form-floating form-floating-outline mb-3 mt-3">
                                  <input type="password" class="form-control" id="konfirmasi_password" 
                                  name="konfirmasi_password" placeholder=" " 
                                  aria-describedby="floatingKonfirmasiPassword">
                                  <label for="Konfirmasi Password">Konfirmasi Password</label>
                                  <div id="floatingKonfirmasiPassword" class="form-text">
                                </div>
                              </div>
                        
                              <div class="form-floating form-floating-outline mb-3 mt-3">
                                <select class="select2 form-select" 
                                    id="id_role" name="id_role">
                                    <option value="">-- Role ID --</option>                                                  
                                    @foreach ($roles as $data)
                                    <option value="{{$data->id}}" >
                                        {{$data->name}}
                                    </option>
                                    @endforeach
                                </select>
                                <label for="id_role"> Role ID</label>
                              </div>

                              <div class="form-floating form-floating-outline mb-3 mt-3">
                                <select class="select2 form-select" 
                                    id="id_biro" name="id_biro">
                                    <option value="">-- Pilih Biro --</option>                                                  
                                    @foreach ($list_biro as $data)
                                    <option value="{{$data->id}}" >
                                        {{$data->nama_biro}}
                                    </option>
                                    @endforeach
                                </select>
                                <label for="class"> Pilih Biro</label>
                              </div>

                            </div>
                          </div>
                        </div>
                        
                          <div class="col-12 col-md-4">
                            <div class="card">
                              <div class="card-header">
                                <label for="Image" class="text-primary">Profile Picture</label>
                              </div>
                              <div class="card-body">

                                <div class="image_area">
                                  <div enctype="multipart/form-data" id="dropzone" class="dropzone">
                    
                                      <div>
                                          <div class="dz-message">
                                              <H5> Klik atau Drop Gambar</h5>
                                          </div>
                                      
                                          <div id="form_id" style="max-height:550px"></div>
                                      </div>
                                  </div>
                              </div>

                                {{-- <div class="dropzone" id="dropzone"></div>
                                <div id="form_id"></div> --}}

                                <div class="form-floating form-floating-outline mb-3 mt-3 hides">
                                  <input type="text" class="form-control" id="gambar_pengguna" name="gambar_pengguna" 
                                  placeholder=" " aria-describedby="floatingGambarPengguna">
                                  <label for="Gambar Pengguna">Gambar Pengguna</label>
                                  <div id="floatingGambarPengguna" class="form-text">
                                  </div>
                                </div>

                                <div class="form-floating form-floating-outline mb-3 mt-3 hides">
                                  <input type="text" class="form-control" id="status_pengguna" 
                                  name="status_pengguna" placeholder=" " 
                                  aria-describedby="floatingStatus">
                                  <label for="Status">Status</label>
                                  <div id="floatingStatus" class="form-text">
                                  </div>
                                </div>

                                <div class="form-floating form-floating-outline mb-3 mt-3 hides">
                                    <input type="text" class="form-control" id="id_user" name="id_user" 
                                    placeholder=" " aria-describedby="floatingIDUser">
                                    <label for="ID User">ID User</label>
                                    <div id="floatingIDUser" class="form-text">
                                    </div>
                                </div>
                              </div>

                            </div>
                          </div>
                          
                        </div>
                      </form>
                                  
                              
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cancel</button>
                      <button type="submit" class="btn btn-primary" id="add-pengguna"><span id="aksi_submit">Create</span></button>
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
    $('.select2').select2(
      {
        dropdownParent: $('#addRoleModal'),
        dropdownParent: $('#newModal')
      }
    );

    });

    //clear disabled serialize form function
    $.fn.serializeIncludeDisabled = function () {
        let disabled = this.find(":input:disabled").removeAttr("disabled");
        let serialized = this.serialize();
        disabled.attr("disabled", "disabled");
        return serialized;
    };

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    $('#newModal').on('hidden.bs.modal', function () {
        clearForm();
    });

    function clearForm(){
        $('.dz-preview').remove();
        $("#form_pengguna")[0].reset();
        $("#aksi_submit").text("Create");
        $("#title_form").text("Create");
        $('#password').removeClass('hides');
        $('#konfirmasi_password').removeClass('hides');
        $('#password').addClass('shows');
        $('#konfirmasi_password').addClass('shows');
        $('#form_id').html('');
        $('#id_role').val('');
        $('#id_role').trigger('change');
        $('#id_biro').val('');
        $('#id_biro').trigger('change');
        $('.dz-message').removeClass('hides');
        $('.dz-message').addClass('shows');
    }

    $(document).on('click', '#editPengguna', function () {
  
      var id = $(this).data("id");
      var stat = $(this).data("stat");
      // alert(id);
      if(stat=='aktif'){

      $('#newModal').modal('show');
      $("#aksi_submit").text("Update");
      $("#title_form").text("Update");

            $.ajax({  
            url:`{{ route("getEditPengguna") }}`,
            method:'POST',  
            data: { 
                _token: $('meta[name="csrf-token"]').attr('content'),
                id : id} ,  
            dataType : "JSON",  
                success:function(data)  
                {  
                  var img_user = data.hasil.pengguna.gambar_pengguna;
                  var edit_id_biro = data.hasil.pengguna.id_biro;
                
                  $('#image_area').html('');
                if (data.status == "success") {  
                
                  if(data.hasil.roles.length > 0){
                    // console.log('ada');
                    var edit_id_role = data.hasil.roles[0].id;
                  }else{
                    // console.log('tak ada');
                    var edit_id_role = '';
                  }
                    // console.log(data.hasil.pengguna.gambar_pengguna);
                  
                    $url_img = "{{ config('jp.path_url_be').config('jp.path_img_profile') }}"+ data.hasil.pengguna.gambar_pengguna;
              
                    $('#id_user').val(data.hasil.id);
                    $('#username').val(data.hasil.name);
                    $('#nama_pengguna').val(data.hasil.pengguna.nama_pengguna);
                    $('#email').val(data.hasil.email);
                    $('#gambar_pengguna').val(data.hasil.pengguna.gambar_pengguna);
                    $('#status_pengguna').val('edit');

                    $('#id_role').val(edit_id_role);
                    $('#id_role').trigger('change');

                    $('#id_biro').val(edit_id_biro);
                    $('#id_biro').trigger('change');
                  
                    $('.dz-message').addClass('hides');
                    $('#form_id').append(`<div class="card border-primary rounded mb-2"><img src="`+data.url_src+`" max-width: 90%; height="185px"/></div>`);

                    if(img_user!= null){
                      $('#form_id').append(`<div class="d-m d-flex justify-content-md-end">
                        <button type="button" class="btn btn-sm btn-secondary clear-preview-getimage" data-id="`+data.hasil.id+`">
                        update</button> </div>`);
                    }else{
                      $('#form_id').append(`<div class="d-m d-flex justify-content-md-end">
                        <button type="button" class="btn btn-sm btn-secondary clear-preview-newupload">
                        update</button> </div>`);
                    }
              

                    $('#password').addClass('hides');
                    $('#konfirmasi_password').addClass('hides');
                  } 
                        
                }
            });  
          }
    });
    
  //Clear Image
  $(document).on('click', '.clear-preview-getimage', function () {
      
        var id = $(this).data("id");

            const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success'
            },
            buttonsStyling: false
            })
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Anda akan mengedit Gambar Pengguna !",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, Edit!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: false
                }).then((result) => {
                if (result.isConfirmed) {
                    // updateIsActive(id);
                    deleteGambarPengguna(id);
                    }else if (
                        /* Read more about handling dismissals below */
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        swalWithBootstrapButtons.fire(
                        'Cancelled',
                        'Gambar Pengguna Anda Aman :)',
                        'error'
                        )
                    }
                })
                
    });

    //Clear Image
  $(document).on('click', '.clear-preview-newupload', function () {
    $('#form_id').html('');
    $('.dz-message').removeClass('hides');
    $('.dz-message').addClass('shows');
  });

    function deleteGambarPengguna(id){
      
      $.ajax({  
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
          url:`{{ route("deleteGambarPengguna") }}`,
          method:'POST',  
          data: { 
                _token: $('meta[name="csrf-token"]').attr('content'),
                id : id} ,  
          dataType : "JSON",  
              success:function(data)  
              {  
              if (data.status == "success") {  
                  Swal.fire('Update!',  data.message + ' !','success'); 
                  $('#form_id').html('');
                  $('.dz-message').removeClass('hides');
                  $('.dz-message').addClass('shows');
                  $('#gambar_pengguna').val('');
                  } 
                      else{
                  // Swal.fire('Changes are not saved', '', 'info');
                  }
              }
          }); 
    }


        $(document).on('click', '#add-pengguna', function () {
            var status_pengguna =  $('#status_pengguna').val();
            if(status_pengguna=='edit'){
                save_edit_user();
            }else{
                save_new_user();
            }
            
        });

        //SAVE new Navigasi
        function save_new_user(){
            var formdata = $('#form_pengguna').serializeIncludeDisabled();
            var usrnme = $('#username').val();
            var nmpengguna = $('#nama_pengguna').val();
            var pass = $('#password').val();
            var konfpass = $('#konfirmasi_password').val();
            
            if(usrnme==''){
              Swal.fire('Username Belum Diisi !', '', 'warning');
            }else if(nmpengguna==''){
              Swal.fire('Nama Pengguna Belum Diisi!', '', 'warning');
            }else if(pass!=konfpass){
              Swal.fire('Password Konfirmasi tidak Sesuai', '', 'warning');
            }else{
              
            $.ajax({  
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            url:`{{ route("pengguna.store") }}`,
            method:'POST',  
            data: formdata,  
            dataType : "JSON",  
                success:function(data)  
                {  
                if (data.status == "success") {  
                    Swal.fire('Saved!',  data.message + ' !','success'); 
                  
                    $('#newModal').modal('hide');
                    Livewire.emit('refreshDatatable'); 
                    } 
                        else{
                    Swal.fire('Changes are not saved', '', 'info');
                    }
                }
            });  
          
          }

        }

        function save_edit_user(){
            var formdata = $('#form_pengguna').serializeIncludeDisabled();

            $.ajax({  
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            url:`{{ route("pengguna.storeUpdate") }}`,
            method:'POST',  
            data: formdata,  
            dataType : "JSON",  
                success:function(data)  
                {  
                if (data.status == "success") {  
                    Swal.fire('Saved!',  data.message + ' !','success'); 
                    $('#newModal').modal('hide');
                    Livewire.emit('refreshDatatable');
                    } 
                        else{
                    Swal.fire('Changes are not saved', '', 'info');
                    }
                }
            });  
        }

        $(document).on('click', '#updateIsActive', function () {
                  var id = $(this).data("id");
                  const swalWithBootstrapButtons = Swal.mixin({
                  customClass: {
                      confirmButton: 'btn btn-success'
                  },
                  buttonsStyling: false
                  })
                  Swal.fire({
                      title: 'Apakah anda yakin?',
                      text: "Anda akan mengedit status User!",
                      icon: 'warning',
                      showCancelButton: true,
                      confirmButtonColor: '#3085d6',
                      cancelButtonColor: '#d33',
                      confirmButtonText: 'Yes, Edit Status User!',
                      cancelButtonText: 'No, cancel!',
                      reverseButtons: false
                      }).then((result) => {
                      if (result.isConfirmed) {
                          updateIsActive(id);
                      
                          }else if (
                              /* Read more about handling dismissals below */
                              result.dismiss === Swal.DismissReason.cancel
                          ) {
                              swalWithBootstrapButtons.fire(
                              'Cancelled',
                              'Status User Anda Aman :)',
                              'error'
                              )
                          }
                      })
                
              });
                

        function updateIsActive(id){
            $.ajax({  
            url:`{{ route("updateIsActive") }}`,
            method:'POST',  
            data: { 
                _token: $('meta[name="csrf-token"]').attr('content'),
                id : id} ,  
            dataType : "JSON",  
                success:function(data)  
                {  
                if (data.status == "success") {  
              
                    Swal.fire('Updated!', data.messages ,'success'); 
                    setTimeout(function(){// wait for 5 secs(2)
                      Livewire.emit('refreshDatatable');
                                /* if(data.is_active == "aktif"){
                                  Livewire.emit('refreshDatatable');
                              
                                }else if(data.is_active == "nonaktif"){
                                  // location.reload();
                                  Livewire.emit('refreshDatatable');
                                } */
                              
                              }, 1000); 
                        
                            } 
                        else{
                    Swal.fire('Changes are not saved', '', 'info');
                    }

                    
                      
                }
            });  
        }

        // AREA DROPZONE
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
                  url:'/pengguna/upload',
                  autoProcessQueue: false,
                  // maxFiles: 1, 
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
                          url: '/pengguna/destroyImg',
                          data: { filename: filename,
                                  name: name},
                          success: function (data){
                          // alert(data.name +" File has been successfully removed!"); 
                              console.log("Foto terhapus");
                              $('input[name="gambar_pengguna"]').val(''); 
                              $('input[name="image_name_add"]').val(''); 
                              $('.dz-message').removeClass('hides');
                              $('.dz-message').addClass('shows');

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
                  olddatadzname.innerHTML = response.success;
                
                  file.previewElement.querySelector("img").style.width = "auto";
                  file.previewElement.querySelector("img").style.height = "185px";
                  file.previewElement.querySelector("img").src =  response.image_full_path;

                      $('#form_id').html('');
                      $('.dz-message').addClass('hides');
                      $('#gambar_pengguna').val(response.image_name);
                      $('#form_id').append('<input type="hidden" class="form-control" name="original_filename" value="'+response.image_name+'" />');
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

                
                          var height_img = 720;
                          var width_img =  720;
                  
                  // $cropperModal.modal('show');
                  $cropperModal.modal('show').on("shown.bs.modal", function () {
                      $no = ++c;
                  
                      // initialize cropper for uploaded image
                      var $image = $('#img');
                      $image.cropper({
                        
                          // aspectRatio: 16 / 9,
                          // autoCropArea: 1,
                              viewMode: 1,
                              aspectRatio: 1,
                              scalable:true,
                              movable: false,
                              cropBoxResizable: true,
                            /*  data:{ //define cropbox size
                                  height: height_img,
                                  width:  width_img,
                              }, */
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
                        var hasildemo = $('#hasildemo').val();
                              $('#imgdemo').attr('src' ,hasildemo);
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
                      
                  });
              });



  /*   $( "#addRoleModal" ).on('shown', function(){
      var id = $(this).data('(id');
      alert(id);
      $('#modalRoleName').val(id);
    }); */


    $(document).on('click', '.getdetailUser', function(e) {
      var id = $(this).attr('data-id');
      get_detail(id);
    });

  /*   $('.getdetailUser').click(function() {
      var id = $(this).data('(id');   
      alert(id);
              }); */

  /*  $( ".getdetailUser" ).on('click', function(){
      var id = $(this).data('(id');
      alert(id);
      $('#modalRoleName').val(id);
    }); */
          function get_detail(id){
            $.ajax({
                      url: "{{url('api/fetch-user')}}",
                      type: "post",
                      data: {
                            id: id,
                          _token: '{{csrf_token()}}'
                          },
                      dataType: 'json',
                      success: function (result) {
                        var nama_pengguna = result.user_detail.pengguna.nama_pengguna;

                      $('#modalRoleName').val(nama_pengguna);
                      }

                  });
          }
                
  </script>
  @endpush