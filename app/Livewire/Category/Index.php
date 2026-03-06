<?php

namespace App\Livewire\Category;

use App\Models\Category;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\File;
use Livewire\Component;
use Livewire\Attributes\Rule;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithFileUploads;

    public $categories, $image_preview, $category_id = '';

    #[Rule('required')]
    public $name;

    #[Rule('nullable|image|max:1024')]
    public $image;

    public function mount()
    {
        $this->categories = Category::where('status', 1)->get();
    }

    public function submit()
    {
        $this->validate();

        if ($this->category_id) {
            $category = Category::find($this->category_id);
            $relativePath = $category->cover;
            if ($this->image) {
                if(!empty($relativePath) && File::exists(public_path($relativePath))) {
                    File::delete(public_path($relativePath));
                }
                $relativePath = $this->image->storePublicly('category', 'spaces');
            }

            $category->update([
                'name' => $this->name,
                'cover' => $relativePath
            ]);

            $message = 'Category Updated!';
        } else {
            if ($this->image) {
                $this->image = $this->image->storePublicly('category', 'spaces');
            }

            Category::create([
                'name' => $this->name,
                'cover' => $this->image
            ]);

            $message = 'Category Added!';
        }

        $this->reset_fields();

        Toastr::success($message, 'Success');
        return redirect()->route('categories');
    }

    public function edit($id)
    {
        $category = Category::find($id);
        $this->category_id = $id;
        $this->name = $category->name;
        $this->image_preview = $category->cover;
    }

    public function delete($id)
    {
        $this->category_id = $id;
    }

    public function destroy()
    {
        $category = Category::find($this->category_id);
        $category->update([
            'status' => 0
        ]);

        $this->reset_fields();
        
        Toastr::success('Category Deleted', 'Success');
        return redirect()->route('categories');
    }

    public function reset_fields()
    {
        $this->category_id = '';
        $this->name = '';
        $this->image_preview = '';
        $this->image = '';
    }

    public function render()
    {
        return view('livewire.category.index')->extends('layouts.master');
    }
}
