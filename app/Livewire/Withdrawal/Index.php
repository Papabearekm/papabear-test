<?php

namespace App\Livewire\Withdrawal;

use App\Models\Cities;
use App\Models\Dealer;
use App\Models\Individual;
use App\Models\Salon;
use App\Models\User;
use App\Models\Withdrawal;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public $withdrawals, $start_date, $end_date, $cities, $city;

    public function mount()
    {
        $user = Auth::user();
        if($user->type == "dealer") {
            $dealer = Dealer::where('uid', $user->id)->first();
            $city = $dealer->city;
            $this->cities = Cities::where('id', $city)->get();
        } else {
            $this->cities = Cities::all();
        }
        $this->city = request()->query('city') ?? ($user->type == "dealer" ? $dealer->city : 'all');
        if($this->city != 'all') {
            $salons = Salon::where('cid', $this->city)->pluck('uid')->toArray();
            $freelancers = Individual::where('cid', $this->city)->pluck('uid')->toArray();
        } else {
            $salons = Salon::pluck('uid')->toArray();
            $freelancers = Individual::pluck('uid')->toArray();
        }
        $userIds = array_merge($freelancers, $salons);
        $userIds = array_unique($userIds);
        $withdrawalsQuery = Withdrawal::query();
        $withdrawalsQuery->whereIn('uid', $userIds);
        $this->start_date = request()->query('start_date') ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->end_date = request()->query('end_date') ?? Carbon::now()->endOfMonth()->format('Y-m-d');
        $withdrawalsQuery->whereBetween('withdrawal_date', [$this->start_date, $this->end_date]);
        $withdrawals = $withdrawalsQuery->latest()->get();
        foreach($withdrawals as $withdrawal)
        {
            $user = User::find($withdrawal->uid);
            $withdrawal->user = $user->first_name . ' ' . @$user->last_name;
            $withdrawal->user_balance = $user->withdrawal_balance;
        }
        $this->withdrawals = $withdrawals;
    }

    public function completePayment($id)
    {
        $withdrawal = Withdrawal::find($id);
        $withdrawal->status = 'Completed';
        $withdrawal->save();
        Toastr::success('Payment Completed', 'Success');
        return redirect()->route('withdrawals');
    }

    // public function destroy()
    // {
    //     $user = User::find($this->user_id);
    //     $user->update([
    //         'status' => 0
    //     ]);

    //     $this->reset_fields();
        
    //     Toastr::success('User Deleted', 'Success');
    //     return redirect()->route('users');
    // }

    // public function reset_fields()
    // {
    //     $this->user_id = '';
    // }

    public function render()
    {
        return view('livewire.withdrawals.index')->extends('layouts.master');
    }
}
