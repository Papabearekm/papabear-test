<?php

namespace App\Livewire\Report;

use App\Models\Appointments;
use App\Models\Dealer;
use App\Models\Individual;
use App\Models\Salon;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AppointmentFreelancer extends Component
{
    public $salons, $start_date, $end_date;

    public function mount()
    {
        $user = Auth::user();
        if($user->type == "dealer") {
            $dealer = Dealer::where('uid', $user->id)->first();
            $city = $dealer->city;
            $this->salons = Individual::where('cid', $city)->pluck('uid')->toArray();
        } else {
            $this->salons = Individual::pluck('uid')->toArray();
        }
        $this->start_date = request()->query('start_date') ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->end_date = request()->query('end_date') ?? Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->salons = Individual::whereIn('uid', function ($query) {
            $query->select('id')->whereIn('type', ['freelancer', 'individual'])->whereIn('id', $this->salons)->from('users');
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
            'B1' => 'Freelancer',
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
            $sheet->setCellValue('B'. $row, $salon->user? ($salon->user->first_name . ' ' . $salon->user->last_name) : '');
            $sheet->setCellValue('C'. $row, $salon->upgrade == 0 ? 'No' : 'Yes');
            $sheet->setCellValue('D'. $row, Appointments::join('individual', 'individual.uid', '=', 'appointments.freelancer_id')
                                            ->join('users', 'users.id', '=', 'individual.uid')
                                            ->whereBetween('appointments.save_date', [$this->start_date, $this->end_date])
                                            ->whereIn('users.type', ['freelancer', 'individual'])->where('appointments.freelancer_id', $salon->uid)
                                            ->count());
            $sheet->setCellValue('E'. $row, Appointments::join('individual', 'individual.uid', '=', 'appointments.freelancer_id')
                                            ->join('users', 'users.id', '=', 'individual.uid')
                                            ->whereBetween('appointments.save_date', [$this->start_date, $this->end_date])
                                            ->whereIn('users.type', ['freelancer', 'individual'])->where('appointments.freelancer_id', $salon->uid)
                                            ->sum('grand_total'));
            $row++;
        }
        $filename = 'freelancer-appointments-report.xlsx';
        $writer = new Xlsx($spreadSheet);
        $path = storage_path($filename);
        $writer->save($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }

    public function render()
    {
        return view('livewire.report.appointment-freelancer')->extends('layouts.master');
    }
}
