<?php

namespace App\Livewire\Freelancer;

use Livewire\Component;

class FreelancerView extends Component
{
    public function render()
    {
        return view('livewire.freelancer.freelancer-view')->extends('layouts.master');
    }
}
