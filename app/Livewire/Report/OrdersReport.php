<?php

namespace App\Livewire\Report;

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

class OrdersReport extends Component
{
    public $items = [], $start_date, $end_date, $statuses, $status;

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
        $ordersQuery = ProductOrders::query();
        $this->statuses = ['Created', 'Accepted', 'Rejected', 'Ongoing', 'Completed', 'Cancelled', 'Refunded', 'Delayed', 'Payment Pending'];
        $this->start_date = request()->query('start_date') ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->end_date = request()->query('end_date') ?? Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->status = request()->query('status') ?? 'all';
        if($this->status != 'all') {
            $ordersQuery->where('status', $this->status);
        }
        $endDateAdded = Carbon::parse($this->end_date)->addDay()->format('Y-m-d');
        $ordersQuery->whereBetween('created_at', [$this->start_date, $endDateAdded]);
        $ordersQuery->where(function($query) use ($userIds) {
            $query->where(function($q) use ($userIds) {
                $q->where('salon_id', '!=', 0)->whereIn('salon_id', $userIds);
            })->orWhere(function($q) use ($userIds) {
                $q->where('freelancer_id', '!=', 0)->whereIn('freelancer_id', $userIds);
            });
        });
        $orders = $ordersQuery->latest()->get();
        foreach($orders as $order) {
            $customer = User::find($order->uid);
            if($order->freelancer_id == 0) {
                $partner = Salon::where('uid', $order->salon_id)->first();
                $partnerName = $partner ? $partner->name : '-';
            } else {
                $partner = User::find($order->freelancer_id);
                $partnerName = $partner ? ($partner->first_name . ' ' . $partner->last_name) : '-';
            }
            $this->items[] = [
                'customer' => $customer ? ($customer->first_name . ' ' . $customer->last_name) : '-',
                'order_id' => $order->id,
                'partner' => $partnerName,
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
            'B1' => 'Customer',
            'C1' => 'Partner',
            'D1' => 'Appointment Date',
            'E1' => 'Status',
            'F1' => 'Payment Method',
            'G1' => 'Total Amount',
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
            $sheet->setCellValue('E'. $row, $order['status']);
            $sheet->setCellValue('F'. $row, $order['payment_status']);
            $sheet->setCellValue('G'. $row, $order['amount']);
            $row++;
        }
        $filename = 'orders-report.xlsx';
        $writer = new Xlsx($spreadSheet);
        $path = storage_path($filename);
        $writer->save($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }
    
    public function render()
    {
        return view('livewire.report.orders-report')->extends('layouts.master');
    }
}
