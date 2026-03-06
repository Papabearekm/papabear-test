<?php

namespace App\Livewire\Blog;

use App\Models\Blogs;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    #[Rule('required')]
    public $title;

    #[Rule('required')]
    public $image;

    #[Rule('required')]
    public $short_content;

    #[Rule('required')]
    public $content;

    public function mount()
    {
        //..
    }

    public function submit()
    {
        $this->validate();

        try {
            if ($this->image) {
                $this->image = $this->image->storePublicly('blogs', 'spaces');
            }

            Blogs::create([
                'title' => $this->title,
                'cover' => $this->image,
                'short_content' => $this->short_content,
                'content' => $this->content,
                'status' => 1
            ]);

            Toastr::success('Blog Created', 'Success');
            return redirect()->route('blogs');
        } catch (Exception $e) {
            //dd($e);
            Toastr::error('Something went wrong!', 'Failed');
            return redirect()->route('blog.create');
        }
    }

    public function render()
    {
        return view('livewire.blog.create')->extends('layouts.master');
    }
}
