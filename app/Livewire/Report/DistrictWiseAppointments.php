<?php

namespace App\Livewire\Report;

use App\Models\Appointments;
use App\Models\Cities;
use App\Models\Dealer;
use App\Models\Individual;
use App\Models\Salon;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DistrictWiseAppointments extends Component
{
    public $items = [], $statuses, $start_date, $end_date, $status, $cities, $city;

    public function mount()
    {
        $user = Auth::user();
        if($user->type == "dealer") {
            $dealer = Dealer::where('uid', $user->id)->first();
        }
        $appointmentsQuery = Appointments::query();
        $this->statuses = ['Created', 'Accepted', 'Declined', 'Ongoing', 'Completed', 'Cancelled By User', 'Refunded', 'Delayed', 'Pending Payment'];
        $this->start_date = request()->query('start_date') ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->end_date = request()->query('end_date') ?? Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->status = request()->query('status') ?? 'all';
        $this->city = request()->query('city') ?? ($user->type == "dealer" ? $dealer->city : 'all');
        $this->cities = $user->type == "dealer" ? Cities::where('id', $dealer->city)->orderBy('name')->get() : Cities::orderBy('name')->get();
        if($this->city != 'all') {
            $salons = Salon::where('cid', $this->city)->pluck('uid')->toArray();
            $freelancers = Individual::where('cid', $this->city)->pluck('uid')->toArray();
        } else {
            $salons = Salon::pluck('uid')->toArray();
            $freelancers = Individual::pluck('uid')->toArray();
        }
        $appointmentsQuery->whereBetween('save_date', [$this->start_date, $this->end_date]);
        if($this->status != 'all') {
            $appointmentsQuery->where('status', $this->status);
        }
        $appointmentsQuery->where(function($query) use ($salons, $freelancers) {
            $query->where(function($q) use ($salons) {
                $q->where('salon_id', '!=', 0)->whereIn('salon_id', $salons);
            })->orWhere(function($q) use ($freelancers) {
                $q->where('freelancer_id', '!=', 0)->whereIn('freelancer_id', $freelancers);
            });
        });
        $appointments = $appointmentsQuery->latest()->get();
        foreach($appointments as $appointment) {
            $customer = User::find($appointment->uid);
            if($appointment->freelancer_id == 0) {
                $partner = Salon::where('uid', $appointment->salon_id)->first();
                $partnerCity = $partner ? Cities::find($partner->cid)->name : '';
                $partnerName = $partner ? $partner->name : '-'; 
            } else {
                $partner = User::find($appointment->freelancer_id);
                $freelancer = Individual::where('uid', $appointment->freelancer_id)->first();
                $partnerCity = $freelancer ? Cities::find($freelancer->cid)->name : '';
                $partnerName = $partner ? ($partner->first_name . ' ' . $partner->last_name) : '-'; 
            }
            $this->items[] = [
                'id' => $appointment->id,
                'customer' => $customer ? ($customer->first_name . ' ' . $customer->last_name) : '',
                'partner' => $partnerName,
                'city' => $partnerCity,
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
            'B1' => 'City',
            'C1' => 'Customer',
            'D1' => 'Partner',
            'E1' => 'Appointment Date',
            'F1' => 'Status',
            'G1' => 'Payment Method',
            'H1' => 'Total Amount',
        ];

        foreach($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
            $sheet->getStyle($cell)->applyFromArray($boldStyle);
        }

        $row = 2;

        foreach($this->items as $index => $appointment) {
            $sheet->setCellValue('A'. $row, $index + 1);
            $sheet->setCellValue('B'. $row, $appointment['city']);
            $sheet->setCellValue('C'. $row, $appointment['customer']);
            $sheet->setCellValue('D'. $row, $appointment['partner']);
            $sheet->setCellValue('E'. $row, $appointment['date']);
            $sheet->setCellValue('F'. $row, $appointment['status']);
            $sheet->setCellValue('G'. $row, $appointment['payment_status']);
            $sheet->setCellValue('H'. $row, $appointment['amount']);
            $row++;
        }
        $filename = 'city-wise-appointments-report.xlsx';
        $writer = new Xlsx($spreadSheet);
        $path = storage_path($filename);
        $writer->save($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }
    
    public function render()
    {
        return view('livewire.report.district-wise-appointments')->extends('layouts.master');
    }
}
