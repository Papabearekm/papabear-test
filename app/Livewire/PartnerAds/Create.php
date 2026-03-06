<?php

namespace App\Livewire\PartnerAds;

use App\Models\Category;
use App\Models\Cities;
use App\Models\PartnerAds;
use App\Models\ProductCategory;
use App\Models\Products;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public $user_id;

    public $categories, $product_categories, $products, $individuals, $partners;

    #[Rule('required')]
    public $title, $link;

    #[Rule('required')]
    public $image;

    public function mount()
    {
        $this->categories = Category::get();
        $this->product_categories = ProductCategory::get();
        $this->products = Products::get();
        $this->individuals = User::with('salon')->where('type', 'freelancer')->where('status', 1)->get();
        $this->partners = User::with('salon')->where('type', 'salon')->where('status', 1)->get();
    }

    public function submit()
    {
        $this->validate();

        try {
            if ($this->image) {
                $this->image = $this->image->storePublicly('partner-ads', 'spaces');
            }

            PartnerAds::create([
                'user_id' => Auth::id(),
                'title' => $this->title,
                'link' => $this->link,
                'cover' => $this->image,
            ]);

            Toastr::success('Banner Created', 'Success');
            return redirect()->route('partner-ads');
        } catch (Exception $e) {
            //dd($e);
            Toastr::error('Something went wrong!', 'Failed');
            return redirect()->route('partner-ads.create');
        }
    }

    public function render()
    {
        return view('livewire.partner_ads.create')->extends('layouts.master');
    }
}
