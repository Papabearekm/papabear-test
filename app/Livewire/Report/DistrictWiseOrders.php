<?php

namespace App\Livewire\Report;

use App\Models\Cities;
use App\Models\Dealer;
use App\Models\Individual;
use App\Models\ProductOrders;
use App\Models\Salon;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class DistrictWiseOrders extends Component
{
    public $items = [], $start_date, $end_date, $statuses, $status, $cities, $city;

    public function mount()
    {
        $user = Auth::user();
        if($user->type == "dealer") {
            $dealer = Dealer::where('uid', $user->id)->first();
        }
        $ordersQuery = ProductOrders::query();
        $this->statuses = ['Created', 'Accepted', 'Rejected', 'Ongoing', 'Completed', 'Cancelled', 'Refunded', 'Delayed', 'Payment is Pending'];
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
        if($this->status != 'all')
        {
            $ordersQuery->where('status', $this->status);
        }
        $newEndDate = Carbon::parse($this->end_date)->addDay();
        $ordersQuery->whereBetween('created_at', [$this->start_date, $newEndDate]);
        $ordersQuery->where(function($query) use ($salons, $freelancers) {
            $query->where(function($q) use ($salons) {
                $q->where('salon_id', '!=', 0)->whereIn('salon_id', $salons);
            })->orWhere(function($q) use ($freelancers) {
                $q->where('freelancer_id', '!=', 0)->whereIn('freelancer_id', $freelancers);
            });
        });
        $orders = $ordersQuery->latest()->get();
        foreach($orders as $order) {
            $customer = User::find($order->uid);
            if($order->freelancer_id == 0) {
                $partner = Salon::where('uid', $order->salon_id)->first();
                $partnerCity = $partner ? Cities::find($partner->cid)->name : '';
                $partnerName = $partner ? $partner->name : '-';
            } else {
                $partner = User::find($order->freelancer_id);
                $freelancer = Individual::where('uid', $order->freelancer_id)->first();
                $partnerCity = $freelancer ? Cities::find($freelancer->cid)->name : '';
                $partnerName = $partner ? ($partner->first_name . ' ' . $partner->last_name) : '-';
            }
            $this->items[] = [
                'customer' => $customer ? ($customer->first_name . ' ' . $customer->last_name) : '-',
                'order_id' => $order->id,
                'partner' => $partnerName,
                'city' => $partnerCity,
                'date' => Carbon::parse($order->date_time)->format('d-m-Y'),
                'status' => $this->statuses[$order->status] ?? 'Unknown',
                'payment_status' => $order->paid_method == 5 ? 'Online Payment' : 'COD',
                'amount' => $order->grand_total,
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

        foreach($this->items as $index => $order) {
            $sheet->setCellValue('A'. $row, $index + 1);
            $sheet->setCellValue('B'. $row, $order['city']);
            $sheet->setCellValue('C'. $row, $order['customer']);
            $sheet->setCellValue('D'. $row, $order['partner']);
            $sheet->setCellValue('E'. $row, $order['date']);
            $sheet->setCellValue('F'. $row, $order['status']);
            $sheet->setCellValue('G'. $row, $order['payment_status']);
            $sheet->setCellValue('H'. $row, $order['amount']);
            $row++;
        }
        $filename = 'city-wise-orders-report.xlsx';
        $writer = new Xlsx($spreadSheet);
        $path = storage_path($filename);
        $writer->save($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }
    
    public function render()
    {
        return view('livewire.report.district-wise-orders')->extends('layouts.master');
    }
}
