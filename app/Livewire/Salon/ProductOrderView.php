<?php

namespace App\Livewire\Salon;

use App\Models\ProductOrders;
use Livewire\Component;

class ProductOrderView extends Component
{
    public ProductOrders $order;
    public function render()
    {
        return view('livewire.salon.product-order-view')->extends('layouts.master');
    }
}
