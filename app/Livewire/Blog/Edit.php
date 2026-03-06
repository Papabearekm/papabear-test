<?php

namespace App\Livewire\Blog;

use App\Models\Blogs;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public Blogs $blog;

    #[Rule('required')]
    public $title;

    public $image, $image_preview;

    #[Rule('required')]
    public $short_content;

    #[Rule('required')]
    public $content;

    public function mount()
    {
        $this->title = $this->blog->title;
        $this->image_preview = $this->blog->cover;
        $this->short_content = $this->blog->short_content;
        $this->content = $this->blog->content;
    }

    public function submit()
    {
        $this->validate();

        $relativePath = $this->blog->cover;
        try {
            if ($this->image) {
                if (!empty($relativePath) && Storage::disk('spaces')->exists($relativePath)) {
                    Storage::disk('spaces')->delete($relativePath);
                }
                $relativePath = $this->image->storePublicly('blogs', 'spaces');
            } else {
                $this->image = $this->blog->cover;
            }

            $this->blog->update([
                'title' => $this->title,
                'cover' => $relativePath,
                'short_content' => $this->short_content,
                'content' => $this->content,
                'status' => 1
            ]);

            Toastr::success('Blog Updated', 'Success');
            return redirect()->route('blogs');
        } catch (Exception $e) {
            //dd($e);
            Toastr::error('Something went wrong!', 'Failed');
            return redirect()->route('blog.edit', $this->blog->id);
        }
    }

    public function render()
    {
        return view('livewire.blog.edit')->extends('layouts.master');
    }
}
