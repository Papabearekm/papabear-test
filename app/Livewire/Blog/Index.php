<?php

namespace App\Livewire\Blog;

use App\Models\Blogs;
use Brian2694\Toastr\Facades\Toastr;
use Livewire\Component;

class Index extends Component
{
    public $blogs, $blog_id;

    public function mount()
    {
        $this->blogs = Blogs::get();
    }

    public function delete($id)
    {
        $this->blog_id = $id;
    }

    public function destroy()
    {
        $blog = Blogs::find($this->blog_id);
        $blog->update([
            'status' => 0
        ]);

        $this->reset_fields();
        
        Toastr::success('Blog Deleted', 'Success');
        return redirect()->route('blogs');
    }

    public function reset_fields()
    {
        $this->blog_id = '';
    }

    public function render()
    {
        return view('livewire.blog.index')->extends('layouts.master');
    }
}
