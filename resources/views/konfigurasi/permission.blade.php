@extends('layouts.materialize')
@push('css')
@endpush

        
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  {{--   <h4 class="fw-semibold pt-3 mb-1">Permissions List</h4> --}}
    <h5 class="card-header d-flex justify-content-between align-items-center mb-2">
      <span class="fw-bold mb-4" style="font-size:large;">
        <span class="text-muted fw-light">Konfigurasi /</span> Permissions List</span>
     <button class="btn btn-md btn-primary" id="create_new"> Add New  </button>
    </h5>
 {{--    <p class="mb-4">
      Each category (Basic, Professional, and Business) includes the four predefined roles shown below.
    </p> --}}

    <!-- Permission Table -->
    <div class="card">
      <div class="card-body">
        @livewire('permission-table')

      </div>
    </div>
    <!--/ Permission Table -->

    <!-- Modal -->
    <!-- Add Permission Modal -->
    <div class="modal fade" id="addPermissionModal" tabindex="-1" aria-hidden="true" role="dialog" >
      <div class="modal-dialog">
        <div class="modal-content p-3 p-md-5">
          <button
            type="button"
            class="btn-close btn-pinned"
            data-bs-dismiss="modal"
            aria-label="Close"></button>
          <div class="modal-body p-md-0">
            <div class="text-center mb-4">
              <h3 class="mb-2 pb-1"> <span id="title_modal"></span></h3>
         
            </div>
            <form id="addPermissionForm" autocomplete="off">
              <div class="col-12 mb-3">
                <div class="form-floating form-floating-outline mb-3">
                  <select id="id_navigation" name="id_navigation" class="select2modal form-select">
                      <option value="">-- Select Menu --</option>
                      @foreach ($navigation as $data)
                      <option value="{{$data->id}}"
                          {{-- @if ($status_form === "edit_form")
                              {{ $data->id_navbar == $item->id_menu_berita ? 'selected' : '' }}
                          @endif --}}
                          >
                          {{$data->name}}
                      </option>
                      @endforeach
                  </select>
                  <label for="id_navigation">Menu</label>
              </div>
              <div class="form-floating form-floating-outline mb-3">
                <input type="text" id="name_permission"
                  name="name_permission" class="form-control"
                  placeholder="Permission Name" autofocus />
                <label for="name_permission">Permission Name</label>
              </div>

              <div class="form-floating form-floating-outline mb-3 hides">
                <input type="text" id="status_permission"
                  name="status_permission" class="form-control"
                  placeholder="Permission Status" autofocus />
                <label for="status_permission">Permission Status</label>
              </div>

              <div class="form-floating form-floating-outline mb-3 hides">
                <input type="text" id="permission_id"
                  name="permission_id" class="form-control"
                  placeholder="Permission ID" autofocus />
                <label for="permission_id">Permission ID</label>
              </div>

              </div>
             
            </form>
              <div class="col-12 text-center demo-vertical-spacing">
                <button type="button" id ="submit_permission" class="btn btn-primary me-sm-3 me-1"> <span id="title_submit"></span></button>
                <button 
                  type="reset"
                  class="btn btn-outline-secondary"
                  data-bs-dismiss="modal"
                  aria-label="Close">
                  Discard
                </button>
              </div>

          </div>
        </div>
      </div>
    </div>
    <!--/ Add Permission Modal -->


    <!-- /Modal -->
  </div>

    
@endsection

@push('js')
  <script>
       $(document).ready(function () {
            $('.select2').select2();

            $(".select2modal").select2({
                dropdownParent: $("#addPermissionModal")
            });
       });

         //clear disabled serialize form function
         $.fn.serializeIncludeDisabled = function () {
                let disabled = this.find(":input:disabled").removeAttr("disabled");
                let serialized = this.serialize();
                disabled.attr("disabled", "disabled");
                return serialized;
            };


      $(document).on('click', '#create_new', function () {
          // Swal.fire('Maaf!',  'silakan tempatkan kursor anda pada text editor!','warning');  
          $('#title_modal').html('Add New');
          $('#title_submit').html('Create');
          $('#addPermissionModal ').modal('show');
          $('#status_permission').val('create');
      });

      $(document).on('click', '#get_edit_permission', function () {
          // Swal.fire('Maaf!',  'silakan tempatkan kursor anda pada text editor!','warning');  
          $('#title_modal').html('Edit Permission');
          $('#title_submit').html('Update');
          $('#addPermissionModal ').modal('show');
          $('#status_permission').val('edit');

          var permission_id = $(this).data("id");
          var navigation_id = $(this).data("nav");
          var permission_name = $(this).data("name");
          
          $('#id_navigation').val(navigation_id).trigger('change');
          $('#name_permission').val(permission_name);
          $('#permission_id').val(permission_id);


      });

      $(document).on('click', '#submit_permission', function () {
          var name_permission =  $('#name_permission').val();
          var status_permission =  $('#status_permission').val();
          if(status_permission=='edit'){
             save_edit_permission();
          }else{
            save_new_permission();
          }
          
      });
      
      $('#addPermissionModal').on('hidden.bs.modal', function () {
        clearFormPermission();
      });
      function clearFormPermission(){
          $('#title_modal').html('');
          $('#title_submit').html('');
          $('#status_permission').val('');
          $('#id_navigation').val('').trigger('change');
          $('#name_permission').val('');
          $('#permission_id').val('');
       }

       function save_edit_permission(){  
        var formdata = $('#addPermissionForm').serializeIncludeDisabled();
        var id_navigation = $('#id_navigation').val();
          var name_permission = $('#name_permission').val();

          if(id_navigation==''){
            Swal.fire('Menu Belum Diisi !', '', 'warning');
          }else if(name_permission==''){
            Swal.fire('Nama Permission Belum Diisi!', '', 'warning');
          }else{
            
          $.ajax({  
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
          url:`{{ route("permission.update") }}`,
          method:'POST',  
          data: formdata,  
          dataType : "JSON",  
              success:function(data)  
              {  
              if (data.status == "success") {  
                  Swal.fire('Saved!',  data.message + ' !','success'); 
                
                  $('#addPermissionModal').modal('hide');
                  Livewire.emit('refreshDatatable'); 
                  } 
                      else{
                  Swal.fire('Changes are not saved', '', 'info');
                  }
              }
          });  
        }
      
       };
      //SAVE new Permission
      function save_new_permission(){
          var formdata = $('#addPermissionForm').serializeIncludeDisabled();
          var id_navigation = $('#id_navigation').val();
          var name_permission = $('#name_permission').val();
          
          if(id_navigation==''){
            Swal.fire('Menu Belum Diisi !', '', 'warning');
          }else if(name_permission==''){
            Swal.fire('Nama Permission Belum Diisi!', '', 'warning');
          }else{
            
          $.ajax({  
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
          url:`{{ route("permission.store") }}`,
          method:'POST',  
          data: formdata,  
          dataType : "JSON",  
              success:function(data)  
              {  
              if (data.status == "success") {  
                  Swal.fire('Saved!',  data.message + ' !','success'); 
                
                  $('#addPermissionModal').modal('hide');
                  Livewire.emit('refreshDatatable'); 
                  } 
                      else{
                  Swal.fire('Changes are not saved', '', 'info');
                  }
              }
          });  
        }
      }

  </script>
@endpush