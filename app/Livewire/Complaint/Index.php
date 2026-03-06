<?php

namespace App\Livewire\Complaint;

use App\Models\Complaints;
use Brian2694\Toastr\Facades\Toastr;
use Livewire\Component;

class Index extends Component
{
    public $complaints, $complaint_id;

    public function mount()
    {
        $this->complaints = Complaints::orderBy('created_at', 'desc')->get();
        $statues = ['Pending', 'Resolved', 'Rejected'];
        $productIssues = [
            '1' => 'With Order',
            '2' => 'With Business / Freelancer',
            '4' => 'With Product',
        ];
        $appointmentIssues = [
            '6' => 'With Service',
            '2' => 'With Business / Freelancer',
            '9' => 'With Packages',
        ];
        $productReasons = [
            'The product arrived too late',
            'The product did not match the description',
            'The Purchase was fraudulent',
            'The product was damaged or defective',
            'The merchant shipped the wrong item',
            'Wrong Item Size or Wrong Product Sent',
            'Driver arrived too late',
            'Driver behaviour',
            'Employee/Freelancer behaviour',
            'Issue with payment Amount',
            'Others'
        ];
        $appointmentReasons = [
            'Employee/Freelancer Arrived too late',
            'Employee/Freelancer did not match the description',
            'Employee/Freelancer was fraudulent',
            'Related to Service',
            'Satisfaction of Service',
            'Employee/Freelancer behaviour',
            'Issue with payment Amount',
            'Others'
        ];
        foreach($this->complaints as $complaint) {
            $complaint->user = $complaint->user()->first();
            if($complaint->complaints_on == 1) {
                $complaint->issue = $productIssues[$complaint->issue_with] ?? '-';
                $complaint->reason = $productReasons[$complaint->reason_id] ? ($complaint->reason_id == 10 ? $complaint->short_message : $productReasons[$complaint->reason_id]) : '-';
            } else {
                $complaint->issue = $appointmentIssues[$complaint->issue_with] ?? '-';
                $complaint->reason = $appointmentReasons[$complaint->reason_id] ? ($complaint->reason_id == 7 ? $complaint->short_message : $appointmentReasons[$complaint->reason_id]) : '-';
            }
            $complaint->statusMessage = $statues[$complaint->status] ?? 'Unknown';
        }
    }

    public function delete($id) {
        $this->complaint_id = $id;
    }

    public function destroy()
    {
        $complaint = Complaints::find($this->complaint_id);
        if($complaint) {
            if($complaint->status == 0) {
                Toastr::error('Cannot delete pending complaint', 'Error');
                return redirect()->route('complaint');
            } else {
                $complaint->delete();
                Toastr::success('Complaint Deleted', 'Success');
                return redirect()->route('complaint');
            }
        } else {
            Toastr::error('Complaint not found', 'Error');
        }
        $this->reset_fields();
    }

    public function reset_fields()
    {
        $this->complaint_id = '';
    }

    public function render()
    {
        return view('livewire.complaint.index')->extends('layouts.master');
    }
}
