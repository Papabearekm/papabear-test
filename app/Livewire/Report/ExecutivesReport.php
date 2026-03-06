<?php

namespace App\Livewire\Report;

use App\Models\Dealer;
use App\Models\Individual;
use App\Models\Salon;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExecutivesReport extends Component
{
    public $items = [], $start_date, $end_date;

    public function mount()
    {
        $this->start_date = request()->query('start_date') ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->end_date = request()->query('end_date') ?? Carbon::now()->endOfMonth()->format('Y-m-d');
        $executives = User::where('type', 'agent')->get();
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
        $endDateAdded = Carbon::parse($this->end_date)->addDay()->format('Y-m-d');
        foreach ($executives as $executive) {
            $createdSalons = Salon::where('agent_id', $executive->id)->whereIn('uid', $salons)->whereBetween('created_at', [$this->start_date, $endDateAdded])->get();
            $createdFreelancers = Individual::where('executive_id', $executive->id)->whereIn('uid', $freelancers)->whereBetween('created_at', [$this->start_date, $endDateAdded])->get();
            $addedSalons = '';
            foreach($createdSalons as $salon) {
                $addedSalons .= $salon->name . '(Partner)' . ($salon->upgrade == 0 ? ' (Not Upgraded)' : ' (Upgraded)') . PHP_EOL;
            }
            foreach($createdFreelancers as $freelancer) {
                $user = User::find($freelancer->uid);
                $addedSalons .= $user ? $user->first_name . ' ' . $user->last_name . '(Freelancer)' . ($freelancer->upgrade == 0 ? ' (Not Upgraded)' : ' (Upgraded)') . PHP_EOL : '';
            }
            $this->items[] = [
                'name' => $executive->first_name . ' ' . $executive->last_name,
                'email' => $executive->email,
                'phone' => $executive->mobile,
                'registrations' => Salon::where('agent_id', $executive->id)->whereIn('uid', $salons)->whereBetween('created_at', [$this->start_date, $endDateAdded])->count() + Individual::where('executive_id', $executive->id)->whereIn('uid', $freelancers)->whereBetween('created_at', [$this->start_date, $endDateAdded])->count(),
                'salons' => $addedSalons,
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
            'B1' => 'Executive',
            'C1' => 'Email',
            'D1' => 'Phone Number',
            'E1' => 'Number of Registrations',
            'F1' => 'Partners',
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
            $sheet->setCellValue('E'. $row, $order['registrations']);
            $sheet->setCellValue('F'. $row, $order['salons']);
            $row++;
        }
        $filename = 'executives-report.xlsx';
        $writer = new Xlsx($spreadSheet);
        $path = storage_path($filename);
        $writer->save($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }

    public function render()
    {
        return view('livewire.report.executives-report')->extends('layouts.master');
    }
}
