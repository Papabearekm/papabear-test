<?php

namespace App\Livewire\Salon;

use App\Models\Dealer;
use App\Models\Individual;
use App\Models\RegisterRequest;
use App\Models\Salon;
use App\Models\Settings;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Hashing\BcryptHasher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class JoinRequest extends Component
{
    public $requests;

    public function mount()
    {
        $user = Auth::user();
        if($user->type == "dealer") {
            $dealer = Dealer::where('uid', $user->id)->first();
            $city = $dealer->city;
            $this->requests = RegisterRequest::where('type', 'salon')->where('cid', $city)->where('status', 1)->get();
        } else {
            $this->requests = RegisterRequest::where('type', 'salon')->where('status', 1)->get();
        }
    }

    public function approve($id) {
        try {
            DB::beginTransaction();
            $partner_request = RegisterRequest::findOrFail($id);
            
            $user = User::create([
                'first_name' => $partner_request['first_name'],
                'last_name' => $partner_request['last_name'],
                'email' => $partner_request['email'],
                'password' => (new BcryptHasher)->make($partner_request['password']),
                'country_code' => $partner_request['country_code'],
                'mobile' => $partner_request['mobile'],
                'cover' => $partner_request['cover'],
                'gender' => $partner_request['gender'],
                'type' => $partner_request['type'],
                'status' => 1,
            ]);

            $salon = Salon::create([
                'uid' => $user->id,
                'name' => $partner_request['name'],
                'about' => $partner_request['about'],
                'rating' => $partner_request['fee_start'],
                'cid' => $partner_request['cid'],
                'lat' => $partner_request['lat'],
                'lng' => $partner_request['lng'],
                'zipcode' => $partner_request['zipcode'],
                'fee_start' => $partner_request['fee_start'],
                'address' => $partner_request['address'],
                'status' => 1,
                'cover' => $partner_request['cover'],
                'total_rating' => 0,
                'categories' => $partner_request['categories'],
                'id_proof' => $partner_request['id_proof'],
                'id_proof_back' => $partner_request['id_proof_back'],
                'bank_name' => $partner_request['bank_name'],
                'bank_ifsc' => $partner_request['bank_ifsc'],
                'bank_account_number' => $partner_request['bank_account_number'],
                'bank_customer_name' => $partner_request['bank_customer_name'],
                'agent_id' => $partner_request['executive_id'],
                'heard_us_from' => $partner_request['heard_us_from'],
                'whatsapp_number' => $partner_request['whatsapp_number'],
                'team_size' => $partner_request['team_size'],
                'pan' => $partner_request['pan'],
                'vat' => $partner_request['vat'],
                'invoice_prefix' => $this->generateInvoicePrefix($partner_request['name']),
            ]);
            
                        
            $partner_request->update([
                'status' => 0
            ]);
            $email = $partner_request['email'];
            $username = $partner_request['email'];
            $generalInfo = Settings::take(1)->first();
            $subject = 'Welcome to ' . $generalInfo->name;
            $mailTo = Mail::send('mails/accepted',
                [
                    'app_name'      => $generalInfo->name,
                    'user_name'     => $partner_request['first_name'] . ' ' . $partner_request['last_name'],
                    'type'          => 'salon'
                ]
                , function($message) use($email,$username,$subject,$generalInfo){
                $message->to($email, $username)
                ->subject($subject);
                $message->from($generalInfo->email,$generalInfo->name);
            });
            DB::commit();
            Toastr::success('Partner Approved', 'Success');
            return redirect()->route('salon.requests');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e);
            Toastr::error('Something went wrong', 'Failed');
            return redirect()->route('salon.requests');
        }
    }

    public function render()
    {
        return view('livewire.salon.join-request')->extends('layouts.master');
    }

    private function generateInvoicePrefix($name)
    {
        $prefix = strtoupper(substr($name, 0, 3));
        $count = Salon::where('invoice_prefix', 'like', $prefix . '%')->count();
        if($count == 0) {
            $count = Individual::where('invoice_prefix', 'like', $prefix . '%')->count();
        }
        return $prefix . str_pad($count + 1, 2, '0', STR_PAD_LEFT);
    }
}
