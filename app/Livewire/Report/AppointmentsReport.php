<?php

namespace App\Livewire\Report;

use App\Models\Appointments;
use App\Models\Dealer;
use App\Models\Individual;
use App\Models\Salon;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AppointmentsReport extends Component
{
    public $items = [], $start_date, $end_date, $statuses, $status;

    public function mount()
    {
        $user = Auth::user();
        if($user->type == "dealer") {
            $dealer = Dealer::where('uid', $user->id)->first();
            $city = $dealer->city;
            $salons = Salon::where('cid', $city)->pluck('uid')->toArray();
            $freelancers = Individual::where('cid', $city)->pluck('uid')->toArray();
        } else {
            $salons = Salon::pluck('uid')->toArray();
            $freelancers = Individual::pluck('uid')->toArray();
        }
        $userIds = array_merge($salons, $freelancers);
        $appointmentsQuery = Appointments::query();
        $this->statuses = ['Created', 'Accepted', 'Declined', 'Ongoing', 'Completed', 'Cancelled By User', 'Refunded', 'Delayed', 'Pending Payment'];
        $this->start_date = request()->query('start_date') ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->end_date = request()->query('end_date') ?? Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->status = request()->query('status') ?? 'all';
        $appointmentsQuery->whereBetween('save_date', [$this->start_date, $this->end_date]);
        if($this->status != 'all') {
            $appointmentsQuery->where('status', $this->status);
        }
        $appointmentsQuery->where(function($query) use ($userIds) {
            $query->where(function($q) use ($userIds) {
                $q->where('salon_id', '!=', 0)->whereIn('salon_id', $userIds);
            })->orWhere(function($q) use ($userIds) {
                $q->where('freelancer_id', '!=', 0)->whereIn('freelancer_id', $userIds);
            });
        });
        $appointments = $appointmentsQuery->latest()->get();
        foreach($appointments as $appointment) {
            $customer = User::find($appointment->uid);
            if($appointment->freelancer_id == 0) {
                $partner = Salon::where('uid', $appointment->salon_id)->first();
                $partnerName = $partner ? $partner->name : '-'; 
            } else {
                $partner = User::find($appointment->freelancer_id);
                $partnerName = $partner ? ($partner->first_name . ' ' . $partner->last_name) : '-'; 
            }
            $this->items[] = [
                'id' => $appointment->id,
                'customer' => $customer ? ($customer->first_name . ' ' . $customer->last_name) : '',
                'partner' => $partnerName,
                'date' => Carbon::parse($appointment->save_date)->format('d-m-Y'),
                'status' => isset($this->statuses[$appointment->status]) ? $this->statuses[$appointment->status] : 'Unknown',
                'payment_status' => $appointment->pay_method == 5 ? 'Online Payment' : 'COD',
                'amount' => $appointment->grand_total
            ];
        }
    }

    public function export()
    {
        $spreadSheet = new Spreadsheet;

        $sheet = $spreadSheet->getActiveSheet();

        $boldStyle = [
            'font' => [
                'bold' => true
            ]
        ];

        $headers = [
            'A1' => 'No',
            'B1' => 'Customer',
            'C1' => 'Partner',
            'D1' => 'Appointment Date',
            'E1' => 'Status',
            'F1' => 'Payment Method',
            'G1' => 'Total Amount',
        ];

        foreach($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
            $sheet->getStyle($cell)->applyFromArray($boldStyle);
        }

        $row = 2;

        foreach($this->items as $index => $appointment) {
            $sheet->setCellValue('A'. $row, $index + 1);
            $sheet->setCellValue('B'. $row, $appointment['customer']);
            $sheet->setCellValue('C'. $row, $appointment['partner']);
            $sheet->setCellValue('D'. $row, $appointment['date']);
            $sheet->setCellValue('E'. $row, $appointment['status']);
            $sheet->setCellValue('F'. $row, $appointment['payment_status']);
            $sheet->setCellValue('G'. $row, $appointment['amount']);
            $row++;
        }
        $filename = 'appointments-report.xlsx';
        $writer = new Xlsx($spreadSheet);
        $path = storage_path($filename);
        $writer->save($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }

    public function render()
    {
        return view('livewire.report.appointments-report')->extends('layouts.master');
    }
}
