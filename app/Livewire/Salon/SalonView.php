<?php

namespace App\Livewire\Salon;

use Livewire\Component;

class SalonView extends Component
{
    public function render()
    {
        return view('livewire.salon.salon-view')->extends('layouts.master');
    }
}
