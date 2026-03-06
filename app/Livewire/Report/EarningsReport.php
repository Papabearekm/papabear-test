<?php

namespace App\Livewire\Report;

use App\Models\Appointments;
use App\Models\Banners;
use App\Models\Dealer;
use App\Models\Individual;
use App\Models\ProductOrders;
use App\Models\Salon;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class EarningsReport extends Component
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
            $salons = Salon::where('cid', $city)->pluck('uid')->toArray();
            $freelancers = Individual::where('cid', $city)->pluck('uid')->toArray();
        } else {
            $salons = Salon::pluck('uid')->toArray();
            $freelancers = Individual::pluck('uid')->toArray();
        }
        $userIds = array_merge($salons, $freelancers);
        $period = CarbonPeriod::create($this->start_date, $this->end_date);
        $items = [];
        foreach ($period as $date) {
            $dateStr = $date->format('Y-m-d');

            $productOrdersIncome = ProductOrders::whereDate('created_at', $dateStr)
                ->where('status', 4)
                ->where(function($query) use ($userIds) {
                    $query->where(function($q) use ($userIds) {
                        $q->where('salon_id', '!=', 0)->whereIn('salon_id', $userIds);
                    })->orWhere(function($q) use ($userIds) {
                        $q->where('freelancer_id', '!=', 0)->whereIn('freelancer_id', $userIds);
                    });
                })
                ->sum('grand_total');

            $appointmentsIncome = Appointments::whereDate('save_date', $dateStr)
                ->where('status', 4)
                ->where(function($query) use ($userIds) {
                    $query->where(function($q) use ($userIds) {
                        $q->where('salon_id', '!=', 0)->whereIn('salon_id', $userIds);
                    })->orWhere(function($q) use ($userIds) {
                        $q->where('freelancer_id', '!=', 0)->whereIn('freelancer_id', $userIds);
                    });
                })
                ->sum('grand_total');

            $adsIncome = Banners::whereDate('from', $dateStr)
                ->where('status', 1)
                ->whereIn('value', $userIds)
                ->sum('price');

            $salonUpgrades = Salon::whereDate('upgrade_date', $dateStr)
                ->whereIn('uid', $salons)
                ->where('upgrade', 1)
                ->count();
            
            $freelancerUpgrades = Individual::whereDate('upgrade_date', $dateStr)
                ->whereIn('uid', $freelancers)
                ->where('upgrade', 1)
                ->count();
            
            $upgradesAmount = ($salonUpgrades + $freelancerUpgrades) * 2000;

            $total = (($productOrdersIncome * 0.05) + (($productOrdersIncome * 0.05) * 0.18)) + (($appointmentsIncome * 0.05) + (($appointmentsIncome * 0.05) * 0.18)) + $adsIncome + $upgradesAmount;

            if ($productOrdersIncome > 0 || $appointmentsIncome > 0 || $adsIncome > 0 || $upgradesAmount > 0) {
                $items[] = [
                    'date' => Carbon::parse($dateStr)->format('d-m-Y'),
                    'product_income' => number_format($productOrdersIncome,2),
                    'product_income_commission' => number_format(((($productOrdersIncome * 5) / 100) + ((($productOrdersIncome * 5) / 100) * 0.18)),2),
                    'appointments_income' => number_format($appointmentsIncome,2),
                    'appointments_income_commission' => number_format(((($appointmentsIncome * 5) / 100) + ((($appointmentsIncome * 5) / 100) * 0.18)),2),
                    'ads_income' => number_format($adsIncome,2),
                    'upgrades_income' => number_format($upgradesAmount,2),
                    'total_income' => number_format($total,2),
                    'raw_date' => $dateStr, // keep raw date for sorting
                ];
            }
        }
        $this->items = collect($items)
        ->sortByDesc('raw_date')
        ->values()
        ->map(function ($item) {
            unset($item['raw_date']);
            return $item;
        })
        ->toArray();
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
            'C1' => 'Product Sales',
            'D1' => 'Product Commission',
            'E1' => 'Appointments',
            'F1' => 'Appointments Commission',
            'G1' => 'Ads',
            'H1' => 'Upgrades',
            'I1' => 'Total',
        ];

        foreach($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
            $sheet->getStyle($cell)->applyFromArray($boldStyle);
        }

        $row = 2;

        foreach($this->items as $index => $order) {
            $sheet->setCellValue('A'. $row, $index + 1);
            $sheet->setCellValue('B'. $row, $order['date']);
            $sheet->setCellValue('C'. $row, $order['product_income']);
            $sheet->setCellValue('D'. $row, $order['product_income_commission']);
            $sheet->setCellValue('E'. $row, $order['appointments_income']);
            $sheet->setCellValue('F'. $row, $order['appointments_income_commission']);
            $sheet->setCellValue('G'. $row, $order['ads_income']);
            $sheet->setCellValue('H'. $row, $order['upgrades_income']);
            $sheet->setCellValue('I'. $row, $order['total_income']);
            $row++;
        }
        $filename = 'earnings-report.xlsx';
        $writer = new Xlsx($spreadSheet);
        $path = storage_path($filename);
        $writer->save($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }

    public function render()
    {
        return view('livewire.report.earnings-report')->extends('layouts.master');
    }
}
