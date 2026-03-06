<?php

namespace App\Livewire\Facilities;

use App\Models\Facilities;
use App\Models\Offers;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Edit extends Component
{
    public Facilities $facilities;

    #[Rule('required')]
    public $name;

    public function mount()
    {
        $this->name = $this->facilities->name;
    }

    public function submit()
    {
        $this->validate();

        try
        {
            DB::beginTransaction();
            $this->facilities->update([
                'name' => $this->name
            ]);
            DB::commit();

            Toastr::success('Facilities Updated', 'Success');
            return redirect()->route('facilities');
        }
        catch(Exception $e)
        {
            DB::rollBack();
            Toastr::error('Something Went Wrong', 'Failed');
            return redirect()->route('facilities.edit', $this->facilities->id);
        }
    }

    public function render()
    {
        return view('livewire.facilities.edit')->extends('layouts.master');
    }
}
