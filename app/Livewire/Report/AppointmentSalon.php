<?php

namespace App\Livewire\Report;

use App\Models\Appointments;
use App\Models\Dealer;
use App\Models\Salon;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AppointmentSalon extends Component
{
    public $salons, $start_date, $end_date;

    public function mount()
    {
        $user = Auth::user();
        if($user->type == "dealer") {
            $dealer = Dealer::where('uid', $user->id)->first();
            $city = $dealer->city;
            $this->salons = Salon::where('cid', $city)->pluck('uid')->toArray();
        } else {
            $this->salons = Salon::pluck('uid')->toArray();
        }
        $this->start_date = request()->query('start_date') ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->end_date = request()->query('end_date') ?? Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->salons = Salon::whereIn('uid', function ($query) {
            $query->select('id')->where('type', 'salon')->whereIn('id', $this->salons)->from('users');
        })->get();
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
            'B1' => 'Partner',
            'C1' => 'Premium Member',
            'D1' => 'Number of Appointments',
            'E1' => 'Total Amount'
        ];

        foreach($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
            $sheet->getStyle($cell)->applyFromArray($boldStyle);
        }

        $row = 2;

        foreach($this->salons as $index => $salon) {
            $sheet->setCellValue('A'. $row, $index + 1);
            $sheet->setCellValue('B'. $row, $salon->name);
            $sheet->setCellValue('C'. $row, $salon->upgrade == 0 ? 'No' : 'Yes');
            $sheet->setCellValue('D'. $row, Appointments::join('salon', 'salon.uid', '=', 'appointments.salon_id')
                                            ->join('users', 'users.id', '=', 'salon.uid')
                                            ->whereBetween('appointments.save_date', [$this->start_date, $this->end_date])
                                            ->where('users.type', 'salon')->where('appointments.salon_id', $salon->uid)
                                            ->count());
            $sheet->setCellValue('E'. $row, Appointments::join('salon', 'salon.uid', '=', 'appointments.salon_id')
                                            ->join('users', 'users.id', '=', 'salon.uid')
                                            ->whereBetween('appointments.save_date', [$this->start_date, $this->end_date])
                                            ->where('users.type', 'salon')->where('appointments.salon_id', $salon->uid)
                                            ->sum('grand_total'));
            $row++;
        }
        $filename = 'partner-appointments-report.xlsx';
        $writer = new Xlsx($spreadSheet);
        $path = storage_path($filename);
        $writer->save($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }

    public function render()
    {
        return view('livewire.report.appointment-salon')->extends('layouts.master');
    }
}
