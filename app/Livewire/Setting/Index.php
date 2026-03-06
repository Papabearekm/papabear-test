<?php

namespace App\Livewire\Setting;

use App\Models\Settings;
use Brian2694\Toastr\Facades\Toastr;
use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Rule;
use Livewire\Component;
use Livewire\WithFileUploads;

class Index extends Component
{
    use WithFileUploads;

    public $setting, $logo, $logo_preview;

    #[Rule('required')]
    public $name, $number, $email, $address, $city, $state, $zip, $country, $allow_distance, $default_city;

    #[Rule('required')]
    public $default_zip, $delivery_charge, $tax_charge, $commission_percentage, $delivery_type, $have_shop, $search_result, $search_radius;

    #[Rule('required')]
    public $currency_symbol, $currency_side, $currency_code, $app_direction, $user_login, $user_verify, $app_color;

    #[Rule('required')]
    public $app_status, $default_country_code, $fcm_token;

    #[Rule('required')]
    public $facebook_social, $twitter_social, $instagram_social, $playstore_social, $appstore_social, $website;

    public function mount()
    {
        $this->setting = Settings::first();

        $this->name = $this->setting->name;
        $this->number = $this->setting->mobile;
        $this->email = $this->setting->email;
        $this->address = $this->setting->address;
        $this->city = $this->setting->city;
        $this->state = $this->setting->state;
        $this->zip = $this->setting->zip;
        $this->country = $this->setting->country;
        $this->tax_charge = $this->setting->tax;
        $this->delivery_charge = $this->setting->delivery_charge;
        $this->commission_percentage = $this->setting->commission_percentage;
        $this->currency_symbol = $this->setting->currencySymbol;
        $this->currency_code = $this->setting->currencyCode;
        $this->currency_side = $this->setting->currencySide;
        $this->app_direction = $this->setting->appDirection;
        $this->logo_preview = $this->setting->logo;
        $this->have_shop = $this->setting->have_shop;
        $this->delivery_type = $this->setting->findType;
        $this->user_login = $this->setting->user_login;
        $this->user_verify = $this->setting->user_verify_with;
        $this->search_radius = $this->setting->search_radius;
        $this->default_country_code = $this->setting->default_country_code;
        $this->default_city = $this->setting->default_city_id;
        $this->default_zip = $this->setting->default_delivery_zip;
        $this->app_color = $this->setting->app_color;
        $this->app_status = $this->setting->app_status;
        $this->allow_distance = $this->setting->allowDistance;
        $this->search_result = $this->setting->searchResultKind;
        $this->fcm_token = $this->setting->fcm_token;

        $this->facebook_social = json_decode($this->setting->social)[0];
        $this->twitter_social = json_decode($this->setting->social)[1];
        $this->instagram_social = json_decode($this->setting->social)[2];
        $this->playstore_social = json_decode($this->setting->social)[3];
        $this->appstore_social = json_decode($this->setting->social)[4];
        $this->website = json_decode($this->setting->social)[5];
    }

    public function submit()
    {
        $this->validate();
        $relativePath = $this->setting->logo;

        try {
            if ($this->logo) {
                if (!empty($relativePath) && Storage::disk('spaces')->exists($relativePath)) {
                    Storage::disk('spaces')->delete($relativePath);
                }
                $relativePath = $this->logo->storePublicly('setting', 'spaces');
            }

            $social_array = [$this->facebook_social, $this->twitter_social, $this->instagram_social, $this->playstore_social, $this->appstore_social, $this->website];
            $social = json_encode($social_array);

            $this->setting->update([
                'name' => $this->name,
                'mobile' => $this->number,
                'email' => $this->email,
                'address' => $this->address,
                'city' => $this->city,
                'state' => $this->state,
                'zip' => $this->zip,
                'country' => $this->country,
                'tax' => $this->tax_charge,
                'delivery_charge' => $this->delivery_charge,
                'commission_percentage' => $this->commission_percentage,
                'currencySymbol' => $this->currency_symbol,
                'currencyCode' => $this->currency_code,
                'currencySide' => $this->currency_side,
                'appDirection' => $this->app_direction,
                'logo' => $relativePath,
                'have_shop' => $this->have_shop,
                'findType' => $this->delivery_type,
                'user_login' => $this->user_login,
                'user_verify_with' => $this->user_verify,
                'search_radius' => $this->search_radius,
                'default_country_code' => $this->default_country_code,
                'default_city_id' => $this->default_city,
                'default_delivery_zip' => $this->default_zip,
                'app_color' => $this->app_color,
                'app_status' => $this->app_status,
                'allowDistance' => $this->allow_distance,
                'searchResultKind' => $this->search_result,
                'fcm_token' => $this->fcm_token,
                'social' => $social,
            ]);

            Toastr::success('Setting Updated', 'Success');
            return redirect()->route('settings');
        } catch (Exception $e) {
            //dd($e);
            Toastr::error('Something went wrong!', 'Failed');
            return redirect()->route('settings');
        }
    }

    public function render()
    {
        return view('livewire.setting.index')->extends('layouts.master');
    }
}
