<?php

namespace App\Livewire\Report;

use App\Models\Redeem;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Referrals extends Component
{
    public $items = [], $start_date, $end_date;

    public function mount()
    {
        $this->start_date = request()->query('start_date') ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->end_date = request()->query('end_date') ?? Carbon::now()->endOfMonth()->format('Y-m-d');
        $endDateAdded = Carbon::parse($this->end_date)->addDay()->format('Y-m-d');
        $redeems = Redeem::whereBetween('created_at', [$this->start_date, $endDateAdded])->get();
        foreach($redeems as $redeem) {
            $owner = User::find($redeem->owner);
            $redeemer = User::find($redeem->redeemer);
            $this->items[] = [
                'user' => $owner ? ($owner->first_name . ' ' . $owner->last_name) : '-',
                'redeemer' => $redeemer ? ($redeemer->first_name . ' ' . $redeemer->last_name) : '-',
                'code' => $redeem->code,
                'date' => $redeem->created_at->format('d-m-Y'),
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
            'B1' => 'Date',
            'C1' => 'Referred User',
            'D1' => 'Redeemed User',
            'E1' => 'Referral Code',
        ];

        foreach($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
            $sheet->getStyle($cell)->applyFromArray($boldStyle);
        }

        $row = 2;

        foreach($this->items as $index => $order) {
            $sheet->setCellValue('A'. $row, $index + 1);
            $sheet->setCellValue('B'. $row, $order['date']);
            $sheet->setCellValue('C'. $row, $order['user']);
            $sheet->setCellValue('D'. $row, $order['redeemer']);
            $sheet->setCellValue('E'. $row, $order['code']);
            $row++;
        }
        $filename = 'referrals-report.xlsx';
        $writer = new Xlsx($spreadSheet);
        $path = storage_path($filename);
        $writer->save($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }
    public function render()
    {
        return view('livewire.report.referrals')->extends('layouts.master');
    }
}
