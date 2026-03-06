<?php

namespace App\Livewire\Report;

use App\Models\Dealer;
use App\Models\Holiday;
use App\Models\Individual;
use App\Models\Salon;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class HolidaysReport extends Component
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
            $freelancers = Individual::where('cid', $city)->pluck('uid')->toArray();
            $salons = Salon::where('cid', $city)->pluck('uid')->toArray();
        } else {
            $freelancers = Individual::pluck('uid')->toArray();
            $salons = Salon::pluck('uid')->toArray();
        }
        $userIds = array_merge($freelancers, $salons);
        $userIds = array_unique($userIds);
        $holidays = Holiday::whereBetween('date', [$this->start_date, $this->end_date])->whereIn('uid', $userIds)->get();
        foreach($holidays as $holiday) {
            $user = User::find($holiday->uid);
            $this->items[] = [
                'date' => Carbon::parse($holiday->date)->format('d-m-Y'),
                'user' => $user ? ($user->first_name . ' ' . $user->last_name) : '-',
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
            'B1' => 'Partner Name',
            'C1' => 'Holiday Date',
        ];

        foreach($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
            $sheet->getStyle($cell)->applyFromArray($boldStyle);
        }

        $row = 2;

        foreach($this->items as $index => $order) {
            $sheet->setCellValue('A'. $row, $index + 1);
            $sheet->setCellValue('B'. $row, $order['user']);
            $sheet->setCellValue('C'. $row, $order['date']);
            $row++;
        }
        $filename = 'holidays-report.xlsx';
        $writer = new Xlsx($spreadSheet);
        $path = storage_path($filename);
        $writer->save($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }

    public function render()
    {
        return view('livewire.report.holidays-report')->extends('layouts.master');
    }
}
