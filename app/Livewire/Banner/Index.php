<?php

namespace App\Livewire\Banner;

use App\Models\Banners;
use App\Models\Dealer;
use App\Models\Individual;
use App\Models\Salon;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public $banners, $banner_id, $start_date, $end_date, $users;

    public function mount()
    {
        $user = Auth::user();
        if ($user->type === 'dealer') { 
            $dealer = Dealer::where('uid', $user->id)->first();
            $city = $dealer->city;
            $salons = Salon::where('cid', $city)->pluck('uid')->toArray();
            $freelancers = Individual::where('cid', $city)->pluck('uid')->toArray();
            $this->users = array_merge($salons, $freelancers);
        } else {
            $salons = Salon::pluck('uid')->toArray();
            $freelancers = Individual::pluck('uid')->toArray();
            $this->users = array_merge($salons, $freelancers);
        }
        $start = request()->query('start_date')
            ? Carbon::parse(request()->query('start_date'))->format('Y-m-d')
            : Carbon::now()->startOfMonth()->format('Y-m-d');

        $end = request()->query('end_date')
            ? Carbon::parse(request()->query('end_date'))->format('Y-m-d')
            : Carbon::now()->endOfMonth()->format('Y-m-d');

        if ($start > $end) {
            [$start, $end] = [$end, $start];
        }

        $this->start_date = $start;
        $this->end_date = $end;

        // Since values are 'YYYY-mm-dd' strings, string comparison is safe & fast.
        $query = Banners::query()
        ->whereRaw('`from` >= ?', [$start])
        ->whereRaw('`to`   <= ?', [$end]);

        if (!empty($this->users)) {
            $query->whereIn('value', (array)$this->users);
        }
        $allBanners = $query->latest()->get();

        $today = Carbon::today()->format('Y-m-d');

        foreach ($allBanners as $banner) {
            $from = trim((string) $banner->from);
            $to   = $banner->to !== null ? trim((string) $banner->to) : null;

            // Active if: from <= today AND (to is null/empty OR today <= to)
            $isActive = ($from !== '')
                && ($from <= $today)
                && (is_null($to) || $to === '' || $today <= $to);

            $banner->status = $isActive ? 'Active' : 'Inactive';
        }

        $this->banners = $allBanners;
    }

    public function delete($id)
    {
        $this->banner_id = $id;
    }

    public function destroy()
    {
        $banner = Banners::find($this->banner_id);
        $banner->delete();

        $this->reset_fields();
        
        Toastr::success('Banner Deleted', 'Success');
        return redirect()->route('banners');
    }

    public function reset_fields()
    {
        $this->banner_id = '';
    }
    
    public function render()
    {
        return view('livewire.banner.index')->extends('layouts.master');
    }
}
