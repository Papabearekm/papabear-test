<?php

namespace App\Livewire\User;

use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Livewire\Component;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Index extends Component
{
    public $users, $user_id;

    public function mount()
    {
        $this->users = User::where('type', 'user')->whereNot('email', 'like', '%_deleted_%')->get();
    }

    public function delete($id)
    {
        $this->user_id = $id;
    }

    public function destroy()
    {
        $user = User::find($this->user_id);
        $user->update([
            'status' => 0
        ]);

        $this->reset_fields();
        
        Toastr::success('User Deleted', 'Success');
        return redirect()->route('users');
    }

    public function reset_fields()
    {
        $this->user_id = '';
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
            'C1' => 'Email',
            'D1' => 'Mobile',
            'E1' => 'Status'
        ];

        foreach($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
            $sheet->getStyle($cell)->applyFromArray($boldStyle);
        }

        $row = 2;

        foreach($this->users as $index => $user) {
            $sheet->setCellValue('A'. $row, $index + 1);
            $sheet->setCellValue('B'. $row, ($user->first_name . ' ' . $user->last_name) ?? '-');
            $sheet->setCellValue('C'. $row, $user->email ?? '-');
            $sheet->setCellValue('D'. $row, @$user->country_code . ' ' . @$user->mobile);
            $sheet->setCellValue('E'. $row, $user->status == 1 ? 'Active' : 'Inactive');
            $row++;
        }
        $filename = 'users.xlsx';
        $writer = new Xlsx($spreadSheet);
        $path = storage_path($filename);
        $writer->save($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }

    public function render()
    {
        return view('livewire.user.index')->extends('layouts.master');
    }
}
