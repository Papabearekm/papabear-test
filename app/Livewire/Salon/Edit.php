<?php

namespace App\Livewire\Salon;

use App\Models\Category;
use App\Models\Cities;
use App\Models\Facilities;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class Edit extends Component
{
    use WithFileUploads;

    public User $user;

    public $categories, $cities, $image_preview, $image, $id_proof, $id_proof_preview, $id_proof_back_preview, $agent_id, $executives, $fee_start;

    #[Rule('required')]
    public $first_name, $last_name, $email, $country_code, $mobile, $gender;

    #[Rule('required')]
    public $salon_name, $about, $address, $city, $zip_code, $commission_rate, $latitude, $longitude;

    #[Rule('required')]
    public $bank_name, $bank_ifsc, $bank_account_number, $bank_customer_name;

    public $id_proof_back, $website, $facilities, $heard_us_from, $pan, $vat, $whatsapp_number, $team_size;
    public array $selectedFacilities = [];
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

        $this->category = json_decode($this->user->salon->categories);
        $this->image_preview = $this->user->salon->cover;
        $this->salon_name = $this->user->salon->name;
        $this->about = $this->user->salon->about;
        $this->address = $this->user->salon->address;
        $this->city = $this->user->salon->cid;
        $this->zip_code = $this->user->salon->zipcode;
        $this->commission_rate = $this->user->salon->rating;
        $this->latitude = $this->user->salon->lat;
        $this->longitude = $this->user->salon->lng;
        $this->website = $this->user->salon->website;
        $this->fee_start = $this->user->salon->fee_start;
        $this->bank_name = $this->user->salon->bank_name;
        $this->bank_ifsc = $this->user->salon->bank_ifsc;
        $this->bank_account_number = $this->user->salon->bank_account_number;
        $this->bank_customer_name = $this->user->salon->bank_customer_name;
        $this->heard_us_from = $this->user->salon->heard_us_from;
        $this->id_proof_preview = $this->user->salon->id_proof;
        $this->id_proof_back_preview = $this->user->salon->id_proof_back;
        $this->pan = $this->user->salon->pan;
        $this->vat = $this->user->salon->vat;
        $this->agent_id = $this->user->salon->agent_id;
        $this->whatsapp_number = $this->user->salon->whatsapp_number;
        $this->team_size = $this->user->salon->team_size;
        $this->facilities = Facilities::where('status', 'Active')->get();
        if($this->user->salon->facilities)
        {
            $this->selectedFacilities = explode(",",$this->user->salon->facilities);
        }
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
                File::delete(public_path('uploads/'.$this->user->salon->cover));
                $this->image = $this->image->storePublicly('salon/image', 'spaces');
            } else {
                $this->image = $this->user->salon->cover;
            }

            if ($this->id_proof) {
                File::delete(public_path('uploads/'.$this->user->salon->id_proof));
                $this->id_proof = $this->id_proof->storePublicly('salon/proof', 'spaces');
            } else {
                $this->id_proof = $this->user->salon->id_proof;
            }

            if ($this->id_proof_back) {
                File::delete(public_path('uploads/'.$this->user->salon->id_proof_back));
                $this->id_proof_back = $this->id_proof_back->storePublicly('salon/proof', 'spaces');
            } else {
                $this->id_proof_back = $this->user->salon->id_proof_back;
            }

            $this->user->update([
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'country_code' => $this->country_code,
                'mobile' => $this->mobile,
                'gender' => $this->gender
            ]);

            $this->user->salon->update([
                'name' => $this->salon_name,
                'cover' => $this->image,
                'categories' => "[" . implode(",", array_map('intval', $this->category)) . "]",
                'address' => $this->address,
                'lat' => $this->latitude,
                'lng' => $this->longitude,
                'cid' => $this->city,
                'about' => $this->about,
                'website' => $this->website,
                'zipcode' => $this->zip_code,
                'id_proof' => $this->id_proof,
                'bank_name' => $this->bank_name,
                'bank_ifsc' => $this->bank_ifsc,
                'bank_account_number' => $this->bank_account_number,
                'bank_customer_name' => $this->bank_customer_name,
                'rating' => $this->commission_rate,
                'id_proof_back' => $this->id_proof_back,
                'heard_us_from' => $this->heard_us_from,
                'agent_id' => $this->agent_id,
                'pan' => $this->pan,
                'vat' => $this->vat,
                'whatsapp_number' => $this->whatsapp_number,
                'team_size' => $this->team_size,
                'facilities' => implode(",", $this->selectedFacilities),
            ]);
            DB::commit();

            Toastr::success('Salon Updated', 'Success');
            return redirect()->route('salons');
        } catch (Exception $e) {
            DB::rollBack();
            //dd($e);
            Toastr::error('Something Went Wrong', 'Failed');
            return redirect()->route('salon.edit', $this->user->id);
        }
    }

    public function render()
    {
        return view('livewire.salon.edit')->extends('layouts.master');
    }
}
