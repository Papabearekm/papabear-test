<?php

namespace App\Livewire\Service;

use App\Models\Services;
use Brian2694\Toastr\Facades\Toastr;
use Livewire\Component;

class Index extends Component
{
    public $services, $service_id;

    public function mount()
    {
        $this->services = Services::with('category')->where('status', 1)->get();
    }

    public function delete($id)
    {
        $this->service_id = $id;
    }

    public function destroy()
    {
        $service = Services::find($this->service_id);
        $service->update(['status' => 2]);

        $this->reset_fields();
        
        Toastr::success('Service Deleted', 'Success');
        return redirect()->route('services');
    }

    public function reset_fields()
    {
        $this->service_id = '';
    }

    public function render()
    {
        return view('livewire.service.index')->extends('layouts.master');
    }
}
