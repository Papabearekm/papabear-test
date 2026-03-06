<?php

namespace App\Livewire\Shop;

use App\Models\Dealer;
use App\Models\Individual;
use App\Models\ProductOrders;
use App\Models\Salon;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Order extends Component
{
    public $orders, $start_date, $end_date, $status, $statuses;

    public function mount()
    {
        $this->statuses = ['Created', 'Accepted', 'Rejected', 'Ongoing', 'Completed', 'Cancelled', 'Refunded', 'Delayed', 'Payment is Pending'];
        $this->start_date = request()->query('start_date') ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->end_date = request()->query('end_date') ?? Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->status = request()->query('status') ?? 'all';
        $user = Auth::user();
        if($user->type == "dealer") {
            $dealer = Dealer::where('uid', $user->id)->first();
            $city = $dealer->city;
            $salons = Salon::where('cid', $city)->pluck('uid')->toArray();
            $freelancers = Individual::where('cid', $city)->pluck('uid')->toArray();
        } else {
            $freelancers = Individual::pluck('uid')->toArray();
            $salons = Salon::pluck('uid')->toArray();
        }
        $ordersQuery = ProductOrders::query()
        ->whereBetween('created_at', [
            Carbon::parse($this->start_date)->startOfDay(),
            Carbon::parse($this->end_date)->endOfDay(),
        ])
        ->where(function ($q) use ($freelancers, $salons) {
            // handle empty arrays safely
            if (!empty($freelancers) && !empty($salons)) {
                $q->whereIn('freelancer_id', $freelancers)
                  ->orWhereIn('salon_id', $salons);
            } elseif (!empty($freelancers)) {
                $q->whereIn('freelancer_id', $freelancers);
            } elseif (!empty($salons)) {
                $q->whereIn('salon_id', $salons);
            } else {
                // no matches possible; force empty result
                $q->whereRaw('1=0');
            }
        });
        if($this->status != 'all') {
            $ordersQuery->where('status', $this->status);
        }
        $this->orders = $ordersQuery->latest()->get();
    }

    public function render()
    {
        return view('livewire.shop.order')->extends('layouts.master');
    }
}
