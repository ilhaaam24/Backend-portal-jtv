<div>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Create new post</div>
                    <div class="card-body">
                        <form wire:submit.prevent="store">
                            <div class="form-group mb-3">
                                <label for="judul">Judul</label>
                                <input type="text" wire:model="judul" wire:keyup='generateSlug' class="form-control" id="judul" placeholder="Enter a title">
                                @error('judul') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="seo">Seo</label>
                                <input type="text" wire:model="seo" class="form-control" id="seo" placeholder="Enter a seo">
                                @error('seo') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group mb-3">
                                <label for="kategori">Kategori</label>
                                <textarea cols="30" rows="2" class="form-control" wire:model="kategori" name="kategori"></textarea>
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