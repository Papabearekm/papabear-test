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

class TopServices extends Component
{
    public $items = [], $start_date, $end_date;

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
        $this->start_date = request()->query('start_date') ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->end_date = request()->query('end_date') ?? Carbon::now()->endOfMonth()->format('Y-m-d');
        $appointments = Appointments::where('status', 4)
            ->where(function($query) use ($userIds) {
                $query->where(function($q) use ($userIds) {
                    $q->where('salon_id', '!=', 0)->whereIn('salon_id', $userIds);
                })->orWhere(function($q) use ($userIds) {
                    $q->where('freelancer_id', '!=', 0)->whereIn('freelancer_id', $userIds);
                });
            })
            ->whereBetween('save_date', [$this->start_date, $this->end_date])->get();
        foreach($appointments as $appointment) {
            $itemsArray = json_decode($appointment->items, true);
            foreach($itemsArray['services'] as $service) {
                if(isset($this->items[$service['id']])) {
                    $this->items[$service['id']]['sales'] += 1;
                    $this->items[$service['id']]['amount'] += $service['price'];
                } else {
                    $this->items[$service['id']] = [
                        'id' => $service['id'],
                        'service' => $service['name'],
                        'sales' => 1,
                        'amount' => $service['price']
                    ];
                }
            }
        }
        $this->items = collect($this->items)->sortByDesc('sales')->take(10)->values()->all();
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
            'B1' => 'Service',
            'C1' => 'Category',
            'D1' => 'Number of Bookings',
            'E1' => 'Total Amount'
        ];

        foreach($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
            $sheet->getStyle($cell)->applyFromArray($boldStyle);
        }

        $row = 2;

        foreach($this->items as $index => $order) {
            $salonService = $order['id'] ? \App\Models\SalonService::find($order['id']) : null;
            $service = $salonService ? \App\Models\Services::find($salonService->service_id) : null;
            $sheet->setCellValue('A'. $row, $index + 1);
            $sheet->setCellValue('B'. $row, $order['service']);
            $sheet->setCellValue('C'. $row, $service ? $service->category->name : '');
            $sheet->setCellValue('D'. $row, $order['sales']);
            $sheet->setCellValue('E'. $row, $order['amount']);
            $row++;
        }
        $filename = 'top-services-report.xlsx';
        $writer = new Xlsx($spreadSheet);
        $path = storage_path($filename);
        $writer->save($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }

    public function render()
    {
        return view('livewire.report.top-services')->extends('layouts.master');
    }
}
