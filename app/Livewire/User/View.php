<?php

namespace App\Livewire\User;

use App\Models\User;
use Livewire\Component;

class View extends Component
{
    public $user;

    public function mount($id)
    {
        $this->user = User::find($id);
    }

    public function render()
    {
        return view('livewire.user.view')->extends('layouts.master');
    }
}
