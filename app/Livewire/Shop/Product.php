<?php

namespace App\Livewire\Shop;

use App\Livewire\Freelancer\ProductOrder;
use App\Models\Dealer;
use App\Models\Individual;
use App\Models\ProductOrders;
use App\Models\Products;
use App\Models\Salon;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Product extends Component
{
    public $products, $product_id;

    public function mount()
    {
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
        $userIds = array_merge($freelancers, $salons);
        $userIds = array_unique($userIds);
        $this->products = Products::whereIn('freelacer_id', $userIds)->get();
    }

    public function updateStatus($id)
    {
        $product = Products::find($id);
        $currentStatus = $product->status;
        $product->status = $currentStatus ? 0 : 1;
        $product->save();
        Toastr::success('Status Changed', 'Success');
        return redirect()->route('shop.products');
    }

    public function updateInHome($id)
    {
        $product = Products::find($id);
        $currentInHome = $product->in_home;
        $product->in_home = $currentInHome ? 0 : 1;
        $product->save();
        Toastr::success('Top Product Status Changed', 'Success');
        return redirect()->route('shop.products');
    }

    public function destroy() {
        $product = Products::find($this->product_id);
        $orders = ProductOrders::all();
        $cannotDelete = false;
        foreach($orders as $order) {
            $details = json_decode($order->orders, true);
            foreach($details as $detail) {
                if($detail['id'] == $this->product_id) {
                    $cannotDelete = true;
                    break;
                }
            }
        }
        if($cannotDelete) {
            Toastr::error('Cannot Delete Product', "Error");
        } else {
            $product->delete();
            Toastr::success('Product deleted successfully', title: 'Success');
        }
        $this->reset_fields();
        return redirect()->route('shop.products');
    }

    public function reset_fields() {
        $this->product_id = '';
    }

    public function delete($id) {
        $this->product_id = $id;
    }

    public function render()
    {
        return view('livewire.shop.product')->extends('layouts.master');
    }
}
