<?php

namespace App\Livewire\Freelancer;

use App\Models\Category;
use App\Models\Cities;
use App\Models\Dealer;
use App\Models\Individual;
use App\Models\Salon;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    public $categories, $cities, $image_preview, $id_proof_preview, $id_proof_back_preview, $executives;

    #[Rule('required')]
    public $first_name, $last_name, $email, $password, $country_code='+91', $mobile, $gender = 1, $image;

    #[Rule('required')]
    public $about, $address, $city, $zip_code, $latitude, $longitude;

    #[Rule('required')]
    public $id_proof, $bank_name, $bank_ifsc, $bank_account_number, $bank_customer_name;

    public $id_proof_back, $fee_start, $pan, $vat, $heard_us_from, $executive_id, $whatsapp_number, $team_size;

    public array $category = [];

    public function mount()
    {
        $user = Auth::user();
        if ($user->type === 'dealer') {
            $dealer = Dealer::where('uid', $user->id)->first();
            $city = $dealer->city;
            $this->cities = Cities::where('status', 1)->where('id', $city)->get();
        } else {
            $this->cities = Cities::where('status', 1)->get();
        }
        $this->executives = User::where('type', 'agent')->get();
        $this->categories = Category::where('status', 1)->get();
    }

    public function submit()
    {
        $this->validate();
        if(sizeof($this->category) == 0) {
            Toastr::error('Please select atleast one category', 'Failed');
            return redirect()->route('salon.create');
        }

        try {
            DB::beginTransaction();
            if ($this->image) {
                $this->image = $this->image->storePublicly('freelancer/image', 'spaces');
            }

            if ($this->id_proof) {
                $this->id_proof = $this->id_proof->storePublicly('freelancer/proof', 'spaces');
            }

            if ($this->id_proof_back) {
                $this->id_proof_back = $this->id_proof_back->storePublicly('freelancer/proof', 'spaces');
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
                'type' => 'individual'
            ]);
            $individual = Individual::create([
                'uid' => $user->id,
                'background' => $this->image,
                'categories' => "[" . implode(",", array_map('intval', $this->category)) . "]",
                'address' => $this->address,
                'lat' => $this->latitude,
                'lng' => $this->longitude,
                'cid' => $this->city,
                'id_proof' => $this->id_proof,
                'id_proof_back' => $this->id_proof_back,
                'bank_name' => $this->bank_name,
                'bank_ifsc' => $this->bank_ifsc,
                'bank_account_number' => $this->bank_account_number,
                'bank_customer_name' => $this->bank_customer_name,
                'about' => $this->about,
                'zipcode' => $this->zip_code,
                'total_rating' => 0,
                'fee_start' => $this->fee_start ?: 0,
                'pan' => $this->pan,
                'vat' => $this->vat,
                'heard_us_from' => $this->heard_us_from,
                'executive_id' => $this->executive_id,
                'whatsapp_number' => $this->whatsapp_number,
                'team_size' => $this->team_size
            ]);
            DB::commit();

            Toastr::success('Freelancer Added', 'Success');
            return redirect()->route('freelancers');
        } catch (Exception $e) {
            DB::rollBack();
            //dd($e);
            Log::error($e);
            Toastr::error('Something Went Wrong', 'Failed');
            return redirect()->route('freelancer.create');
        }
    }

    public function render()
    {
        return view('livewire.freelancer.create')->extends('layouts.master');
    }
}
