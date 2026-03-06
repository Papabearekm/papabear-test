<?php

namespace App\Livewire\Salon;

use App\Models\Appointments;
use Livewire\Component;

class AppointmentView extends Component
{
    public Appointments $appointment;
    
    public function render()
    {
        return view('livewire.salon.appointment-view')->extends('layouts.master');
    }
}
