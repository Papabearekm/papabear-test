<?php

namespace App\Livewire\Dealer;

use App\Models\Cities;
use App\Models\Dealer;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Rule;
use Illuminate\Validation\Rule as VRule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;
    public $cities, $dealer, $image, $id_proof, $id_proof_back, $image_preview, $password, $id_proof_preview, $id_proof_back_preview;

    #[Rule('required')]
    public $first_name, $last_name, $country_code, $mobile, $gender = 1, 
    $zip_code, $bank_name, $bank_ifsc, $bank_account_number, $bank_customer_name, $address;

    #[Rule('required|email')]
    public $email;

    #[Rule('required|exists:cities,id')]
    public $city;

    public function mount($dealer)
    {
        $this->cities = Cities::where('status', 1)->get();
        $this->dealer = Dealer::with('user')->find($dealer);
        $this->first_name = $this->dealer->user->first_name;
        $this->last_name = $this->dealer->user->last_name;
        $this->email = $this->dealer->user->email;
        $this->country_code = $this->dealer->user->country_code;
        $this->mobile = $this->dealer->user->mobile;
        $this->gender = $this->dealer->user->gender;
        $this->image_preview = $this->dealer->cover;
        $this->id_proof_preview = $this->dealer->id_proof;
        $this->id_proof_back_preview = $this->dealer->id_proof_back;
        $this->city = $this->dealer->city;
        $this->zip_code = $this->dealer->zip_code;
        $this->bank_name = $this->dealer->bank_name;
        $this->bank_ifsc = $this->dealer->bank_ifsc;
        $this->bank_account_number = $this->dealer->bank_account_number;
        $this->bank_customer_name = $this->dealer->bank_customer_name;
        $this->address = $this->dealer->address;
    }

    public function rules()
    {
        return [
            'email' => [
                'required',
                'email',
                VRule::unique('users', 'email')->ignore($this->dealer->user->id),
            ],
            'city' => [
                'required',
                'exists:cities,id',
                // If dealers.city stores a city_id and must be unique per dealer row:
                VRule::unique('dealers', 'city')->ignore($this->dealer->id),
            ],
        ];
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
            } else {
                $this->id_proof = $this->dealer->id_proof;
            }

            if ($this->id_proof_back) {
                $this->id_proof_back = $this->id_proof_back->storePublicly('dealer/proof', 'spaces');
            } else {
                $this->id_proof_back = $this->dealer->id_proof_back;
            }

            $this->dealer->user->update([
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'country_code' => $this->country_code,
                'mobile' => $this->mobile,
                'gender' => $this->gender,
                'cover' => $this->image,
            ]);

            if($this->password) {
                $this->dealer->user->update([
                    'password' => Hash::make($this->password)
                ]);
            }

            $this->dealer->update([
                'city' => $this->city,
                'zip_code' => $this->zip_code,
                'id_proof' => $this->id_proof,
                'id_proof_back' => $this->id_proof_back,
                'bank_name' => $this->bank_name,
                'bank_ifsc' => $this->bank_ifsc,
                'bank_account_number' => $this->bank_account_number,
                'bank_customer_name' => $this->bank_customer_name,
                'address' => $this->address,
            ]);

            DB::commit();
            Toastr::success('Dealer Updated', 'Success');
            return redirect()->route('dealers');
        } catch (\Exception $e) {
            DB::rollBack();
            Toastr::error('Something went wrong', 'Error');
            return redirect()->route('dealer.edit', $this->dealer->id);
        }
    }

    public function render()
    {
        return view('livewire.dealer.edit')->extends('layouts.master');
    }
}
