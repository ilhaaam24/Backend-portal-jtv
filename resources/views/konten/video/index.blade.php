@extends('layouts.materialize')
    @push('css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>
    @endpush

        @section('content')
        <div class="container-xxl flex-grow-1 container-p-y">
            <h5 class="card-header d-flex justify-content-between align-items-center mb-2">
              <span class="fw-bold mb-4" style="font-size:large;">
                <span class="text-muted fw-light">Konten /</span> Video
              </span>
              <a href="{{route('video.create')}}" class="btn btn-md btn-primary"> Add New  </a>
            </h5>
            <div class="row gy-4">
              <div class="col-md-12 col-lg-12">
              <div class="card">
                <div class="card-body">
                  @livewire('video-table')
                        {{-- <livewire:iklan-table/> --}}
                </div>
                </div>
              </div>
              </div>
            </div>
             
        @endsection

    @push('js')
            <script>
              $(document).on('click', '.img_link', function () {
                var link_vid = $(this).attr("data-link");
                var win = window.open(link_vid, '_blank');
                if (win) {
                    //Browser has allowed it to be opened
                    win.focus();
                } else {
                    //Browser has blocked it
                    alert('Please allow popups for this website');
                }
            });
      
            $(document).on('click', '#delete_video', function () {
              var id = $(this).data('id');
              // alert(id);
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
                   /*  Swal.fire(
                      'Deleted!',
                      'Your file has been deleted.',
                      'success'
                    ) */
                    deleteVideo(id);
                  }
                })
              }

              function deleteVideo(id){
                $.ajax({  
                url:`{{ route("video.delete") }}`,
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