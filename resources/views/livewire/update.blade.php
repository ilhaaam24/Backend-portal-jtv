<div class="card">
    <div class="card-body">
        <form>
            <div class="form-group mb-3">
                <label for="judul_berita">judul_berita:</label>
                <input type="text" class="form-control @error('judul_berita') is-invalid @enderror" id="judul_berita" placeholder="Enter judul_berita" wire:model="judul_berita">
                @error('judul_berita')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group mb-3">
                <label for="artikel_berita">artikel_berita:</label>
                <textarea class="form-control @error('artikel_berita') is-invalid @enderror" id="artikel_berita" wire:model="artikel_berita" placeholder="Enter artikel_berita"></textarea>
                @error('artikel_berita')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="d-grid gap-2">
                <button wire:click.prevent="updatePost()" class="btn btn-success btn-block">Update</button>
                <button wire:click.prevent="cancelPost()" class="btn btn-secondary btn-block">Cancel</button>
            </div>
        </form>
    </div>
</div>