<div wire:ignore.self class="modal fade" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updateModalLabel">Update Data Tipe Tulisan</h5>
      
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
           <div class="modal-body">
                <form>
      
                   <div class="form-group mb-3">
                       <label for="judul">Judul</label>
                       <input type="hidden" wire:model="idtipetulisan">
                       <input type="text" wire:model="judul" wire:keyup='generateSlug' class="form-control" id="judul" placeholder="Enter a title">
                       @error('judul') <span class="text-danger">{{ $message }}</span> @enderror
                   </div>
               
                   <div class="form-group mb-3">
                       <label for="seo">Seo</label>
                       <input type="text" wire:model="seo" class="form-control" id="seo" placeholder="Enter a seo">
                       @error('seo') <span class="text-danger">{{ $message }}</span> @enderror
                   </div>
               
                   <div class="form-group mb-3">
                       <label for="exampleFormControlInput2">Category</label>
                       <input type="text" class="form-control" wire:model="kategori" id="exampleFormControlInput2" placeholder="Enter Category">
                       @error('kategori') <span class="text-danger">{{ $message }}</span>@enderror
                   </div>
                  {{--  <button wire:click.prevent="update()" class="btn btn-dark">Update</button>
                   <button wire:click.prevent="cancel()" class="btn btn-danger">Cancel</button> --}}
                   <div class="modal-footer">
                    <button type="button" wire:click.prevent="cancel()" class="btn btn-secondary close-btn" data-bs-dismiss="modal">Close</button>
                    {{-- <button type="submit" class="btn btn-primary close-modal">Save changes</button> --}}
                    <button type="button" wire:click.prevent="update()" class="btn btn-primary" data-bs-dismiss="modal">Save changes</button>
                    {{-- <button type="button"  wire:click.prevent="update()" class="btn btn-primary close-modal">Save changes</button> --}}
    
                    </div>
                </form>
   
            </div>
            
        </div>
    </div>
</div>