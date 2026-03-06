<?php

namespace App\Livewire\Report;

use App\Models\Dealer;
use App\Models\Individual;
use App\Models\ProductOrders;
use App\Models\Salon;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class TopProducts extends Component
{
    public $items, $start_date, $end_date;
    
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
        $endDateAdded = Carbon::parse($this->end_date)->addDay()->format('Y-m-d');
        $orders = ProductOrders::where('status', 4)->whereBetween('created_at', [$this->start_date, $endDateAdded])
            ->where(function($query) use ($userIds) {
                $query->where(function($q) use ($userIds) {
                    $q->where('salon_id', '!=', 0)->whereIn('salon_id', $userIds);
                })->orWhere(function($q) use ($userIds) {
                    $q->where('freelancer_id', '!=', 0)->whereIn('freelancer_id', $userIds);
                });
            })
            ->select('orders')->get();
        foreach($orders as $order) {
            $newOrder = json_decode($order->orders, true);
            foreach($newOrder as $item) {
                if(isset($this->items[$item['id']])) {
                    $this->items[$item['id']]['sales'] += $item['quantity'];
                    $this->items[$item['id']]['amount'] += $item['sell_price'];
                } else {
                    $this->items[$item['id']] = [
                        'product' => $item['name'],
                        'category' => $item['cate_id'],
                        'sales' => $item['quantity'],
                        'amount' => $item['sell_price']
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
            'B1' => 'Product',
            'C1' => 'Category',
            'D1' => 'Number of Sales',
            'E1' => 'Total Amount'
        ];

        foreach($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
            $sheet->getStyle($cell)->applyFromArray($boldStyle);
        }

        $row = 2;

        foreach($this->items as $index => $order) {
            $category = $order['category'] ? \App\Models\ProductCategory::find($order['category']) : null;
            $sheet->setCellValue('A'. $row, $index + 1);
            $sheet->setCellValue('B'. $row, $order['product']);
            $sheet->setCellValue('C'. $row, $category ? $category->name : '');
            $sheet->setCellValue('D'. $row, $order['sales']);
            $sheet->setCellValue('E'. $row, $order['amount']);
            $row++;
        }
        $filename = 'top-products-report.xlsx';
        $writer = new Xlsx($spreadSheet);
        $path = storage_path($filename);
        $writer->save($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }
    public function render()
    {
        return view('livewire.report.top-products')->extends('layouts.master');
    }
}
