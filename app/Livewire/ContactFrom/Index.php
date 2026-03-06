<?php

namespace App\Livewire\ContactFrom;

use App\Models\Contacts;
use Brian2694\Toastr\Facades\Toastr;
use Livewire\Component;

class Index extends Component
{
    public $contacts, $contact_id = '';

    public function mount()
    {
        $this->contacts = Contacts::orderBy('created_at', 'desc')->get();
    }

    public function delete($id)
    {
        $this->contact_id = $id;
    }

    public function destroy()
    {
        $contact = Contacts::find($this->contact_id);
        $contact->delete();

        $this->reset_fields();

        Toastr::success('Contact Deleted', 'Success');
        return redirect()->route('contactform');
    }

    public function reset_fields()
    {
        $this->contact_id = '';
    }

    public function render()
    {
        return view('livewire.contact-from.index')->extends('layouts.master');
    }
}
