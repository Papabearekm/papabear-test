<?php

namespace App\Livewire\Report;

use App\Models\Dealer;
use App\Models\Individual;
use App\Models\Salon;
use App\Models\User;
use App\Models\Withdrawal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class WithdrawalsReport extends Component
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
            $freelancers = Individual::where('cid', $city)->pluck('uid')->toArray();
            $salons = Salon::where('cid', $city)->pluck('uid')->toArray();
        } else {
            $freelancers = Individual::pluck('uid')->toArray();
            $salons = Salon::pluck('uid')->toArray();
        }
        $userIds = array_merge($freelancers, $salons);
        $userIds = array_unique($userIds);
        $users = User::whereIn('type', ['salon', 'individual', 'freelancer'])->whereIn('id', $userIds)->get();
        foreach($users as $user) {
            if($user->type == 'salon') {
                $salon = Salon::where('uid', $user->id)->first();
                $userName = $salon ? $salon->name : '';
            } else {
                $userName = $user->first_name . ' ' . $user->last_name;
            }
            $endDateAdded = Carbon::parse($this->end_date)->addDay()->format('Y-m-d');
            $withdrawals = Withdrawal::where('uid', $user->id)->whereBetween('created_at', [$this->start_date, $endDateAdded])->latest('id')->get();
            foreach($withdrawals as $withdrawal) {
                $this->items[] = [
                    'partner' => $userName,
                    'date' => $withdrawal->created_at->format('d-m-Y'),
                    'withdrawal_amount' => $withdrawal->amount,
                ];
            }
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
            'C1' => 'Partner',
            'D1' => 'Withdrawal Amount',
        ];

        foreach($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
            $sheet->getStyle($cell)->applyFromArray($boldStyle);
        }

        $row = 2;

        foreach($this->items as $index => $order) {
            $sheet->setCellValue('A'. $row, $index + 1);
            $sheet->setCellValue('B'. $row, $order['partner']);
            $sheet->setCellValue('C'. $row, $order['date']);
            $sheet->setCellValue('D'. $row, $order['withdrawal_amount']);
            $row++;
        }
        $filename = 'withdrawals-report.xlsx';
        $writer = new Xlsx($spreadSheet);
        $path = storage_path($filename);
        $writer->save($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }

    public function render()
    {
        return view('livewire.report.withdrawals-report')->extends('layouts.master');
    }
}
