<?php

namespace App\Livewire\Offer;

use App\Models\Dealer;
use App\Models\Individual;
use App\Models\Offers;
use App\Models\Salon;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public $offers, $offer_id, $start_date, $end_date;

    public function mount()
    {
        $user = Auth::user();
        if($user->type == "dealer") {
            $dealer = Dealer::where('uid', $user->id)->first();
            $city = $dealer->city;
            $freelancers = Individual::where('cid', $city)->pluck('uid')->toArray();
            $salons = Salon::where('cid', $city)->pluck('uid')->toArray();
        } else {
            $freelancers = Individual::pluck('uid')->toArray();
            $salons = Salon::pluck('uid')->toArray();
        }
        $userIds = array_merge($freelancers, $salons);
        $userIds = array_unique($userIds);
        $offers = Offers::query();
        $this->start_date = request()->query('start_date') ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->end_date = request()->query('end_date') ?? Carbon::now()->endOfMonth()->format('Y-m-d');
        $offers->whereBetween('expire', [$this->start_date, $this->end_date]);
        $offers->where(function ($q) use ($userIds) {
            foreach ($userIds as $id) {
                $q->orWhereRaw('FIND_IN_SET(?, freelancer_ids)', [$id]);
            }
        });
        $this->offers = $offers->latest()->get();
    }

    public function delete($id)
    {
        $this->offer_id = $id;
    }

    public function destroy()
    {
        $offer = Offers::find($this->offer_id);
        $offer->delete();

        $this->reset_fields();
        
        Toastr::success('Offer Deleted', 'Success');
        return redirect()->route('offers');
    }

    public function reset_fields()
    {
        $this->offer_id = '';
    }

    public function render()
    {
        return view('livewire.offer.index')->extends('layouts.master');
    }
}
