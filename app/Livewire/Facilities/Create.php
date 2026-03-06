<?php

namespace App\Livewire\Facilities;

use App\Models\Facilities;
use App\Models\Offers;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Create extends Component
{
    public $users, $selectedFreelancers = [];

    #[Rule('required')]
    public $name;

    public function mount()
    {

    }

    public function submit()
    {
        $this->validate();

        try
        {
            Facilities::create([
                'name' => $this->name,
            ]);
    
            Toastr::success("Facility Created", 'Success');
            return redirect()->route('facilities');
        }
        catch(Exception $e)
        {   
            Toastr::error('Something went wrong!', 'Failed');
            return redirect()->route('facilities.create');
        }
    }

    public function render()
    {
        return view('livewire.facilities.create')->extends('layouts.master');
    }
}
