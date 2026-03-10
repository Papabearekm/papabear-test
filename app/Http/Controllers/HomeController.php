<?php

namespace App\Http\Controllers;

use App\Models\AccountDeletion;
use App\Models\Appointments;
use App\Models\Banners;
use App\Models\Booking;
use App\Models\Cities;
use App\Models\Dealer;
use App\Models\Individual;
use App\Models\ProductOrders;
use App\Models\Salon;
use App\Models\Setting;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        if($user->type == 'dealer') {
            $dealer = Dealer::where('uid', $user->id)->first();
            $cities = Cities::where('id', $dealer->city)->orderBy('name', 'asc')->get();
            $city = request()->query('city') ?? $dealer->city;
        } else {
            $cities = Cities::where('status', 1)->orderBy('name', 'asc')->get();
            $city = request()->query('city') ?? 'all';
        }
        $salon = Salon::query();
        $freelancer = Individual::query();

        if($city == 'all'){
            $selectedCities = $cities->pluck('id')->toArray();
            $salon = $salon->pluck('uid')->toArray();
            $freelancer = $freelancer->pluck('uid')->toArray();
        } else {
            $selectedCities = [$city];
            $salon = $salon->where('cid', $city)->pluck('uid')->toArray();
            $freelancer = $freelancer->where('cid', $city)->pluck('uid')->toArray();
        }

        $startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $endDate = Carbon::now()->endOfMonth()->format('Y-m-d');

        if(request()->query('start_date') && request()->query('end_date')) {
            $startDate = request()->query('start_date');
            $endDate = request()->query('end_date');
        }
        $updatedEndDate = Carbon::parse($endDate)->addDay()->format('Y-m-d');

        $salonCount = Salon::whereBetween('created_at', [$startDate, $updatedEndDate])->whereIn('uid', $salon)->whereNot('status', 2)->count();
        $todaySalonCount = Salon::where('created_at', Carbon::now()->format('Y-m-d'))->whereIn('uid', $salon)->count();
        $upgradedSalonsCount = Salon::whereBetween('created_at', [$startDate, $updatedEndDate])->whereIn('uid', $salon)->where('upgrade', 1)->count();
        $freelancerCount = Individual::whereBetween('created_at', [$startDate, $updatedEndDate])->whereIn('uid', $freelancer)->count();
        $todayFreelancerCount = Individual::where('created_at', Carbon::now()->format('Y-m-d'))->whereIn('uid', $freelancer)->count();
        $upgradedFreelancerCount = Individual::whereBetween('created_at', [$startDate, $updatedEndDate])->whereIn('uid', $freelancer)->where('upgrade', 1)->count();
        $salonAppointmentsCount = Appointments::whereBetween('save_date', [$startDate, $updatedEndDate])->whereIn('salon_id', $salon)->count();
        $freelancerAppointmentsCount = Appointments::whereBetween('save_date', [$startDate, $updatedEndDate])->whereIn('freelancer_id', $freelancer)->count();
        $totalAppointmentsCount = $salonAppointmentsCount + $freelancerAppointmentsCount;
        $usersCount = User::whereBetween('created_at', [$startDate, $updatedEndDate])->where('type', 'user')->count();
        $todayUsersCount = User::where('created_at', Carbon::now()->format('Y-m-d'))->where('type', 'user')->count();
        $salonOrdersCount = ProductOrders::whereBetween('created_at', [$startDate, $updatedEndDate])->whereIn('salon_id', $salon)->count();
        $freelancerOrdersCount = ProductOrders::whereBetween('created_at', [$startDate, $updatedEndDate])->whereIn('freelancer_id', $freelancer)->count();
        $totalOrdersCount = $salonOrdersCount + $freelancerOrdersCount;
        Log::info('Salons: ' . implode(',', $salon));
        Log::info('Freelancers: ' . implode(',', $freelancer));
        $freelancerAdsCount = Banners::whereBetween('from', [$startDate, $updatedEndDate])->where('type', '1')->whereIn('value', $freelancer)->count();
        $salonAdsCount = Banners::whereBetween('from', [$startDate, $updatedEndDate])->where('type', '2')->whereIn('value', $salon)->count();
        $adsCount = $freelancerAdsCount + $salonAdsCount;
        $appointmentsFromSalonIncome = Appointments::whereBetween('save_date', [$startDate, $updatedEndDate])->whereIn('salon_id', $salon)->where('status', 4)->sum('grand_total') ?? 0;
        $appointmentsFromFreelancerIncome = Appointments::whereBetween('save_date', [$startDate, $updatedEndDate])->whereIn('freelancer_id', $freelancer)->where('status', 4)->sum('grand_total') ?? 0;
        $appointmentsIncome = $appointmentsFromSalonIncome + $appointmentsFromFreelancerIncome;
        $productOrdersFromSalonIncome = ProductOrders::whereBetween('created_at', [$startDate, $updatedEndDate])->whereIn('salon_id', $salon)->where('status', 4)->sum('grand_total') ?? 0;
        $productOrdersFromFreelancerIncome = ProductOrders::whereBetween('created_at', [$startDate, $updatedEndDate])->whereIn('freelancer_id', $freelancer)->where('status', 4)->sum('grand_total') ?? 0;
        $productOrdersIncome = $productOrdersFromSalonIncome + $productOrdersFromFreelancerIncome;
        $salonAdsIncome = Banners::whereBetween('from', [$startDate, $updatedEndDate])->where('type', '2')->whereIn('value', $salon)->sum('price') ?? 0;
        $freelancerAdsIncome = Banners::whereBetween('from', [$startDate, $updatedEndDate])->where('type', '1')->whereIn('value', $freelancer)->sum('price') ?? 0;
        $adsIncome = $salonAdsIncome + $freelancerAdsIncome;

        $currentDate = Carbon::today(); // or now(), but use dates consistently
        $daysCount   = 3;
        $endDateForFuture     = $currentDate->copy()->addDays($daysCount);

        $appointments = Appointments::whereBetween('save_date', [
                $currentDate->toDateString(),
                $endDateForFuture->toDateString(),
            ])
            ->where(function ($q) use ($salon, $freelancer) {
                $q->whereIn('salon_id', $salon);

                if (!empty($freelancer)) {
                    $q->orWhereIn('freelancer_id', $freelancer);
                }
            })
            ->select('id', 'uid', 'freelancer_id', 'salon_id', 'save_date', 'status')
            ->orderBy('save_date')
            ->take(5)              // final combined limit
            ->get();

        $futureAppointments = [];

        foreach ($appointments as $appointment) {
            $user = User::find($appointment->uid);

            $partner = $appointment->freelancer_id == 0
                ? Salon::where('uid', $appointment->salon_id)->first()
                : User::find($appointment->freelancer_id);

            $futureAppointments[] = [
                'date'         => Carbon::parse($appointment->save_date)->format('d-m-Y'),
                'user_name'    => $user ? ($user->first_name . ' ' . $user->last_name) : '-',
                'partner_name' => $partner ? ($partner->name ?? ($partner->first_name . ' ' . $partner->last_name)) : '-',
            ];
        }
        $weekDays = [];
        $weeklyAppointments = [];
        $weeklyAppointmentsIncome = [];
        $weeklyAds = [];
        $weeklyAdsIncome = [];
        $weeklyOrders = [];
        $weeklyOrdersIncome = [];
        $graphPeriod = request()->query('graph') ?? 'Weekly';

        // Normalize range as Carbon instances (inclusive)
        $rangeStart = Carbon::parse($startDate)->startOfDay();
        $rangeEnd   = Carbon::parse($endDate)->endOfDay();

        if ($graphPeriod === 'Weekly' || $graphPeriod === 'Monthly') {
            // Day-wise buckets across the selected range
            // (Weekly/Monthly both render per-day; you can keep them separate if you want different labeling)
            $period = CarbonPeriod::create($rangeStart->copy()->startOfDay(), $rangeEnd->copy()->startOfDay());

            foreach ($period as $d) {
                $dateStr = $d->toDateString(); // 'Y-m-d'
                // Counts (scoped by selected salons/freelancers)
                $salonApptsCnt = Appointments::whereIn('salon_id', $salon)
                    ->whereDate('save_date', $dateStr)->count();
                $freelApptsCnt = Appointments::whereIn('freelancer_id', $freelancer)
                    ->whereDate('save_date', $dateStr)->count();
                $appointmentsCount = $salonApptsCnt + $freelApptsCnt;

                // Income for the day (appointments)
                $salonApptsIncome = Appointments::whereIn('salon_id', $salon)
                    ->whereDate('save_date', $dateStr)->sum('grand_total') ?? 0;
                $freelApptsIncome = Appointments::whereIn('freelancer_id', $freelancer)
                    ->whereDate('save_date', $dateStr)->sum('grand_total') ?? 0;
                $todayAppointmentsIncome = $salonApptsIncome + $freelApptsIncome;

                // Ads (assuming 'from' is a DATE)
                $salonAdsCount = Banners::where('type', '1')->whereIn('value', $salon)->whereDate('from', $dateStr)->count();
                $freelancerAdsCount = Banners::where('type', '2')->whereIn('value', $freelancer)->whereDate('from', $dateStr)->count();
                $todayAdsCount = $salonAdsCount + $freelancerAdsCount;
                $salonAdsIncome = Banners::where('type', '1')->whereIn('value', $salon)->whereDate('from', $dateStr)->sum('price') ?? 0;
                $freelancerAdsIncome = Banners::where('type', '2')->whereIn('value', $freelancer)->whereDate('from', $dateStr)->sum('price') ?? 0;
                $todayAdsIncome = $salonAdsIncome + $freelancerAdsIncome;

                // Orders (scoped by selected salons/freelancers)
                $salonOrdersCnt = ProductOrders::whereIn('salon_id', $salon)
                    ->whereDate('created_at', $dateStr)->count();
                $freelOrdersCnt = ProductOrders::whereIn('freelancer_id', $freelancer)
                    ->whereDate('created_at', $dateStr)->count();
                $ordersCount = $salonOrdersCnt + $freelOrdersCnt;

                $salonOrdersIncome = ProductOrders::whereIn('salon_id', $salon)
                    ->whereDate('created_at', $dateStr)->sum('grand_total') ?? 0;
                $freelOrdersIncome = ProductOrders::whereIn('freelancer_id', $freelancer)
                    ->whereDate('created_at', $dateStr)->sum('grand_total') ?? 0;
                $ordersIncome = $salonOrdersIncome + $freelOrdersIncome;

                $weekDays[] = Carbon::parse($dateStr)->format('d M');
                $weeklyAppointments[] = $appointmentsCount;
                $weeklyAppointmentsIncome[] = $todayAppointmentsIncome;
                $weeklyAds[] = $todayAdsCount;
                $weeklyAdsIncome[] = $todayAdsIncome;
                $weeklyOrders[] = $ordersCount;
                $weeklyOrdersIncome[] = $ordersIncome;
            }
        }

        if ($graphPeriod === 'Yearly') {
            // Month-wise buckets across the selected range
            $monthCursor = $rangeStart->copy()->startOfMonth();
            $lastMonth   = $rangeEnd->copy()->startOfMonth();

            while ($monthCursor->lte($lastMonth)) {
                $monthStart = $monthCursor->copy()->startOfMonth();
                $monthEnd   = $monthCursor->copy()->endOfMonth();
                // clamp to selected range
                if ($monthStart->lt($rangeStart)) $monthStart = $rangeStart->copy();
                if ($monthEnd->gt($rangeEnd))     $monthEnd   = $rangeEnd->copy();

                // Appointments count (scoped)
                $salonApptsCnt = Appointments::whereIn('salon_id', $salon)
                    ->whereBetween('save_date', [$monthStart->toDateString(), $monthEnd->toDateString()])
                    ->count();
                $freelApptsCnt = Appointments::whereIn('freelancer_id', $freelancer)
                    ->whereBetween('save_date', [$monthStart->toDateString(), $monthEnd->toDateString()])
                    ->count();
                $appointmentsCount = $salonApptsCnt + $freelApptsCnt;

                // Appointments income (scoped)
                $salonApptsIncome = Appointments::whereIn('salon_id', $salon)
                    ->whereBetween('save_date', [$monthStart->toDateString(), $monthEnd->toDateString()])
                    ->sum('grand_total') ?? 0;
                $freelApptsIncome = Appointments::whereIn('freelancer_id', $freelancer)
                    ->whereBetween('save_date', [$monthStart->toDateString(), $monthEnd->toDateString()])
                    ->sum('grand_total') ?? 0;
                $monthAppointmentsIncome = $salonApptsIncome + $freelApptsIncome;

                // Ads (assumes 'from' is DATE; month bucket)
                $salonAdsCount = Banners::where('type', '1')->whereIn('value', $salon)
                    ->whereBetween('from', [$monthStart->toDateString(), $monthEnd->toDateString()])
                    ->count();
                $freelancerAdsCount = Banners::where('type', '2')->whereIn('value', $freelancer)
                    ->whereBetween('from', [$monthStart->toDateString(), $monthEnd->toDateString()])
                    ->count();
                $todayAdsCount = $salonAdsCount + $freelancerAdsCount;
                $salonAdsIncome = Banners::where('type', '1')->whereIn('value', $salon)
                    ->whereBetween('from', [$monthStart->toDateString(), $monthEnd->toDateString()])
                    ->sum('price') ?? 0;
                $freelancerAdsIncome = Banners::where('type', '2')->whereIn('value', $freelancer)
                    ->whereBetween('from', [$monthStart->toDateString(), $monthEnd->toDateString()])
                    ->sum('price') ?? 0;
                $todayAdsIncome = $salonAdsIncome + $freelancerAdsIncome;

                // Orders (scoped)
                $salonOrdersCnt = ProductOrders::whereIn('salon_id', $salon)
                    ->whereBetween('created_at', [$monthStart, $monthEnd])->count();
                $freelOrdersCnt = ProductOrders::whereIn('freelancer_id', $freelancer)
                    ->whereBetween('created_at', [$monthStart, $monthEnd])->count();
                $ordersCount = $salonOrdersCnt + $freelOrdersCnt;

                $salonOrdersIncome = ProductOrders::whereIn('salon_id', $salon)
                    ->whereBetween('created_at', [$monthStart, $monthEnd])->sum('grand_total') ?? 0;
                $freelOrdersIncome = ProductOrders::whereIn('freelancer_id', $freelancer)
                    ->whereBetween('created_at', [$monthStart, $monthEnd])->sum('grand_total') ?? 0;
                $ordersIncome = $salonOrdersIncome + $freelOrdersIncome;

                $weekDays[] = $monthCursor->format('M Y');
                $weeklyAppointments[] = $appointmentsCount;
                $weeklyAppointmentsIncome[] = $monthAppointmentsIncome;
                $weeklyAds[] = $todayAdsCount;
                $weeklyAdsIncome[] = $todayAdsIncome;
                $weeklyOrders[] = $ordersCount;
                $weeklyOrdersIncome[] = $ordersIncome;

                $monthCursor->addMonth();
            }
        }

        return view('index', compact('cities', 'salonCount', 'freelancerCount', 'todaySalonCount', 'todayFreelancerCount', 'salonAppointmentsCount', 'freelancerAppointmentsCount', 'totalAppointmentsCount', 'usersCount', 'todayUsersCount', 'salonOrdersCount', 'freelancerOrdersCount', 'totalOrdersCount', 'futureAppointments', 'adsCount', 'appointmentsIncome', 'productOrdersIncome', 'adsIncome', 'upgradedSalonsCount', 'upgradedFreelancerCount', 'weekDays', 'weeklyAppointments', 'weeklyAds', 'weeklyOrders', 'weeklyAppointmentsIncome', 'weeklyAdsIncome', 'weeklyOrdersIncome'));
    }

    public function login()
    {
        if(Auth::check()) {
            if(in_array(Auth::user()->type, ['admin', 'tele', 'dealer'])){
                return redirect()->route('home');
            }
        }
        return view('auth.login');
    }

    public function loginsubmit(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required'
        ],[
            'email.required' => 'email field is required',
            'email.email' => 'enter an valid email',
            'password.required' => 'password field is required'
        ]);

        $user= $request->all();


        $login = Auth::attempt([
            'email' => $user['email'],
            'password' => $user['password'],
            'status' => 1
        ]);

        if($login && in_array(Auth::user()->type, ['admin', 'tele', 'dealer'])){
            return redirect()->route('home');
        }
        else{
            Toastr::error('Invalid Password');
            return redirect()->back();
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
