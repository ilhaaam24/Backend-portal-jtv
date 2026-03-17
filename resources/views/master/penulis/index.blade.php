@extends('layouts.materialize')
    @push('css')
    @endpush

        @section('content')
        <div class="container-xxl flex-grow-1 container-p-y">
          <div class="d-flex justify-content-between">
            <h5 class="card-header align-items-center mb-2">
              <span class="text-muted fw-light">Master /</span> Penulis</h5>
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
                  @livewire('penulis-table')
                      
                </div>
                </div>
              </div>
              </div>
            </div>
             
               <!-- Create new pengguna Modal -->
        <div class="modal fade" id="newModal" tabindex="-1" aria-hidden="true">
          <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
            <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title"><span id="title_form"></span> Penulis</h5>
                  <button
                  type="button"
                  class="btn-close btn btn-light p-2 rounded-circle position-absolute"
                  data-bs-dismiss="modal"
                  aria-label="Close" style="right: 30px;z-index: 5;"></button>
              </div>

                  <div class="modal-body" style="background:#f7f7f9;">
          
                      <form id="form_penulis" action="" autocomplete="off">
                        <div class="row">
                          <div class="col-12 col-md-12 mb-3">
                            <div class="card">
                              <div class="card-header">
                                <label for="Image" class="text-primary">Form</label>
                              </div>
                              <div class="card-body">
                                <div class="form-floating form-floating-outline mb-3">
                                  <input type="text" class="form-control" id="nama_penulis" name="nama_penulis" 
                                  placeholder=" " aria-describedby="floatingNamaPenulis">
                                  <label for="Nama Penulis">Nama Penulis</label>
                                  <div id="floatingNamaPenulis" class="form-text">
                                  </div>
                                </div>

                                <div class="form-floating form-floating-outline mb-3">
                                  <input type="text" class="form-control" id="username" name="username" 
                                  placeholder=" " aria-describedby="floatingUsername">
                                  <label for="Username">Username</label>
                                  <div id="floatingUsername" class="form-text">
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

                              <div class="form-floating form-floating-outline mb-3">
                                <input type="number" class="form-control" id="telp_penulis" name="telp_penulis"  maxlength="13"
                                oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                                placeholder=" " aria-describedby="floatingTelpPenulis">
                                <label for="Telp Penulis">Telp Penulis</label>
                                <div id="floatingTelpPenulis" class="form-text">
                                </div>
                              </div>
                            
                              <div class="form-floating form-floating-outline mb-3 mt-3 hides">
                                <input type="text" class="form-control" id="status_penulis" 
                                name="status_penulis" placeholder=" " 
                                aria-describedby="floatingStatus">
                                <label for="Status">Status</label>
                                <div id="floatingStatus" class="form-text">
                                </div>
                              </div>

                            </div>
                          </div>
                        </div>
                        
                          <div class="col-12 col-md-4 hides">
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
                                  <input type="text" class="form-control" id="gambar_penulis" name="gambar_penulis" 
                                  placeholder=" " aria-describedby="floatingGambarPenulis">
                                  <label for="Gambar Penulis">Gambar Penulis</label>
                                  <div id="floatingGambarPenulis" class="form-text">
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
                      <button type="submit" class="btn btn-primary" id="add-penulis"><span id="aksi_submit"></span></button>
                  </div>
              </div>
          </div>
        </div>
        @endsection

    @push('js')
    <script src="//unpkg.com/alpinejs" defer></script>
    <script>
      $(document).ready(function () {
      $('.select2').select2(
        {
          dropdownParent: $('#addPenulis'),
          dropdownParent: $('#newModal')
        }
      );
  
      })

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

      $('#newModal').on('show.bs.modal', function () {
        $("#form_penulis")[0].reset();
        $("#aksi_submit").text("Create");
        $("#title_form").text("Create");
        $("#status_penulis").val("create");
      });

      $('#newModal').on('hidden.bs.modal', function () {
          clearForm();
      });

      function clearForm(){

        $("#form_penulis")[0].reset();
        $("#aksi_submit").text("Create");
        $("#title_form").text("Create");
        $("#status_penulis").val("create");
      
      }

      // simpan button
      $(document).on('click', '#add-penulis', function () {
            var status_penulis =  $('#status_penulis').val();
            if(status_penulis=='edit'){
                save_edit_penulis();
            }else{
                save_new_penulis();
            }
        });

         //SAVE new Navigasi
         function save_new_penulis(){
            var formdata = $('#form_penulis').serializeIncludeDisabled();
            var usrnme = $('#username').val();
            var nmpengguna = $('#nama_penulis').val();
            var pass = $('#password').val();
            var konfpass = $('#konfirmasi_password').val();
            
            if(usrnme==''){
              Swal.fire('Username Belum Diisi !', '', 'warning');
            }else if(nmpengguna==''){
              Swal.fire('Nama Penulis Belum Diisi!', '', 'warning');
            }else if(pass!=konfpass){
              Swal.fire('Password Konfirmasi tidak Sesuai', '', 'warning');
            }else{
              
            $.ajax({  
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            url:`{{ route("penulis.store") }}`,
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

        function save_edit_penulis(){
            var formdata = $('#form_penulis').serializeIncludeDisabled();

            $.ajax({  
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
            url:`{{ route("penulis.storeUpdate") }}`,
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



    </script>
@endpush