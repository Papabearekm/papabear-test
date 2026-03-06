<?php

namespace App\Livewire\Cities;

use App\Models\Cities;
use Brian2694\Toastr\Facades\Toastr;
use Livewire\Component;
use Livewire\Attributes\Rule;

class Index extends Component
{
    public $cities, $cities_id;

    #[Rule('required')]
    public $name;

    #[Rule('required')]
    public $countries;

    #[Rule('required')]
    public $country;

    #[Rule('required')]
    public $latitude;

    #[Rule('required')]
    public $longitude;

    public function mount()
    {
        $this->cities = Cities::where('status', 1)->get();
        $this->countries = [
            "Afghanistan",
            "Albania",
            "Algeria",
            "Andorra",
            "Angola",
            "Antigua and Barbuda",
            "Argentina",
            "Armenia",
            "Australia",
            "Austria",
            "Azerbaijan",
            "Bahamas",
            "Bahrain",
            "Bangladesh",
            "Barbados",
            "Belarus",
            "Belgium",
            "Belize",
            "Benin",
            "Bhutan",
            "Bolivia",
            "Bosnia and Herzegovina",
            "Botswana",
            "Brazil",
            "Brunei",
            "Bulgaria",
            "Burkina Faso",
            "Burundi",
            "Côte d'Ivoire",
            "Cabo Verde",
            "Cambodia",
            "Cameroon",
            "Canada",
            "Central African Republic",
            "Chad",
            "Chile",
            "China",
            "Colombia",
            "Comoros",
            "Congo (Congo-Brazzaville)",
            "Costa Rica",
            "Croatia",
            "Cuba",
            "Cyprus",
            "Czechia (Czech Republic)",
            "Democratic Republic of the Congo (Congo-Kinshasa)",
            "Denmark",
            "Djibouti",
            "Dominica",
            "Dominican Republic",
            "Ecuador",
            "Egypt",
            "El Salvador",
            "Equatorial Guinea",
            "Eritrea",
            "Estonia",
            "Eswatini",
            "Ethiopia",
            "Fiji",
            "Finland",
            "France",
            "Gabon",
            "Gambia",
            "Georgia",
            "Germany",
            "Ghana",
            "Greece",
            "Grenada",
            "Guatemala",
            "Guinea",
            "Guinea-Bissau",
            "Guyana",
            "Haiti",
            "Holy See",
            "Honduras",
            "Hungary",
            "Iceland",
            "India",
            "Indonesia",
            "Iran",
            "Iraq",
            "Ireland",
            "Israel",
            "Italy",
            "Jamaica",
            "Japan",
            "Jordan",
            "Kazakhstan",
            "Kenya",
            "Kiribati",
            "Kuwait",
            "Kyrgyzstan",
            "Laos",
            "Latvia",
            "Lebanon",
            "Lesotho",
            "Liberia",
            "Libya",
            "Liechtenstein",
            "Lithuania",
            "Luxembourg",
            "Madagascar",
            "Malawi",
            "Malaysia",
            "Maldives",
            "Mali",
            "Malta",
            "Marshall Islands",
            "Mauritania",
            "Mauritius",
            "Mexico",
            "Micronesia",
            "Moldova",
            "Monaco",
            "Mongolia",
            "Montenegro",
            "Morocco",
            "Mozambique",
            "Myanmar (formerly Burma)",
            "Namibia",
            "Nauru",
            "Nepal",
            "Netherlands",
            "New Zealand",
            "Nicaragua",
            "Niger",
            "Nigeria",
            "North Korea",
            "North Macedonia (formerly Macedonia)",
            "Norway",
            "Oman",
            "Pakistan",
            "Palau",
            "Palestine State",
            "Panama",
            "Papua New Guinea",
            "Paraguay",
            "Peru",
            "Philippines",
            "Poland",
            "Portugal",
            "Qatar",
            "Romania",
            "Russia",
            "Rwanda",
            "Saint Kitts and Nevis",
            "Saint Lucia",
            "Saint Vincent and the Grenadines",
            "Samoa",
            "San Marino",
            "Sao Tome and Principe",
            "Saudi Arabia",
            "Senegal",
            "Serbia",
            "Seychelles",
            "Sierra Leone",
            "Singapore",
            "Slovakia",
            "Slovenia",
            "Solomon Islands",
            "Somalia",
            "South Africa",
            "South Korea",
            "South Sudan",
            "Spain",
            "Sri Lanka",
            "Sudan",
            "Suriname",
            "Sweden",
            "Switzerland",
            "Syria",
            "Tajikistan",
            "Tanzania",
            "Thailand",
            "Timor-Leste",
            "Togo",
            "Tonga",
            "Trinidad and Tobago",
            "Tunisia",
            "Turkey",
            "Turkmenistan",
            "Tuvalu",
            "Uganda",
            "Ukraine",
            "United Arab Emirates",
        ];
    }

    public function submit()
    {
        $this->validate();

        if ($this->cities_id) {
            $city = Cities::find($this->cities_id);

            $city->update([
                'name' => $this->name,
                'country' => $this->country,
                'lat' => $this->latitude,
                'lng' => $this->longitude
            ]);

            $message = 'Cities Updated!';
        } else {
            Cities::create([
                'name' => $this->name,
                'country' => $this->country,
                'lat' => $this->latitude,
                'lng' => $this->longitude
            ]);

            $message = 'Cities Added!';
        }

        $this->reset_fields();

        Toastr::success($message, 'Success');
        return redirect()->route('cities');
    }

    public function edit($id)
    {
        $city = Cities::find($id);
        $this->cities_id = $id;
        $this->name = $city->name;
        $this->country = $city->country;
        $this->latitude = $city->lat;
        $this->longitude = $city->lng;
    }

    public function delete($id)
    {
        $this->cities_id = $id;
    }

    public function destroy()
    {
        $city = Cities::find($this->cities_id);
        $city->update([
            'status' => 0
        ]);

        $this->reset_fields();

        Toastr::success('City Deleted', 'Success');
        return redirect()->route('cities');
    }

    public function reset_fields()
    {
        $this->cities_id = '';
        $this->name = '';
        $this->country = '';
        $this->latitude = '';
        $this->longitude = '';
    }

    public function render()
    {
        return view('livewire.cities.index')->extends('layouts.master');
    }
}
