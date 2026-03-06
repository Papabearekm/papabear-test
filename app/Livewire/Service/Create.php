<?php

namespace App\Livewire\Service;

use App\Models\Category;
use App\Models\Services;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    #[Rule('required')]
    public $name, $category;

    public $categories, $users, $hsn_code;

    public function mount()
    {
        $this->categories = Category::where('status', 1)->get();
    }

    /* public function updatedType($id)
    {
        $this->partner = '';

        if ($id == 1) {
            $this->users = User::where('type', 'salon')->get();
        } elseif ($id == 2) {
            $this->users = User::where('type', 'freelancer')->get();
        } else {
            $this->users = '';
        }
    } */

    public function submit()
    {
        $this->validate();

        try {
            Services::create([
                'cate_id' => $this->category,
                'name' => $this->name,
                'hsn_code' => $this->hsn_code,
                'status' => 1
            ]);

            Toastr::success('Service Added', 'Success');
            return redirect()->route('services');
        } catch (Exception $e) {
            //dd($e);
            Toastr::error('Something went wrong!', 'Failed');
            return redirect()->route('service.create');
        }
    }

    public function render()
    {
        return view('livewire.service.create')->extends('layouts.master');
    }
}
