<?php

namespace App\Livewire\Freelancer;

use App\Models\Dealer;
use App\Models\Individual;
use App\Models\ProductOrders;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ProductOrder extends Component
{
    public $orders, $order_id, $statuses, $start_date, $end_date, $status;

    public function mount()
    {
        $user = Auth::user();
        if($user->type == "dealer") {
            $dealer = Dealer::where('uid', $user->id)->first();
            $city = $dealer->city;
            $freelancers = Individual::where('cid', $city)->pluck('uid')->toArray();
        } else {
            $freelancers = Individual::pluck('uid')->toArray();
        }
        $this->statuses = ['Created', 'Accepted', 'Declined', 'Ongoing', 'Completed', 'Cancelled By User', 'Refunded', 'Delayed', 'Pending Payment'];
        $this->start_date = request()->query('start_date') ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->end_date = request()->query('end_date') ?? Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->status = request()->query('status') ?? 'all';
        $ordersQuery = ProductOrders::query();
        $endDateAdded = Carbon::parse($this->end_date)->addDay()->format('Y-m-d');
        $ordersQuery->where('freelancer_id', '!=', 0)->whereIn('freelancer_id', $freelancers)->whereBetween('created_at', [$this->start_date, $endDateAdded]);
        if($this->status != 'all') {
            $ordersQuery->where('status', $this->status);
        }
        $this->orders =  $ordersQuery->latest()->get();
    }

    public function delete($id)
    {
        $this->order_id = $id;
    }

    public function destroy()
    {
        $order = ProductOrders::find($this->order_id);
        $order->update([
            'status' => 0
        ]);

        $this->reset_fields();

        Toastr::success('Order Deleted', 'Success');
        return redirect()->route('freelancer.orders');
    }

    public function reset_fields()
    {
        $this->order_id = '';
    }
    
    public function render()
    {
        return view('livewire.freelancer.product-order')->extends('layouts.master');
    }
}
