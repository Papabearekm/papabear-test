<?php

namespace App\Livewire\Dealer;

use App\Models\Cities;
use App\Models\Dealer;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Rule;
use Brian2694\Toastr\Facades\Toastr;

class Create extends Component
{
    use WithFileUploads;
    public $cities;

    #[Rule('required')]
    public $first_name, $last_name, $password, $country_code, $mobile, $gender = 1, 
    $image, $zip_code, $id_proof, $id_proof_back, $bank_name, $bank_ifsc, $bank_account_number, $bank_customer_name, $address;

    #[Rule('required|unique:users,email|email')]
    public $email;

    #[Rule('required|unique:dealers,city|exists:cities,id')]
    public $city;

    public function mount()
    {
        $this->cities = Cities::where('status', 1)->get();
    }

    public function submit()
    {
        $this->validate();

        try {
            DB::beginTransaction();
            if ($this->image) {
                $this->image = $this->image->storePublicly('dealer/image', 'spaces');
            }

            if ($this->id_proof) {
                $this->id_proof = $this->id_proof->storePublicly('dealer/proof', 'spaces');
            }

            if ($this->id_proof_back) {
                $this->id_proof_back = $this->id_proof_back->storePublicly('dealer/proof', 'spaces');
            }

            $user = User::create([
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'password' => Hash::make($this->password),
                'country_code' => $this->country_code,
                'mobile' => $this->mobile,
                'gender' => $this->gender,
                'cover' => $this->image,
                'type' => 'dealer'
            ]);

            $dealer = Dealer::create([
                'uid' => $user->id,
                'name' => $this->first_name . ' ' . $this->last_name,
                'cover' => $this->image,
                'address' => $this->address,
                'city' => $this->city,
                'zip_code' => $this->zip_code,
                'id_proof' => $this->id_proof,
                'id_proof_back' => $this->id_proof_back,
                'bank_name' => $this->bank_name,
                'bank_ifsc' => $this->bank_ifsc,
                'bank_account_number' => $this->bank_account_number,
                'bank_customer_name' => $this->bank_customer_name,
            ]);

            DB::commit();
            Toastr::success('Dealer Added', 'Success');
            return redirect()->route('dealers');
        } catch (\Exception $e) {
            DB::rollBack();
            Toastr::error('Something went wrong', 'Error');
            return redirect()->route('dealer.create');
        }
    }
    public function render()
    {
        return view('livewire.dealer.create')->extends('layouts.master');
    }
}
