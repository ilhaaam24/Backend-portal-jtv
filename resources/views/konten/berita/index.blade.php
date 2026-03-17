@extends('layouts.materialize')
    @push('css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.16.6/dist/sweetalert2.all.min.js"></script>
    <link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/sweetalert2@10.10.1/dist/sweetalert2.min.css'>

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
                    <span class="text-muted fw-light">Konten /</span> Daftar Berita</span>
             
            </h5>
            <h5 class="card-header align-items-center mb-2">
              {{-- @can('create berita')
              <a href="{{route('berita.create')}}" class="btn btn-md btn-primary"> Add New  </a>
              @endcan --}}

{{-- {{ dd(auth()->user()->getPermissionsViaRoles()->where('name', 'create berita')); }} --}}
            {{--   @if(auth()->user()->can('create berita'))
              <a href="{{route('berita.create')}}" class="btn btn-md btn-primary"> Add New  </a>
            @endif --}}
            




              {{-- @if(filled(auth()->user()->getPermissionsViaRoles()->where('name', 'create berita'))) --}}
              @cekRolePermission('create berita')
              <a href="{{route('berita.create')}}" class="btn btn-md btn-primary"> Add New  </a>
            
             @endcekRolePermission
             
       </h5>
          </div>
           
            <div class="row gy-4">
              <div class="col-md-12 col-lg-12">
              <div class="card">
                <div class="card-header">
                
              </div>
                <div class="card-body">
                  @cekRolePermission('read berita')
                        {{-- <livewire:berita-table/> --}}
                        @livewire('berita-table')
                   @endcekRolePermission
                </div>
                </div>
              </div>
            </div>
        </div>
             
      @endsection

     
    {{--   <h4 class="fw-bold mb-4"><span class="text-muted fw-light">Konten /</span> Jurnalisme Warga</h4>
      <div class="row gy-4">
        <div class="col-md-12 col-lg-12">
        <div class="card">
          <div class="card-body">
                  <livewire:opini-warga-table/>
          </div>
          </div>
        </div>
        </div>
 --}}
    @push('js')
    {{-- <script src="//unpkg.com/alpinejs" defer></script> --}}
    <script>

      $( document ).ready(function() {
        
        const url = document.URL;
        function load() {
          // console.log(url);
        }
        window.onload = load()

        $('.select2').select2();
    });

    /* $(document).on('click', '#reload_table', function () {
       Livewire.emit('refreshDatatable');
     }); */

     $(document).on('click', '#terbaru_berita', function () {
              var id = $(this).data('id');
              terbaruBerita(id);
     });

     $(document).on('click', '#terbaik_berita', function () {
              var id = $(this).data('id');
              terbaikBerita(id);
     });

     
     function terbaruBerita(id){
        $.ajax({  
            url:`{{ route("berita.list_terbaru") }}`,
            method:'POST',  
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {'id' : id },  
            dataType : "JSON",  
                success:function(data){  
                if (data.status == "success") {  
                    Swal.fire('Success!',  data.message + ' !','success');  
                    Livewire.emit('refreshDatatable');} 
                    else{ Swal.fire('Changes are not saved', '', 'info');}
                }
        });  
      }

      function terbaikBerita(id){
        $.ajax({  
            url:`{{ route("berita.list_terbaik") }}`,
            method:'POST',  
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            data: {'id' : id },  
            dataType : "JSON",  
                success:function(data){  
                if (data.status == "success") {  
                    Swal.fire('Success!',  data.message + ' !','success');  
                    Livewire.emit('refreshDatatable');} 
                    else{ Swal.fire('Changes are not saved', '', 'info');}
                }
        });  
      }

    $(document).on('click', '#trash_berita', function () {
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
                   /*  Swal.fire(
                      'Deleted!',
                      'Your file has been deleted.',
                      'success'
                    ) */
                    trashBerita(id);
                  }
                })
              }

              function trashBerita(id){
                $.ajax({  
                url:`{{ route("berita.trashed") }}`,
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