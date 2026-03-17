@extends('layouts.materialize')
@push('css')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>
<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>

<style>
  .swal2-container {
        z-index: 10000;
        }
  .roleborder{
    border: 2px solid #978d8d2e;
    width: auto;
    padding: 15px;
    border-radius: 0.5rem !important;
  }
</style>
@endpush

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-medium mb-1">Biro List</h4>
    <p class="mb-4">
   
    </p>
    <!-- Role cards -->
    <div class="row g-4">
    {{-- new --}}
    @foreach ($list_biro as $item)
        <div class="col-xl-4 col-lg-6 col-md-6">
          <div class="card">
            <div class="card-body">
              <div class="d-flex justify-content-between mb-2">
                <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">

                  <li
                    data-bs-toggle="tooltip"
                    data-popup="tooltip-custom"
                    data-bs-placement="top"
                    title="{{$item->nama_biro}}"
                    class="avatar pull-up">
                    <img class="rounded-circle" src="{{ config('jp.path_url_be').config('jp.path_url_no_img')}}" alt="Avatar"  style="border: 2px solid #eae0ebb8;"/>
                    
                  </li>
            

                </ul>
              </div>
              <div class="d-flex justify-content-between align-items-end">
                <div class="role-heading">
                  <h4 class="mb-1 text-body">{{ $item->nama_biro }}</h4>
                 
                </div>
                <button data-seo="{{ $item->seo }}" data-nama="{{ $item->nama_biro }}"
                  data-link="{{ $item->link }}"
                class="biro-edit-modal btn btn-info btn-sm"><i class="mdi mdi-pencil mdi-12px"></i>edit
              </button>

              </div>
            </div>
          </div>
        </div>
    @endforeach
   
    {{-- end --}}


     
    </div>
    <!--/ Role cards -->

    <!-- Add Role Modal -->
    <!-- Add Role Modal -->
    <div class="modal fade" id="BiroModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered modal-add-new-role">
        <div class="modal-content p-3 p-md-5">
          <button
          type="button"
          class="btn-close btn btn-light p-3 rounded-circle position-absolute"
          data-bs-dismiss="modal"
          aria-label="Close" style="right: 30px;z-index: 5;"></button>
          <div class="modal-body p-md-0">
           
            <!-- Add role form -->
            <form id="addRoleForm" class="row g-3" onsubmit="return false">
         
              <div class="col-12 col-md-12">
                <div class="card mb-3">
                  <div class="card-header d-flex justify-content-between">
                <h5>Form Biro</h5>
                  </div>
                  <div class="card-body" id='form_role_menu'>
                    <div class="row">    
                      <div class="col-12 col-md-8">
                        <div class="form-floating form-floating-outline mb-3 mt-1">
                            <input type="text" class="form-control" id="nama_biro" name="nama_biro" 
                            style="text-transform: uppercase" placeholder=" " 
                            aria-describedby="floatingInputBiro" value="">
                            <label for="Nama Biro">Nama Biro</label>
                            <div id="floatingInputBiro" class="form-text">
                            </div>
                            @error('nama_biro') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>

                      <div class="col-12 col-md-8">
                        <div class="form-floating form-floating-outline mb-3 mt-1">
                            <input type="text" class="form-control" id="seo_biro" name="seo_biro" 
                            placeholder=" " 
                            aria-describedby="floatingInputSeoBiro" value="">
                            <label for="Seo Biro">Seo Biro</label>
                            <div id="floatingInputSeoBiro" class="form-text">
                            </div>
                            @error('seo_biro') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                                    
                      <div class="col-12 col-md-8">
                        <div class="form-floating form-floating-outline mb-3 mt-1">
                            <input type="text" class="form-control" id="link_biro" name="link_biro" 
                            placeholder=" " 
                            aria-describedby="floatingInputLinkBiro" value="">
                            <label for="Link Biro">Link Biro</label>
                            <div id="floatingInputLinkBiro" class="form-text">
                            </div>
                            @error('link_biro') <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                    </div>
                  </div>         
               
                  </div>
              </div>

              <div class="col-12 text-center">
                <button type="button" class="btn btn-primary me-sm-3 me-1" id="submit_biro">Submit</button>
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

    <!-- / Add Role Modal -->
  </div>
@endsection

@push('js')
<script>
        $(document).ready(function () {
          $('.select2').select2();
          $(".select2modal").select2({ dropdownParent: $("#BiroModal") });

        });

      // Get Submit data Role Menu Checked
      $(document).on('click', '#submit_biro', function(){
        var link = $('#link_biro').val();
        var seo = $('#seo_biro').val();
        var nama = $('#nama_biro').val();

            $.ajax({
              type: "POST",
              url: "{{url('SubmitBiroUpdate')}}",
              data: { link: link, seo: seo, nama: nama,
                      _token: '{{csrf_token()}}'
                  },
              cache: false,
              success: function (res) {
                    if (res.status == "success") {  
                      Swal.fire('Success!',  res.message + ' !','success');  
                      setTimeout(function(){
                              location.reload(); 
                              window.location=data.url;
                          }, 1000); 
                    } else if(res.status == "error"){
                          Swal.fire('Warning!',  res.message + ' !','warning');  
                      }
                  }
            });
      });

      /* ---------------------------------------------- */
      $(document).on('click', '.biro-edit-modal', function () {
        $('#BiroModal ').modal('show');  
        let nama = $(this).data("nama");
        let link = $(this).data("link");
        let seo = $(this).data("seo");
        $('#nama_biro').val(nama);
        $('#seo_biro').val(seo);
        $('#link_biro').val(link);
        $('#nama_biro').prop('disabled', true);
        $('#seo_biro').prop('disabled', true);
      });

      $('#BiroModal').on('hidden.bs.modal', function () {
        $('#id_role').prop('disabled', false);
      });

</script>

@endpush