<?php

namespace App\Livewire\Freelancer;

use App\Models\Category;
use App\Models\Cities;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public User $user;

    public $categories, $cities, $image_preview, $image, $id_proof, $id_proof_preview, $id_proof_back_preview, $executives;

    #[Rule('required')]
    public $first_name, $last_name, $email, $country_code, $mobile, $gender;

    #[Rule('required')]
    public $about, $address, $city, $zip_code, $latitude, $longitude;

    #[Rule('required')]
    public $bank_name, $bank_ifsc, $bank_account_number, $bank_customer_name;

    public $id_proof_back, $fee_start, $pan, $vat, $heard_us_from, $executive_id, $whatsapp_number, $team_size;

    public array $category = [];

    public function mount()
    {
        $this->categories = Category::where('status', 1)->get();
        $this->cities = Cities::where('status', 1)->get();
        $this->executives = User::where('type', 'agent')->get();

        $this->first_name = $this->user->first_name;
        $this->last_name = $this->user->last_name;
        $this->email = $this->user->email;
        $this->country_code = $this->user->country_code;
        $this->mobile = $this->user->mobile;
        $this->gender = $this->user->gender;

        $this->category = json_decode($this->user->individual->categories);
        $this->image_preview = $this->user->cover;
        $this->about = $this->user->individual->about;
        $this->address = $this->user->individual->address;
        $this->city = $this->user->individual->cid;
        $this->zip_code = $this->user->individual->zipcode;
        $this->latitude = $this->user->individual->lat;
        $this->longitude = $this->user->individual->lng;
        $this->bank_name = $this->user->individual->bank_name;
        $this->bank_ifsc = $this->user->individual->bank_ifsc;
        $this->bank_account_number = $this->user->individual->bank_account_number;
        $this->bank_customer_name = $this->user->individual->bank_customer_name;
        $this->id_proof_preview = $this->user->individual->id_proof;
        $this->id_proof_back_preview = $this->user->individual->id_proof_back;
        $this->fee_start = $this->user->individual->fee_start;
        $this->pan = $this->user->individual->pan;
        $this->vat = $this->user->individual->vat;
        $this->heard_us_from = $this->user->individual->heard_us_from;
        $this->executive_id = $this->user->individual->executive_id;
        $this->whatsapp_number = $this->user->individual->whatsapp_number;
        $this->team_size = $this->user->individual->team_size;
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
                File::delete(public_path('uploads/'.$this->user->individual->background));
                $this->image = $this->image->storePublicly('salon/image', 'spaces');
            } else {
                $this->image = $this->user->individual->background;
            }

            if ($this->id_proof) {
                File::delete(public_path('uploads/'.$this->user->individual->id_proof));
                $this->id_proof = $this->id_proof->storePublicly('salon/proof', 'spaces');
            } else {
                $this->id_proof = $this->user->individual->id_proof;
            }

            if ($this->id_proof_back) {
                File::delete(public_path('uploads/'.$this->user->individual->id_proof_back));
                $this->id_proof_back = $this->id_proof_back->storePublicly('salon/proof', 'spaces');
            } else {
                $this->id_proof_back = $this->user->individual->id_proof_back;
            }

            $this->user->update([
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'country_code' => $this->country_code,
                'mobile' => $this->mobile,
                'gender' => $this->gender,
                'cover' => $this->image,
            ]);

            $this->user->individual->update([
                'background' => $this->image,
                'categories' => "[" . implode(",", array_map('intval', $this->category)) . "]",
                'address' => $this->address,
                'lat' => $this->latitude,
                'lng' => $this->longitude,
                'cid' => $this->city,
                'about' => $this->about,
                'zipcode' => $this->zip_code,
                'id_proof' => $this->id_proof,
                'bank_name' => $this->bank_name,
                'bank_ifsc' => $this->bank_ifsc,
                'bank_account_number' => $this->bank_account_number,
                'bank_customer_name' => $this->bank_customer_name,
                'id_proof_back' => $this->id_proof_back
            ]);

            $this->user->individual->update([
                'background' => $this->image,
                'categories' => "[" . implode(",", array_map('intval', $this->category)) . "]",
                'address' => $this->address,
                'lat' => $this->latitude,
                'lng' => $this->longitude,
                'cid' => $this->city,
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

            Toastr::success('Freelancer Updated', 'Success');
            return redirect()->route('freelancers');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            Toastr::error('Something Went Wrong', 'Failed');
            return redirect()->route('freelancer.edit', $this->user->id);
        }
    }

    public function render()
    {
        return view('livewire.freelancer.edit')->extends('layouts.master');
    }
}
