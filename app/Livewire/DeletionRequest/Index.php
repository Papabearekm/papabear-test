<?php

namespace App\Livewire\DeletionRequest;

use App\Models\AccountDeletion;
use Brian2694\Toastr\Facades\Toastr;
use Livewire\Component;

class Index extends Component
{
    public $requests;

    public function mount() {
        $this->requests = AccountDeletion::orderBy('created_at', 'desc')->get();
    }

    public function completeDeletion($id)
    {
        $deletionRequest = AccountDeletion::find($id);
        $deletionRequest->status = 'Completed';
        $deletionRequest->save();
        $user = $deletionRequest->user;
        if ($user) {
            $user->mobile = $user->mobile . '_deleted_' . time();
            $user->email = $user->email . '_deleted_' . time();
            $user->status = 0; // Deactivate the user
            $user->save();
        }
        Toastr::success('Account Deleted', 'Success');
        return redirect()->route('deletion-requests');
    }

    public function render()
    {
        return view('livewire.deletion-requests.index')->extends('layouts.master');
    }
}
