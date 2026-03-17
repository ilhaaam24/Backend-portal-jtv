<div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Update Tipe Tulisan</div>
                    <div class="card-body">
                        <form wire:submit.prevent="update">
                            <div class="form-group mb-3">
                                <label for="judul">Judul</label>
                                <input type="judul" wire:model="judul" class="form-control" id="judul" placeholder="Enter a title">
                                @error('judul') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="kategori">Kategori</label>
                                <textarea cols="60" rows="2" class="form-control" wire:model="kategori" name="kategori">
                                </textarea>
                                @error('kategori') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{route('tipetulisan.index')}}" class="btn btn-danger">Back</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>