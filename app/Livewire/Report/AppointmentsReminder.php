<?php

namespace App\Livewire\Report;

use App\Models\AppointmentCompletion;
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

class AppointmentsReminder extends Component
{
    public $items = [], $start_date, $end_date;

    public function mount()
    {
        $this->start_date = request()->query('start_date') ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->end_date = request()->query('end_date') ?? Carbon::now()->endOfMonth()->format('Y-m-d');
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
        $completedAppointments = AppointmentCompletion::whereNotNull('reminder_date')->pluck('appointment_id')->toArray();
        $appointments = Appointments::whereIn('id', $completedAppointments)
        ->where(function($query) use ($salons, $freelancers) {
            $query->where(function($q) use ($salons) {
                $q->where('salon_id', '!=', 0)->whereIn('salon_id', $salons);
            })->orWhere(function($q) use ($freelancers) {
                $q->where('freelancer_id', '!=', 0)->whereIn('freelancer_id', $freelancers);
            });
        })
        ->whereBetween('save_date', [$this->start_date, $this->end_date])->latest('created_at')->get();
        $statuses = ['Created', 'Accepted', 'Declined', 'Ongoing', 'Completed', 'Cancelled By User', 'Refunded', 'Delayed', 'Pending Payment'];
        foreach($appointments as $appointment) {
            $appointmentCompletion = AppointmentCompletion::where('appointment_id', $appointment->id)->latest('id')->first();
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
                'reminder_date' => Carbon::parse($appointmentCompletion->reminder_date)->format('d-m-Y'),
                'status' => isset($statuses[$appointment->status]) ? $statuses[$appointment->status] : 'Unknown',
                'remarks' => $appointmentCompletion->reminder_description ?? ($appointmentCompletion->remarks ?? '')
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
            'E1' => 'Reminder Date',
            'F1' => 'Remarks',
            'G1' => 'Status',
        ];

        foreach($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
            $sheet->getStyle($cell)->applyFromArray($boldStyle);
        }

        $row = 2;

        foreach($this->items as $index => $order) {
            $sheet->setCellValue('A'. $row, $index + 1);
            $sheet->setCellValue('B'. $row, $order['customer']);
            $sheet->setCellValue('C'. $row, $order['partner']);
            $sheet->setCellValue('D'. $row, $order['date']);
            $sheet->setCellValue('E'. $row, $order['reminder_date']);
            $sheet->setCellValue('F'. $row, $order['remarks']);
            $sheet->setCellValue('G'. $row, $order['status']);
            $row++;
        }
        $filename = 'completed-appointments-reminder-report.xlsx';
        $writer = new Xlsx($spreadSheet);
        $path = storage_path($filename);
        $writer->save($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }
    
    public function render()
    {
        return view('livewire.report.appointments-reminder')->extends('layouts.master');
    }
}
