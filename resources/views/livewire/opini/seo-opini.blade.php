<div> <!-- Added this wrapping div -->
    
    <div class="form-floating form-floating-outline mb-3 mt-3">
   
    <input type="text" wire:model="judul" wire:keyup='generateSlug' class="form-control" 
    id="judul" name="judul_opini" placeholder="Enter a title" aria-describedby="floatingInputJudul"
   >
    <label for="judul Berita">Judul Opini</label>
    <div id="floatingInputJudul" class="form-text">
    </div>
    @error('judul') <span class="text-danger">{{ $message }}</span> @enderror
    </div>

<div class="form-floating form-floating-outline mb-3 mt-3">
   
    <input type="text" wire:model="seo" class="form-control" name="seo_opini"
    id="seo" placeholder="Generate SEO" aria-describedby="floatingInputSeo">
    <label for="seo Berita">(SEO)</label>
    <div id="floatingInputSeo" class="form-text"> </div>
    @error('seo') <span class="text-danger">{{ $message }}</span> @enderror
</div>

</div> <!-- Added this closing tag for the wrapping div -->