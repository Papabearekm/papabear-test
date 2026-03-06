<?php

namespace App\Livewire\Shop;

use App\Models\ProductCategory;
use App\Models\ProductSubCategory;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class SubCategory extends Component
{
    use WithFileUploads;

    public $sub_categories, $categories, $image_preview, $sub_category_id = '';

    #[Rule('required')]
    public $name, $category;

    #[Rule('nullable|image|max:1024')]
    public $image;

    public function mount()
    {
        $this->categories = ProductCategory::where('status', 1)->get();
        $this->sub_categories = ProductSubCategory::where('status', 1)->get();
    }

    public function submit()
    {
        $this->validate();

        if ($this->sub_category_id) {
            $sub_category = ProductSubCategory::find($this->sub_category_id);
            $relativePath = $sub_category->cover;
            if ($this->image) {
                if (!empty($relativePath) && Storage::disk('spaces')->exists($relativePath)) {
                    Storage::disk('spaces')->delete($relativePath);
                }
                $relativePath = $this->image->storePublicly('shop/subcategory', 'spaces');
            }

            $sub_category->update([
                'name' => $this->name,
                'cate_id' => $this->category,
                'cover' => $relativePath,
                'status' => 1
            ]);

            $message = 'Sub Category Updated!';
        } else {
            if ($this->image) {
                $relativePath = $this->image->storePublicly('shop/subcategory', 'spaces');
            } else {
                Toastr::error('Image is required', 'Failed');
                return redirect()->route('shop.subcategories');
            }

            ProductSubCategory::create([
                'name' => $this->name,
                'cate_id' => $this->category,
                'cover' => $relativePath,
                'status' => 1
            ]);

            $message = 'Sub Category Added!';
        }

        $this->reset_fields();

        Toastr::success($message, 'Success');
        return redirect()->route('shop.subcategories');
    }

    public function edit($id)
    {
        $sub_category = ProductSubCategory::find($id);
        $this->sub_category_id = $id;
        $this->name = $sub_category->name;
        $this->category = $sub_category->cate_id;
        $this->image_preview = $sub_category->cover;
    }

    public function delete($id)
    {
        $this->sub_category_id = $id;
    }

    public function destroy()
    {
        $sub_category = ProductSubCategory::find($this->sub_category_id);
        $sub_category->update([
            'status' => 0
        ]);

        $this->reset_fields();
        
        Toastr::success('Sub Category Deleted', 'Success');
        return redirect()->route('shop.subcategories');
    }

    public function reset_fields()
    {
        $this->sub_category_id = '';
        $this->name = '';
        $this->category = '';
        $this->image_preview = '';
        $this->image = '';
    }

    public function render()
    {
        return view('livewire.shop.sub-category')->extends('layouts.master');
    }
}
