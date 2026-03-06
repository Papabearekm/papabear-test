<?php

namespace App\Livewire\PartnerAds;

use App\Models\PartnerAds;
use Brian2694\Toastr\Facades\Toastr;
use Livewire\Component;

class Index extends Component
{
    public $banners, $banner_id;

    public function mount()
    {
        $this->banners = PartnerAds::get();
    }

    public function delete($id)
    {
        $this->banner_id = $id;
    }

    public function destroy()
    {
        $banner = PartnerAds::find($this->banner_id);
        $banner->delete();

        $this->reset_fields();
        
        Toastr::success('Banner Deleted', 'Success');
        return redirect()->route('partner-ads');
    }

    public function updateStatus($id)
    {
        $banner = PartnerAds::find($id);
        $currentStatus = $banner->status;
        $banner->status = $currentStatus ? 0 : 1;
        $banner->save();
        Toastr::success('Status Changed', 'Success');
        return redirect()->route('partner-ads');
    }

    public function reset_fields()
    {
        $this->banner_id = '';
    }
    
    public function render()
    {
        return view('livewire.partner_ads.index')->extends('layouts.master');
    }
}
