<?php

namespace App\Livewire\Report;

use App\Models\CouponRedeem;
use App\Models\Dealer;
use App\Models\Individual;
use App\Models\Offers;
use App\Models\Salon;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class OffersReport extends Component
{
    public $items = [], $start_date, $end_date;

    public function mount()
    {
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
        $this->start_date = request()->query('start_date') ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->end_date = request()->query('end_date') ?? Carbon::now()->endOfMonth()->format('Y-m-d');
        $offers = Offers::whereBetween('expire', [$this->start_date, $this->end_date])->where(function ($q) use ($userIds) {
            foreach ($userIds as $id) {
                $q->orWhereRaw('FIND_IN_SET(?, freelancer_ids)', [$id]);
            }
        })->latest()->get();
        foreach($offers as $offer) {
            $freelancerIds = explode(',', $offer->freelancer_ids);
            $users = User::whereIn('id', $freelancerIds)->pluck('first_name')->toArray();
            $usedCount = 0;
            $usedCount = CouponRedeem::where('coupon_id', $offer->id)->count() ?? 0;
            $this->items[] = [
                'name' => $offer->name,
                'code' => $offer->code,
                'expiry' => Carbon::parse($offer->expire)->format('d-m-Y'),
                'freelancer' => implode(', ', $users),
                'usage' => $usedCount . '/' . $offer->max_usage 
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
            'B1' => 'Offer Name',
            'C1' => 'Coupon Code',
            'D1' => 'Expiry Date',
            'E1' => 'Partner',
            'F1' => 'Usage'
        ];

        foreach($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
            $sheet->getStyle($cell)->applyFromArray($boldStyle);
        }

        $row = 2;

        foreach($this->items as $index => $order) {
            $sheet->setCellValue('A'. $row, $index + 1);
            $sheet->setCellValue('B'. $row, $order['name']);
            $sheet->setCellValue('C'. $row, $order['code']);
            $sheet->setCellValue('D'. $row, $order['expiry']);
            $sheet->setCellValue('E'. $row, $order['freelancer']);
            $sheet->setCellValue('F'. $row, $order['usage']);
            $row++;
        }
        $filename = 'offers-report.xlsx';
        $writer = new Xlsx($spreadSheet);
        $path = storage_path($filename);
        $writer->save($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }

    public function render()
    {
        return view('livewire.report.offers-report')->extends('layouts.master');
    }
}
