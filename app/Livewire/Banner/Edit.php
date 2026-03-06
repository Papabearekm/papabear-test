<?php

namespace App\Livewire\Banner;

use App\Models\AdsPlan;
use App\Models\Banners;
use App\Models\Category;
use App\Models\Cities;
use App\Models\Individual;
use App\Models\ProductCategory;
use App\Models\Products;
use App\Models\Salon;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public Banners $banner;

    public $categories, $product_categories, $products, $individuals, $partners, $start_date, $link;

    public $user_id, $cities, $image_preview;

    public $image;

    #[Rule('required')]
    public $end_date, $position, $title, $price, $selection, $selection_value;


    public function mount()
    {
        $this->cities = Cities::where('status', 1)->get();
        $this->categories = Category::get();
        $this->product_categories = ProductCategory::get();
        $this->products = Products::get();
        $this->individuals = User::with('salon')->where('type', 'freelancer')->where('status', 1)->get();
        $this->partners = User::with('salon')->where('type', 'salon')->where('status', 1)->get();

        $this->image_preview = $this->banner->cover;
        $this->start_date = $this->banner->from;
        $this->end_date = Carbon::parse($this->banner->to)->diffInDays($this->start_date);
        $this->title = $this->banner->title;
        $this->position = $this->banner->position;
        $this->price = $this->banner->price;
        $this->selection = $this->banner->type;
        $this->selection_value = $this->banner->value;
        $this->link = $this->banner->link;
    }

    public function reset_type()
    {
        $this->selection = '';
        $this->selection_value = '';
    }

    public function updatedSelection($id)
    {
        $this->selection = $id;
        $this->selection_value = '';
    }

    public function submit()
    {
        $this->validate();

        $relativePath = $this->banner->cover;
        try {
            if ($this->image) {
                if (!empty($relativePath) && Storage::disk('spaces')->exists($relativePath)) {
                    Storage::disk('spaces')->delete($relativePath);
                }
                $relativePath = $this->image->storePublicly('banners', 'spaces');
            }

            $numberOfDays = $this->end_date;
            $this->end_date = Carbon::now()->addDays($numberOfDays)->format('Y-m-d');

            $this->banner->update([
                'user_id' => Auth::id(),
                'position' => $this->position,
                'title' => $this->title,
                'cover' => $relativePath,
                'link' => $this->link
            ]);

            Toastr::success('Banner Updated', 'Success');
            return redirect()->route('banners');
        } catch (Exception $e) {
            //dd($e);
            Toastr::error('Something went wrong!', 'Failed');
            return redirect()->route('banner.edit', $this->banner->id);
        }
    }

    public function render()
    {
        return view('livewire.banner.edit')->extends('layouts.master');
    }
}
