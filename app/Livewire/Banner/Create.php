<?php

namespace App\Livewire\Banner;

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
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public $user_id, $cities;

    public $categories, $product_categories, $products, $individuals, $partners, $lat, $lng, $start_date, $link;

    #[Rule('required')]
    public $position = '0', $title, $price, $selection, $selection_value, $end_date;

    #[Rule('required')]
    public $image;

    public function mount()
    {
        $this->cities = Cities::where('status', 1)->get();
        $this->categories = Category::get();
        $this->product_categories = ProductCategory::get();
        $this->products = Products::get();
        $this->individuals = User::with('salon')->whereIn('type', ['freelancer', 'individual'])->where('status', 1)->get();
        $this->partners = User::with('salon')->where('type', 'salon')->where('status', 1)->get();
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

        try {
            if ($this->image) {
                $this->image = $this->image->storePublicly('banners', 'spaces');
            }

            if($this->selection == "1")
            {
                $individual = Individual::where('uid',$this->selection_value)->first();
                if($individual)
                {
                    $this->lat = $individual->lat;
                    $this->lng = $individual->lng;
                }
            }
            else if($this->selection == "2")
            {
                $salon = Salon::where('uid',$this->selection_value)->first();
                if($salon)
                {
                    $this->lat = $salon->lat;
                    $this->lng = $salon->lng;
                }
            }

            $this->start_date = Carbon::now()->format('Y-m-d');
            $numberOfDays = $this->end_date;
            $this->end_date = Carbon::now()->addDays($numberOfDays)->format('Y-m-d');

            Banners::create([
                'user_id' => Auth::id(),
                'position' => $this->position,
                'title' => $this->title,
                'price' => $this->price,
                'cover' => $this->image,
                'from' => $this->start_date,
                'to' => $this->end_date,
                'type' => $this->selection,
                'value' => $this->selection_value,
                'lat' => $this->lat,
                'lng' => $this->lng,
                'link' => $this->link
            ]);

            Toastr::success('Banner Created', 'Success');
            return redirect()->route('banners');
        } catch (Exception $e) {
            //dd($e);
            Toastr::error('Something went wrong!', 'Failed');
            return redirect()->route('banner.create');
        }
    }

    public function render()
    {
        return view('livewire.banner.create')->extends('layouts.master');
    }
}
