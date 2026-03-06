<?php

namespace App\Livewire\Freelancer;

use App\Models\Appointments;
use Livewire\Component;

class AppointmentView extends Component
{
    public Appointments $appointment;

    public function render()
    {
        return view('livewire.freelancer.appointment-view')->extends('layouts.master');
    }
}
