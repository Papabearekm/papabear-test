<?php

namespace App\Livewire\Freelancer;

use App\Models\ProductOrders;
use Livewire\Component;

class ProductOrderView extends Component
{
    public ProductOrders $order;
    public function render()
    {
        return view('livewire.freelancer.product-order-view')->extends('layouts.master');
    }
}
