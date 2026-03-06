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

class WalletReport extends Component
{
    public $items = [], $start_date, $end_date, $type;

    public function mount()
    {
        $this->start_date = request()->query('start_date') ?? Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->end_date = request()->query('end_date') ?? Carbon::now()->endOfMonth()->format('Y-m-d');
        $this->type = request()->query('type') ?? 'all';
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
        if($this->type != 'all') {
            $userTypes = $this->type == 'Partner' ? ['salon'] : ['individual', 'freelancer'];
        } else {
            $userTypes = ['salon', 'individual', 'freelancer'];
        }
        $users = User::whereIn('type', $userTypes)->whereIn('id', $userIds)->get();
        foreach($users as $user) {
            if($user->type == 'salon') {
                $salon = Salon::where('uid', $user->id)->first();
                $userName = $salon ? $salon->name : '';
            } else {
                $userName = $user->first_name . ' ' . $user->last_name;
            }
            $endDateAdded = Carbon::parse($this->end_date)->addDay()->format('Y-m-d');
            $withdrawals = Withdrawal::whereBetween('created_at', [$this->start_date, $endDateAdded])->where('uid', $user->id)->count();
            $withdrawalAmount = Withdrawal::whereBetween('created_at', [$this->start_date, $endDateAdded])->where('uid', $user->id)->sum('amount');
            $this->items[] = [
                'name' => $userName,
                'type' => $user->type == 'salon' ? 'Partner' : 'Freelancer',
                'balance' => $user->withdrawal_balance,
                'withdrawal_count' => $withdrawals,
                'withdrawal_amount' => $withdrawalAmount,
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
            'B1' => 'Name',
            'C1' => 'Type',
            'D1' => 'Wallet Balance',
            'E1' => 'Total Withdrawals',
            'F1' => 'Withdrawal Amount'
        ];

        foreach($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
            $sheet->getStyle($cell)->applyFromArray($boldStyle);
        }

        $row = 2;

        foreach($this->items as $index => $order) {
            $sheet->setCellValue('A'. $row, $index + 1);
            $sheet->setCellValue('B'. $row, $order['name']);
            $sheet->setCellValue('C'. $row, $order['type']);
            $sheet->setCellValue('D'. $row, $order['balance']);
            $sheet->setCellValue('E'. $row, $order['withdrawal_count']);
            $sheet->setCellValue('F'. $row, $order['withdrawal_amount']);
            $row++;
        }
        $filename = 'wallet-report.xlsx';
        $writer = new Xlsx($spreadSheet);
        $path = storage_path($filename);
        $writer->save($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }

    public function render()
    {
        return view('livewire.report.wallet-report')->extends('layouts.master');
    }
}
