<?php

namespace App\Livewire\Freelancer;

use App\Models\Dealer;
use App\Models\Individual;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Index extends Component
{
    public $freelancers, $freelancer_id;

    public function mount()
    {
        $user = Auth::user();
        if ($user->type === 'dealer') {
            $dealer = Dealer::where('uid', $user->id)->first();
            $city = $dealer->city;

            $this->freelancers = User::with('individual')
                ->whereIn('type', ['individual', 'freelancer'])
                ->whereHas('individual', function ($query) use ($city) {
                    $query->where('cid', $city);
                })
                ->get();
        } else {
            $this->freelancers = User::with('individual')
                ->whereIn('type', ['individual', 'freelancer'])
                ->get();
        }
    }

    public function delete($id)
    {
        $this->freelancer_id = $id;
    }

    public function destroy()
    {
        $freelancer = User::find($this->freelancer_id);
        $freelancer->update([
            'status' => 0
        ]);

        $freelancer->individual->update([
            'status' => 0
        ]);

        $this->reset_fields();
        
        Toastr::success('Freelancer Deleted', 'Success');
        return redirect()->route('freelancers');
    }

    public function reset_fields()
    {
        $this->freelancer_id = '';
    }

    public function updateStatus($id)
    {
        $individual = Individual::find($id);
        $currentStatus = $individual->status;
        $individual->status = $currentStatus ? 0 : 1;
        $individual->save();
        $user = User::find($individual->uid);
        $userStatus = $user->status;
        $user->status = $userStatus ? 0 : 1;
        $user->save();
        Toastr::success('Status Changed', 'Success');
        return redirect()->route('freelancers');
    }

    public function updatePopular($id)
    {
        $individual = Individual::find($id);
        $currentStatus = $individual->popular;
        $individual->popular = $currentStatus ? 0 : 1;
        $individual->save();
        Toastr::success('Popular Status Changed', 'Success');
        return redirect()->route('freelancers');
    }

    public function updateInHome($id)
    {
        $individual = Individual::find($id);
        $currentStatus = $individual->in_home;
        $individual->in_home = $currentStatus ? 0 : 1;
        $individual->save();
        Toastr::success('In Homepage Status Changed', 'Success');
        return redirect()->route('freelancers');
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
            'C1' => 'City',
            'D1' => 'Mobile Number',
            'E1' => 'Whatsapp Number',
            'F1' => 'Status',
            'G1' => 'Is Premium'
        ];

        foreach($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
            $sheet->getStyle($cell)->applyFromArray($boldStyle);
        }

        $row = 2;

        foreach($this->freelancers as $index => $freelancer) {
            $sheet->setCellValue('A'. $row, $index + 1);
            $sheet->setCellValue('B'. $row, $freelancer->first_name . ' ' . $freelancer->last_name);
            $sheet->setCellValue('C'. $row, $freelancer->individual->city->name);
            $sheet->setCellValue('D'. $row, $freelancer->country_code . ' ' . $freelancer->mobile);
            $sheet->setCellValue('E'. $row, $freelancer->individual->whatsapp_number);
            $sheet->setCellValue('F'. $row, $freelancer->individual->status == 1 ? 'Active' : 'Inactive');
            $sheet->setCellValue('G'. $row, $freelancer->individual->upgrade == 1 ? 'Yes' : 'No');
            $row++;
        }
        $filename = 'freelancers.xlsx';
        $writer = new Xlsx($spreadSheet);
        $path = storage_path($filename);
        $writer->save($path);

        return response()->download($path)->deleteFileAfterSend(true);   
    }

    public function render()
    {
        return view('livewire.freelancer.index')->extends('layouts.master');
    }
}
