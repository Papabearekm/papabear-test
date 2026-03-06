<?php

namespace App\Livewire\Salon;

use App\Models\Dealer;
use App\Models\Salon;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Index extends Component
{
    public $salons, $salon_id, $status, $id;

    public function mount()
    {
        $user = Auth::user();
        if ($user->type === 'dealer') {
            $dealer = Dealer::where('uid', $user->id)->first();
            $city = $dealer->city;

            $this->salons = User::with('salon')
                ->where('type', 'salon')
                ->whereHas('salon', function ($query) use ($city) {
                    $query->where('cid', $city);
                })
                ->get();
        } else {
            $this->salons = User::with('salon')
                ->where('type', 'salon')
                ->get();
        }
    }

    public function delete($id)
    {
        $this->salon_id = $id;
    }

    public function destroy()
    {
        $salon = User::find($this->salon_id);
        $salon->update([
            'status' => 0
        ]);

        $salon->salon->update([
            'status' => 0
        ]);

        $this->reset_fields();

        Toastr::success('Partner Deleted', 'Success');
        return redirect()->route('salons');
    }

    /* public function status($id)
    {
        dd($id);
        $this->id = $id;
    }

    public function status_change()
    {
        dd($this->id);
        $salon = Salon::find($this->id);
        dd($salon);
        $this->status = $salon->status;

        if ($this->status == 1) {
            $salon->update([
                'status' => 0
            ]);
        } else {
            $salon->update([
                'status' => 1
            ]);
        }

        $this->reset_fields();

        Toastr::success('Status Updated', 'Success');
        return redirect()->route('salons');
    } */

    public function reset_fields()
    {
        $this->salon_id = '';
        $this->id = '';
        $this->status = '';
    }

    public function updateStatus($id)
    {
        $salon = Salon::find($id);
        $currentStatus = $salon->status;
        $salon->status = $currentStatus ? 0 : 1;
        $salon->save();
        $user = User::find($salon->uid);
        $userStatus = $user->status;
        $user->status = $userStatus ? 0 : 1;
        $user->save();
        Toastr::success('Status Changed', 'Success');
        return redirect()->route('salons');
    }

    public function updateInHome($id)
    {
        $salon = Salon::find($id);
        $currentStatus = $salon->in_home;
        $salon->in_home = $currentStatus ? 0 : 1;
        $salon->save();
        Toastr::success('In Homepage Status Changed', 'Success');
        return redirect()->route('salons');
    }
    public function updatePopular($id)
    {
        $salon = Salon::find($id);
        $currentStatus = $salon->popular;
        $salon->popular = $currentStatus ? 0 : 1;
        $salon->save();
        Toastr::success('Popular Status Changed', 'Success');
        return redirect()->route('salons');
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

        foreach($this->salons as $index => $salon) {
            $sheet->setCellValue('A'. $row, $index + 1);
            $sheet->setCellValue('B'. $row, $salon->salon->name);
            $sheet->setCellValue('C'. $row, $salon->salon->city->name);
            $sheet->setCellValue('D'. $row, $salon->country_code . ' ' . $salon->mobile);
            $sheet->setCellValue('E'. $row, $salon->salon->whatsapp_number);
            $sheet->setCellValue('F'. $row, $salon->salon->status == 1 ? 'Active' : 'Inactive');
            $sheet->setCellValue('G'. $row, $salon->salon->upgrade == 1 ? 'Yes' : 'No');
            $row++;
        }
        $filename = 'partners.xlsx';
        $writer = new Xlsx($spreadSheet);
        $path = storage_path($filename);
        $writer->save($path);

        return response()->download($path)->deleteFileAfterSend(true);   
    }

    public function render()
    {
        return view('livewire.salon.index')->extends('layouts.master');
    }
}
