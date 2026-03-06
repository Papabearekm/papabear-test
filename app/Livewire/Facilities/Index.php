<?php

namespace App\Livewire\Facilities;

use App\Models\Facilities;
use App\Models\Salon;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Livewire\Component;

class Index extends Component
{
    public $facilities, $facilities_id;

    public function mount()
    {
        $this->facilities = Facilities::get();
    }

    public function delete($id)
    {
        $this->facilities_id = $id;
    }

    public function destroy()
    {
        $facility = Facilities::find($this->facilities_id);
        $salons = Salon::whereNotNull('facilities')->get();
        foreach($salons as $salon) {
            $facilities = explode(',', $salon->facilities);
            if(in_array($this->facilities_id, $facilities)) {
                Toastr::error('Facility is assigned to a salon. Remove it from the salon first.', 'Error');
                return redirect()->route('facilities');
            }
        }
        $facility->delete();

        $this->reset_fields();

        Toastr::success('Facility Deleted', 'Success');
        return redirect()->route('facilities');
    }

    public function reset_fields()
    {
        $this->facilities_id = '';
    }

    /* public function status($id)
    {
        dd($id);
        $this->id = $id;
    }

    public function status_change()
    {
        dd($this->id);
        $salon = Salon::find($this->id);
        dd($salon);
        $this->status = $salon->status;

        if ($this->status == 1) {
            $salon->update([
                'status' => 0
            ]);
        } else {
            $salon->update([
                'status' => 1
            ]);
        }

        $this->reset_fields();

        Toastr::success('Status Updated', 'Success');
        return redirect()->route('salons');
    } */

    public function render()
    {
        return view('livewire.facilities.index')->extends('layouts.master');
    }
}
