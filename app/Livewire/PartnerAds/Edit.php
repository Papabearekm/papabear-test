<?php

namespace App\Livewire\PartnerAds;

use App\Models\AdsPlan;
use App\Models\Banners;
use App\Models\Category;
use App\Models\Cities;
use App\Models\PartnerAds;
use App\Models\ProductCategory;
use App\Models\Products;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
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

    public PartnerAds $banner;

    public $categories, $product_categories, $products, $individuals, $partners;

    public $user_id, $image_preview;

    public $image;

    #[Rule('required')]
    public $title, $link;


    public function mount()
    {
        $this->categories = Category::get();
        $this->product_categories = ProductCategory::get();
        $this->products = Products::get();
        $this->individuals = User::with('salon')->where('type', 'freelancer')->where('status', 1)->get();
        $this->partners = User::with('salon')->where('type', 'salon')->where('status', 1)->get();

        $this->image_preview = $this->banner->cover;
        $this->title = $this->banner->title;
        $this->link = $this->banner->link;
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
                $relativePath = $this->image->storePublicly('partner-ads', 'spaces');
            }

            $this->banner->update([
                'user_id' => Auth::id(),
                'title' => $this->title,
                'link' => $this->link,
                'cover' => $relativePath,
            ]);

            Toastr::success('Banner Updated', 'Success');
            return redirect()->route('partner-ads');
        } catch (Exception $e) {
            //dd($e);
            Toastr::error('Something went wrong!', 'Failed');
            return redirect()->route('partner-ads.edit', $this->banner->id);
        }
    }

    public function render()
    {
        return view('livewire.partner_ads.edit')->extends('layouts.master');
    }
}
