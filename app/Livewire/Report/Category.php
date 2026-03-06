<?php

namespace App\Livewire\Report;

use App\Models\Category as ModelsCategory;
use App\Models\Dealer;
use App\Models\Individual;
use App\Models\Salon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Category extends Component
{
    public $categories, $salons, $freelancers;

    public function mount()
    {
        $this->categories = ModelsCategory::get();
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
    }

    public function export() {
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

        foreach($this->categories as $category) {
            $sheet->setCellValue('A'. $row, $category->name);
            $sheet->setCellValue('B'. $row, Salon::join('users', 'users.id', '=', 'salon.uid')->where('users.type', 'salon')->whereIn('users.id', $this->salons)->whereJsonContains('salon.categories', (int) $category->id)->count());
            $sheet->setCellValue('C'. $row, Individual::join('users', 'users.id', '=', 'individual.uid')->whereIn('users.type', ['freelancer', 'individual'])->whereIn('users.id', $this->freelancers)->whereJsonContains('individual.categories', (int) $category->id)->count());
            $row++;
        }
        $filename = 'category-report.xlsx';
        $writer = new Xlsx($spreadSheet);
        $path = storage_path($filename);
        $writer->save($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }

    public function render()
    {
        return view('livewire.report.category')->extends('layouts.master');
    }
}
