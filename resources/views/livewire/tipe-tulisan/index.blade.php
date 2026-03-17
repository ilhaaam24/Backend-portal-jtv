
{{-- @extends('layouts.materialize')
    @push('css')
    @endpush

        @section('content') --}}
        <div>
            <div class="container">
              @if (session()->has('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                    <button type="button" class="btn-close btn btn-sm btn-secondary" data-bs-dismiss="alert" aria-label="Close"></button>
                    {{-- <button type="button btn btn-sm" class="close" data-bs-dismiss="alert">x</button> --}}
                </div>
                @endif

               
                    @include('livewire.tipe-tulisan.createmodal')
                    {{-- @include('livewire.tipe-tulisan.updatemodal') --}}
            

                
                <div class="row justify-content-center">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">
                                <div class="float-right">
                                    <a href="{{route('tipetulisan.create')}}" class="btn btn-primary"> Create new post </a>
                                </div>
                            </div>
                            <div class="card-body">
                             <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">Judul</th>
                                            <th scope="col">Kategori</th>
                                           
                                            <th scope="col">Opsi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($tipetulisan as $data)
                                        <tr>
                                            <td>{{$data->judul}}</td>
                                            <td>{{$data->kategori}}</td>
                                            <td>
                                                <button data-bs-toggle="modal" data-bs-target="#updateModal" wire:click="edit({{ $data->id }})" class="btn btn-primary btn-sm">Edit</button>
                                                {{-- <button  type="button" class="btn btn-primary btn-sm" wire:click="edit({{ $data->id }})" data-bs-toggle="modal" data-bs-target="#updateModal">  Edit</button> --}}
                                                <button wire:click="deleteTipetulisan({{ $data->id }})" class="btn btn-danger btn-sm">Delete</button>
                                            </td>
                                            </td>
                                     
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3">No Tipe Tulisan found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                {{$tipetulisan->links()}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
           
  {{--           <script type="text/javascript">
                window.livewire.on('update', () => {
                    alert('update successfully');
                    $('#updateModal').modal('hide');
                });
          
          
             function deletePost(id) {
                alert(id);
              
             }
            </script>
            --}}
        </div>
        
 {{--        @endsection

    @push('js')
@endpush
 --}}