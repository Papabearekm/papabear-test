<?php

namespace App\Livewire\Offer;

use App\Models\Dealer;
use App\Models\Individual;
use App\Models\Offers;
use App\Models\Salon;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Edit extends Component
{
    public Offers $offer;
    public $users, $selectedFreelancers;

    #[Rule('required')]
    public $code, $name, $short_descriptions, $discount, $upto, $type, $max_usage, $min_cart, $expiry_date;

    public function mount()
    {
        $user = Auth::user();
        if($user->type == "dealer") {
            $dealer = Dealer::where('uid', $user->id)->first();
            $city = $dealer->city;
            $salons = Salon::where('cid', $city)->pluck('uid')->toArray();
            $freelancers = Individual::where('cid', $city)->pluck('uid')->toArray();
        } else {
            $salons = Salon::pluck('uid')->toArray();
            $freelancers = Individual::pluck('uid')->toArray();
        }
        $userIds = array_merge($salons, $freelancers);
        $userIds = array_unique($userIds);
        $this->users = User::whereIn('type', ['salon', 'freelancer', 'individual'])->whereIn('id', $userIds)->where('status', 1)->get();
        foreach($this->users as $user) {
            if($user->type == 'salon') {
                $salon = Salon::where('uid', $user->id)->first();
                if($salon) {
                    $user->first_name = $salon->name;
                    $user->last_name = '';
                }
            }
        }
        $this->code = $this->offer->code;
        $this->name = $this->offer->name;
        $this->short_descriptions = $this->offer->short_descriptions;
        $this->discount = $this->offer->discount;
        $this->upto = $this->offer->upto;
        $this->type = $this->offer ? $this->offer->type : 1;
        $this->max_usage = $this->offer->max_usage;
        $this->min_cart = $this->offer->min_cart_value;
        $this->expiry_date = $this->offer->expire;
        $this->selectedFreelancers = explode(',', $this->offer->freelancer_ids);
    }

    public function submit()
    {
        $this->validate();

        try
        {
            $this->offer->update([
                'name' => $this->name,
                'short_descriptions' => $this->short_descriptions,
                'code' => $this->code,
                'type' => $this->type,
                'for' => '0',
                'discount' => $this->discount,
                'upto' => $this->upto,
                'expire' => $this->expiry_date,
                'freelancer_ids' => implode(",",$this->selectedFreelancers),
                'max_usage' => $this->max_usage,
                'min_cart_value' => $this->min_cart,
                'validations' => '0',
                'status' => '1'
            ]);
    
            Toastr::success("Offer Updated", 'Success');
            return redirect()->route('offers');
        }
        catch(Exception $e)
        {   
            Toastr::error('Something went wrong!', 'Failed');
            Log::error($e->getMessage());
            return redirect()->route('offer.edit', $this->offer->id);
        }
    }

    public function render()
    {
        return view('livewire.offer.edit')->extends('layouts.master');
    }
}
