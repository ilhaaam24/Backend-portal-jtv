@extends('layouts.materialize')
    @push('css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
    @endpush

        @section('content')
        <div class="container-xxl flex-grow-1 container-p-y">
          @if(Session::has('success'))
        
            <div class="alert alert-warning">

              <strong>Success: </strong>{{ Session::get('success') }}
              
              <button type="button" class="btn-close btn-pinned" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>
            @endif
         
            <h5 class="card-header d-flex justify-content-between align-items-center mb-2">
              <span class="fw-bold mb-4" style="font-size:large;">
                <span class="text-muted fw-light">Konten /</span> Sorot
              </span>
              <a href="{{route('konten.createsorot')}}" class="btn btn-md btn-primary"> Add New  </a>
            </h5>
      
            <div class="row gy-4">
              <div class="col-md-12 col-lg-12">
              <div class="card">
                <div class="card-body">
                  @livewire('sorot-table')
                </div>
                </div>
              </div>
              </div>
            </div>
             
        @endsection

    @push('js')
    <script>
       $(document).on('click', '#delete_sorot', function () {
              var id = $(this).data('id');
            
              deleteConfirmation(id);
     });
              function deleteConfirmation(id) {
                Swal.fire({
                  title: 'Are you sure?',
                  text: "You won't be able to revert this!",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                  if (result.isConfirmed) {
                    deleteSorot(id);
                  }
                })
              }

              function deleteSorot(id){
                $.ajax({  
                url:`{{ route("konten.deletesorot") }}`,
                method:'POST',  
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {'id' : id},  
                dataType : "JSON",  
                    success:function(data)  
                    {  
                   
                    if (data.status == "success") {  
                        Swal.fire('Saved!',  data.message + ' !','success');  
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