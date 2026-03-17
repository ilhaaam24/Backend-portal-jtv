<div>
    <div class="col-md-12 mb-2">
        @if(session()->has('success'))
            <div class="alert alert-success" role="alert">
                {{ session()->get('success') }}
            </div>
        @endif
        @if(session()->has('error'))
            <div class="alert alert-danger" role="alert">
                {{ session()->get('error') }}
            </div>
        @endif
        @if($addPost)
            @include('livewire.create')
        @endif
        @if($updatePost)
            @include('livewire.update')
        @endif
    </div>
    <div class="col-md-12 mb-2">
        <div class="card">
            <div class="card-body">
                @if(!$addPost)
                <button wire:click="addPost()" class="btn btn-primary btn-sm float-right">Add New Post</button>
                @endif
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($berita) > 0)
                                @foreach ($berita as $post)
                                    <tr>
                                        <td>
                                            {{$post->judul_berita.' -'.$post->id_berita}}
                                        </td>
                                        <td>
                                            {{$post->rangkuman_berita}}
                                        </td>
                                        <td>
                                            <button wire:click="editPost({{$post->id_berita}})" class="btn btn-primary btn-sm">Edit</button>
                                            <button onclick="deletePost({{$post->id_berita}})" class="btn btn-danger btn-sm">Delete</button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3" align="center">
                                        No berita Found.
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
 
</div>

<script>
    Livewire.on('addPost', postId => {
        alert('A post was added with the id of: ' + postId);
    })
</script>