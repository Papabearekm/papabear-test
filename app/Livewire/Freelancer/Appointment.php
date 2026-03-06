<?php

namespace App\Livewire\Freelancer;

use App\Models\Appointments;
use App\Models\Dealer;
use App\Models\Individual;
use App\Models\Salon;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Appointment extends Component
{
    public $appointments, $appointment_id, $statuses, $start_date, $end_date, $status;

    public function mount()
    {
        $user = Auth::user();
        if($user->type == "dealer") {
            $dealer = Dealer::where('uid', $user->id)->first();
            $city = $dealer->city;
            $freelancers = Individual::where('cid', $city)->pluck('uid')->toArray();
        } else {
            $freelancers = Individual::pluck('uid')->toArray();
        }
        $this->statuses = ['Created', 'Accepted', 'Declined', 'Ongoing', 'Completed', 'Cancelled By User', 'Refunded', 'Delayed', 'Pending Payment'];
        $this->start_date = request()->query('start_date') ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->end_date = request()->query('end_date') ?? Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->status = request()->query('status') ?? 'all';
        $appointmentsQuery = Appointments::query();
        $appointmentsQuery->where('freelancer_id', '!=', 0)->whereIn('freelancer_id', $freelancers)->whereBetween('save_date', [$this->start_date, $this->end_date]);
        if($this->status != 'all') {
            $appointmentsQuery->where('status', $this->status);
        }
        $this->appointments = $appointmentsQuery->latest()->get();
    }

    public function delete($id)
    {
        $this->appointment_id = $id;
    }

    public function destroy()
    {
        $appointment = Appointments::find($this->appointment_id);
        $appointment->update([
            'status' => 0
        ]);

        $this->reset_fields();

        Toastr::success('Appointment Deleted', 'Success');
        return redirect()->route('freelancer.appointments');
    }

    public function reset_fields()
    {
        $this->appointment_id = '';
    }

    public function render()
    {
        return view('livewire.freelancer.appointment')->extends('layouts.master');
    }
}
