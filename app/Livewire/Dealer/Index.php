<?php

namespace App\Livewire\Dealer;

use App\Models\Dealer;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Index extends Component
{
    public $dealers, $dealer_id;

    public function mount()
    {
        $this->dealers = Dealer::with('user')->get();
    }

    public function delete($id)
    {
        $this->dealer_id = $id;
    }

    public function destroy()
    {
        $dealer = Dealer::find($this->dealer_id);
        if ($dealer) {
            try {
                DB::beginTransaction();
                $user = $dealer->user;
                $user->delete();
                $dealer->delete();
                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
                Toastr::error('Error deleting dealer: ' . $e->getMessage(), 'Error');
                return;
            }
            Toastr::success('Dealer Deleted', 'Success');
        } else {
            Toastr::error('Dealer not found.', 'Error');
        }

        $this->reset_fields();
    }

    public function updateStatus($id)
    {
        $dealer = Dealer::find($id);
        $currentStatus = $dealer->status;
        $dealer->status = $currentStatus ? 0 : 1;
        $dealer->save();
        $user = User::find($dealer->uid);
        $userStatus = $user->status;
        $user->status = $userStatus ? 0 : 1;
        $user->save();
        Toastr::success('Status Changed', 'Success');
        return redirect()->route('dealers');
    }

    public function reset_fields()
    {
        $this->dealer_id = '';
    }

    public function render()
    {
        return view('livewire.dealer.index')->extends('layouts.master');
    }
}
