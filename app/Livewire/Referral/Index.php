<?php

namespace App\Livewire\Referral;

use App\Models\Referral;
use Brian2694\Toastr\Facades\Toastr;
use Livewire\Attributes\Rule;
use Livewire\Component;

class Index extends Component
{
    public $referral;

    #[Rule('required')]
    public $title;

    #[Rule('required')]
    public $message;

    #[Rule('required|integer')]
    public $limit;

    #[Rule('required')]
    public $who_received;

    #[Rule('required')]
    public $status = 1;

    #[Rule('required|integer')]
    public $amount;

    public function mount()
    {
        $this->referral = Referral::first();
        if ($this->referral) {
            $this->title = $this->referral->title;
            $this->message = $this->referral->message;
            $this->amount = $this->referral->amount;
            $this->limit = $this->referral->limit;
            $this->who_received = $this->referral->who_received;
            $this->status = $this->referral->status;
        }
    }

    public function submit()
    {
        $this->validate();

        if ($this->referral) {
            $this->referral->update([
                'title' => $this->title,
                'amount' => $this->amount,
                'message' => $this->message,
                'limit' => $this->limit,
                'who_received' => $this->who_received,
                'status' => $this->status
            ]);
        } else {
            Referral::create([
                'title' => $this->title,
                'amount' => $this->amount,
                'message' => $this->message,
                'limit' => $this->limit,
                'who_received' => $this->who_received,
                'status' => $this->status
            ]);
        }

        Toastr::success('Referral Data Updated', 'Success');
        return redirect()->route('referral');
    }

    public function render()
    {
        return view('livewire.referral.index')->extends('layouts.master');
    }
}
