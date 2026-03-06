<?php

namespace App\Livewire\Salon;

use App\Models\RegisterRequest;
use Livewire\Component;

class JoinRequestView extends Component
{
    public RegisterRequest $request;

    public function mount()
    {
        //..
    }

    public function render()
    {
        return view('livewire.salon.join-request-view')->extends('layouts.master');
    }
}
