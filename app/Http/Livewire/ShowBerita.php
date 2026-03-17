<?php

namespace App\Http\Livewire;

use App\Models\Berita;
use Livewire\Component;

class ShowBerita extends Component
{
    public $berita, $judul_berita, $artikel_berita, $id_berita, $updatePost = false, $addPost = false;
 
    /**
     * delete action listener
     */
    protected $listeners = [
        'deletePostListner'=>'deletePost'
    ];
 
    /**
     * List of add/edit form rules
     */
    protected $rules = [
        'judul_berita' => 'required',
        'artikel_berita' => 'required'
    ];
 
    /**
     * Reseting all inputted fields
     * @return void
     */
    public function resetFields(){
        $this->judul_berita = '';
        $this->artikel_berita = '';
    }
 
    /**
     * render the post data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function render()
    {
        $this->berita = Berita::select('id_berita', 'judul_berita', 'rangkuman_berita')
        ->get();
        return view('livewire.show-berita');
    }
 
    /**
     * Open Add Post form
     * @return void
     */
    public function addPost()
    {
        $this->resetFields();
        $this->addPost = true;
        $this->updatePost = false;
    }
     /**
      * store the user inputted post data in the berita table
      * @return void
      */
    public function storePost()
    {
        $this->validate();
        try {
            Berita::create([
                'judul_berita' => $this->judul_berita,
                'rangkuman_berita' => $this->artikel_berita
            ]);
            session()->flash('success','Post Created Successfully!!');
            $this->resetFields();
            $this->addPost = false;
        } catch (\Exception $ex) {
            session()->flash('error','Something goes wrong!!');
        }
    }
 
    /**
     * show existing post data in edit post form
     * @param mixed $id
     * @return void
     */
    public function editPost($id){
        return $id;exit;
        try {
            // echo $id;exit;
            $post = Berita::where('id_berita',$id)->first();

            if( !$post) {
                session()->flash('error','Post not found');
            } else {
                $this->judul_berita = $post->judul_berita;
                $this->artikel_berita = $post->rangkuman_berita;
                $this->id_berita = $post->id_berita;
                $this->updatePost = true;
                $this->addPost = false;
            }
        } catch (\Exception $ex) {
            session()->flash('error','Something goes wrong!!');
        }
 
    }
 
    /**
     * update the post data
     * @return void
     */
    public function updatePost()
    {
        $this->validate();
        try {
            Berita::where('id_berita', $this->id_berita)->update([
                'judul_berita' => $this->judul_berita,
                'rangkuman_berita' => $this->artikel_berita
            ]);
            session()->flash('success','Post Updated Successfully!!');
            $this->resetFields();
            $this->updatePost = false;
        } catch (\Exception $ex) {
            session()->flash('success','Something goes wrong!!');
        }
    }
 
    /**
     * Cancel Add/Edit form and redirect to post listing page
     * @return void
     */
    public function cancelPost()
    {
        $this->addPost = false;
        $this->updatePost = false;
        $this->resetFields();
    }
 
    /**
     * delete specific post data from the berita table
     * @param mixed $id
     * @return void
     */
    public function deletePost($id)
    {
        try{
            Berita::where('id_berita',$id)->delete();
            session()->flash('success',"Post Deleted Successfully!!");
        }catch(\Exception $e){
            session()->flash('error',"Something goes wrong!!");
        }
    }
 
}
