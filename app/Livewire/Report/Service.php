<?php

namespace App\Livewire\Report;

use App\Models\Dealer;
use App\Models\Individual;
use App\Models\Salon;
use App\Models\SalonService;
use App\Models\Services;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Service extends Component
{
    public $services, $salons, $freelancers;

    public function mount()
    {
        $user = Auth::user();
        if($user->type == "dealer") {
            $dealer = Dealer::where('uid', $user->id)->first();
            $city = $dealer->city;
            $this->salons = Salon::where('cid', $city)->pluck('uid')->toArray();
            $this->freelancers = Individual::where('cid', $city)->pluck('uid')->toArray();
        } else {
            $this->salons = Salon::pluck('uid')->toArray();
            $this->freelancers = Individual::pluck('uid')->toArray();
        }
        $this->services = Services::get();
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
            'A1' => 'Category',
            'B1' => 'Number of Partners',
            'C1' => 'Number of Freelancers'
        ];

        foreach($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
            $sheet->getStyle($cell)->applyFromArray($boldStyle);
        }

        $row = 2;

        foreach($this->services as $service) {
            $sheet->setCellValue('A'. $row, $service->name);
            $sheet->setCellValue('B'. $row, SalonService::join('users', 'users.id', '=', 'salon_services.uid')->join('services','salon_services.service_id','=','services.id')->where('users.type', 'salon')->whereIn('users.id', $this->salons)->where('salon_services.service_id', $service->id)->count());
            $sheet->setCellValue('C'. $row, SalonService::join('users', 'users.id', '=', 'salon_services.uid')->join('services','salon_services.service_id','=','services.id')->whereIn('users.type', ['individual', 'freelancer'])->whereIn('users.id', $this->freelancers)->where('salon_services.service_id', $service->id)->count());
            $row++;
        }
        $filename = 'services-report.xlsx';
        $writer = new Xlsx($spreadSheet);
        $path = storage_path($filename);
        $writer->save($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }

    public function render()
    {
        return view('livewire.report.service')->extends('layouts.master');
    }
}
