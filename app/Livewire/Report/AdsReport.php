<?php

namespace App\Livewire\Report;

use App\Models\Banners;
use App\Models\Dealer;
use App\Models\Individual;
use App\Models\Salon;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AdsReport extends Component
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
        $ads = Banners::whereBetween('from', [$this->start_date, $this->end_date])->whereIn('value', $userIds)->latest('created_at', 'desc')->get();
        $today = Carbon::today()->format('Y-m-d');
        foreach($ads as $ad) {
            $individual = Individual::where('uid', $ad->value)->first();
            $salon = Salon::where('uid', $ad->value)->first();
            $partner = $salon ? $salon->name : ($individual ? User::find($individual->uid)->first_name . ' ' . User::find($individual->uid)->last_name : '-');
            $daysDifference = Carbon::parse($ad->to)->diffInDays(Carbon::parse($ad->from));
            $plan = $daysDifference <= 7 ? 'Weekly' : ($daysDifference <= 14 ? 'Bi-Weekly' : ($daysDifference <= 28 ? 'Monthly' : 'Custom Plan'));
            $from = trim((string) $ad->from);
            $to   = $ad->to !== null ? trim((string) $ad->to) : null;

            // Active if: from <= today AND (to is null/empty OR today <= to)
            $isActive = ($from !== '')
                && ($from <= $today)
                && (is_null($to) || $to === '' || $today <= $to);
            $this->items[] = [
                'partner' => $partner,
                'title' => $ad->title,
                'position' => $ad->position == 0 ? 'Home' : 'Search',
                'validity' => Carbon::parse($ad->from)->format('d-m-Y') . ' to ' . Carbon::parse($ad->to)->format('d-m-Y'),
                'price' => $plan . ' - ' . $ad->price,
                'status' => $isActive ? 'Active' : 'Inactive'
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
            'B1' => 'Partner/Freelancer',
            'C1' => 'Title',
            'D1' => 'Position',
            'E1' => 'Plan & Price',
            'F1' => 'Validity',
            'G1' => 'Status',
        ];

        foreach($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
            $sheet->getStyle($cell)->applyFromArray($boldStyle);
        }

        $row = 2;

        foreach($this->items as $index => $order) {
            $sheet->setCellValue('A'. $row, $index + 1);
            $sheet->setCellValue('B'. $row, $order['partner']);
            $sheet->setCellValue('C'. $row, $order['title']);
            $sheet->setCellValue('D'. $row, $order['position']);
            $sheet->setCellValue('E'. $row, $order['validity']);
            $sheet->setCellValue('F'. $row, $order['price']);
            $sheet->setCellValue('G'. $row, $order['status']);
            $row++;
        }
        $filename = 'ads-report.xlsx';
        $writer = new Xlsx($spreadSheet);
        $path = storage_path($filename);
        $writer->save($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }

    public function render()
    {
        return view('livewire.report.ads-report')->extends('layouts.master');
    }
}
