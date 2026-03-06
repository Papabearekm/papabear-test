<?php

namespace App\Livewire\Report;

use App\Models\Appointments;
use App\Models\ProductOrders;
use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CustomersReport extends Component
{
    public $items = [], $start_date, $end_date;

    public function mount() 
    {
        $this->start_date = request()->query('start_date') ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->end_date = request()->query('end_date') ?? Carbon::now()->endOfMonth()->format('Y-m-d');
        $users = User::where('type', 'user')->whereNot('email','like', '%_deleted_%')->get();
        $endDateAdded = Carbon::parse($this->end_date)->addDay()->format('Y-m-d');
        foreach($users as $user) {
            $this->items[] = [
                'name' => $user->first_name . ' ' . $user->last_name,
                'email' => $user->email,
                'phone' => $user->mobile,
                'gender' => $user->gender == 0 ? 'Female' : ($user->gender == 1 ? 'Male' : 'Kids'),
                'bookings' => Appointments::where('uid', $user->id)->whereBetween('save_date', [$this->start_date, $endDateAdded])->where('status', 4)->count(),
                'purchases' => ProductOrders::where('uid', $user->id)->whereBetween('created_at', [$this->start_date, $endDateAdded])->where('status', 4)->count(),
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
            'C1' => 'Email',
            'D1' => 'Phone Number',
            'E1' => 'Gender',
            'F1' => 'Number of Bookings',
            'G1' => 'Number of Purchases',
        ];

        foreach($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
            $sheet->getStyle($cell)->applyFromArray($boldStyle);
        }

        $row = 2;

        foreach($this->items as $index => $order) {
            $sheet->setCellValue('A'. $row, $index + 1);
            $sheet->setCellValue('B'. $row, $order['name']);
            $sheet->setCellValue('C'. $row, $order['email']);
            $sheet->setCellValue('D'. $row, $order['phone']);
            $sheet->setCellValue('E'. $row, $order['gender']);
            $sheet->setCellValue('F'. $row, $order['bookings']);
            $sheet->setCellValue('G'. $row, $order['purchases']);
            $row++;
        }
        $filename = 'customers-report.xlsx';
        $writer = new Xlsx($spreadSheet);
        $path = storage_path($filename);
        $writer->save($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }
    public function render()
    {
        return view('livewire.report.customers-report')->extends('layouts.master');
    }
}
