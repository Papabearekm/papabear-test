<?php

namespace App\Livewire\Service;

use App\Models\Category;
use App\Models\Services;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Support\Facades\File;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public Services $service;

    #[Rule('required')]
    public $name, $category;

    public $categories, $users, $hsn_code;

    public function mount()
    {
        $this->categories = Category::where('status', 1)->get();

        /* $user = User::find($this->service->uid);
        if ($user->type == 'salon') {
            $this->users = User::where('type', 'salon')->get();
            $this->type = 1;
        } elseif ($user->type == 'freelancer') {
            $this->users = User::where('type', 'freelancer')->get();
            $this->type == 2;
        } else {
            $this->type = '';
        }

        $this->partner = $user->id; */

        $this->category = $this->service->cate_id;
        $this->name = $this->service->name;
        $this->hsn_code = $this->service->hsn_code;
        //$this->partner = $this->service->uid;
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
            $this->service->update([
                'cate_id' => $this->category,
                'name' => $this->name,
                'hsn_code' => $this->hsn_code,
                'status' => 1
            ]);

            Toastr::success('Service Updated', 'Success');
            return redirect()->route('services');
        } catch (Exception $e) {
            //dd($e);
            Toastr::error('Something went wrong!', 'Failed');
            return redirect()->route('service.edit', $this->service->id);
        }
    }

    public function render()
    {
        return view('livewire.service.edit')->extends('layouts.master');
    }
}
