<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Appointments;
use App\Models\Facilities;
use App\Models\SalonService;
use Illuminate\Http\Request;
use App\Models\Salon;
use App\Models\Banners;
use App\Models\Category;
use App\Models\User;
use App\Models\Cities;
use App\Models\Settings;
use App\Models\Individual;
use App\Models\Services;
use App\Models\ServiceReviews;
use App\Models\Specialist;
use App\Models\Packages;
use App\Models\Commission;
use App\Models\Filter;
use App\Models\Products;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Validator;
use DB;

class SalonController extends Controller
{
    public function filters()
    {
        $filters = Filter::get();
        $response = [
            'success' => true,
            'filters' => $filters,
            'status' => 200
        ];
        return response()->json($response, 200);
    }

    public function getBannerData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'required',
            'lng' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.',
                $validator->errors(),
                'status' => 500
            ];
            return response()->json($response, 404);
        }

        /* $searchQuery = Settings::select('allowDistance','searchResultKind')->first();
        if($searchQuery->searchResultKind == 1){
            $values = 3959; // miles
            $distanceType = 'miles';
        }else{
            $values = 6371; // km
            $distanceType = 'km';
        } */

        /* $cities  = Cities::select(DB::raw('cities.id as id,cities.name as name, ( '.$values.' * acos( cos( radians('.$request->lat.') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians('.$request->lng.') ) + sin( radians('.$request->lat.') ) * sin( radians( lat ) ) ) ) AS distance'))
        ->orderBy('distance')
        ->where(['cities.status'=>1])
        ->first();
        $banners =[];
        if (isset($cities) && $cities) {
            $banners =Banners::where('city_id',$cities->id)->get();
        } */

        $banners = Banners::whereIn('position',['search','1'])->where('status', 1)->whereDate('from', '<=', Carbon::today())->whereDate('to', '>=', Carbon::today())->get();
        foreach($banners as $banner) {
            if($banner->link) {
                $banner->value = $banner->link;
                $banner->type = 5;
            }
            unset($banner->link);
        }
        $response = [
            'banners' => $banners,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uid' => 'required',
            'name' => 'required',
            'cover' => 'required',
            'categories' => 'required',
            'address' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'about' => 'required',
            'rating' => 'required',
            'total_rating' => 'required',
            'website' => 'required',
            'timing' => 'required',
            'images' => 'required',
            'zipcode' => 'required',
            'service_at_home' => 'required',
            'verified' => 'required',
            'status' => 'required',
            'have_stylist' => 'required',
            'in_home' => 'required',
            'popular' => 'required',
            'have_shop' => 'required',
            'rate' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.',
                $validator->errors(),
                'status' => 500
            ];
            return response()->json($response, 404);
        }

        $data = Salon::create($request->all());
        if (is_null($data)) {
            $response = [
                'data' => $data,
                'message' => 'error',
                'status' => 500,
            ];
            return response()->json($response, 200);
        }
        Commission::create([
            'uid' => $request->uid,
            'rate' => $request->rate,
            'status' => 1,
        ]);
        $response = [
            'data' => $data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getById(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.',
                $validator->errors(),
                'status' => 500
            ];
            return response()->json($response, 404);
        }

        $data = Salon::where('uid', $request->id)->first();
        if ($data && $data->categories && $data->categories != null) {
            $ids = json_decode($data->categories);
            $cats = Category::WhereIn('id', $ids)->get();
            $data->web_cates_data = $cats;
        }
        if ($data && $data->cid && $data->cid != null) {
            $data->city_data = Cities::find($data->cid);
        }
        if ($data && $data->facilities && $data->facilities != null) {
            $ids = explode(",", $data->facilities);
            $facilities = Facilities::whereIn('id', $ids)->select('id', 'name')->get();
            $data->facilities_data = $facilities;
        }
        if (is_null($data)) {
            $response = [
                'success' => false,
                'message' => 'Data not found.',
                'status' => 404
            ];
            return response()->json($response, 404);
        }

        $response = [
            'data' => $data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getSearchResult(Request $request)
    {
        $str = $request->param ?? "";
        $lat = $request->lat ?? null;
        $lng = $request->lng ?? null;
        $categories = Category::where(['status' => 1])->get();
        $facilities = $request->facilities ? explode(",", $request->facilities) : [];
        $category = $request->category;
        $distanceFrom = $request->distance_from ?? 0;
        $distanceTo = $request->distance_to ?? 0;
        $searchedGender = $request->gender;
        $orderBy = $request->price_sort == "Ascending" ? "asc" : "desc";
        $startingPrice = $request->price_start ?? 0;
        $endingPrice = $request->price_end ?? 0;
        $totalRating = $request->rating ?? 0;
        $values = 6371; // earth's distance in km
        // if ($searchQuery->searchResultKind == 1) {
        //     $values = 3959; // miles
        //     $distanceType = 'miles';
        // } else {
        //     $values = 6371; // km
        //     $distanceType = 'km';
        // }

        $activeSalons = User::whereIn('type', ['salon'])->where('status', '1')->pluck('id');
        $salon = Salon::join('salon_services', 'salon.uid', '=', 'salon_services.uid')
            ->join('services', 'services.id', '=', 'salon_services.service_id')
            ->select(DB::raw('services.id as service_id, services.name as service_name, services.cate_id as categories, salon.id as id,salon.uid as uid,salon.name as name,salon.rating as rating,
            salon.total_rating as total_rating,salon.address as address,salon.cover as cover,salon.lat as lat,salon.lng as lng, 
            ( ' . $values . ' * acos( cos( radians(' . $lat . ') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(' . $lng . ') ) + sin( radians(' . $lat . ') ) * sin( radians( lat ) ) ) ) AS distance,
            salon_services.gender as gender, salon_services.duration as duration, salon_services.price as price, salon_services.off as off, salon_services.discount as discount, salon.upgrade as upgrade, salon_services.status as status'))
            ->where(['salon.status' => 1, 'salon.in_home' => 1])
            ->whereIn('salon.uid', $activeSalons);


        $freelancer = Individual::join('salon_services', 'individual.uid', '=', 'salon_services.uid')
            ->join('services', 'services.id', '=', 'salon_services.service_id')
            ->select(DB::raw('services.id as service_id, services.name as service_name, services.cate_id as categories, individual.id as id,individual.uid as uid,individual.lat as lat,individual.lng as lng,individual.rating as rating, individual.address as address, individual.total_rating as total_rating, users.first_name as first_name,users.last_name as last_name,users.cover as cover, ( ' . $values . ' * acos( cos( radians(' . $lat . ') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(' . $lng . ') ) + sin( radians(' . $lat . ') ) * sin( radians( lat ) ) ) ) AS distance,
            salon_services.gender as gender, salon_services.duration as duration, salon_services.price as price, salon_services.off as off, salon_services.discount as discount, individual.upgrade as upgrade, salon_services.status as status'))
            ->join('users', 'individual.uid', 'users.id')
            ->where(['individual.status' => 1, 'individual.in_home' => 1])
            ->where('users.status', '1');

        if ($str != "") {
            $salon = $salon->where('services.name', 'like', '%' . $str . '%');
            $freelancer = $freelancer->where('services.name', 'like', '%' . $str . '%');
        }
        if (!empty($category)) {
            $salon->where('services.cate_id', $category);
            $freelancer->where('services.cate_id', $category);
            // $salon->whereRaw('JSON_CONTAINS(categories, ?)', [$category]);
            // $freelancer->whereRaw('JSON_CONTAINS(categories, ?)', [$category]);
        }

        if ($distanceFrom) {
            $salon = $salon->having('distance', '>=', $distanceFrom);
            $freelancer = $freelancer->having('distance', '>=', $distanceFrom);
        }

        if ($distanceTo) {
            $salon = $salon->having('distance', '<=', $distanceTo);
            $freelancer = $freelancer->having('distance', '<=', $distanceTo);
        }

        if ($startingPrice) {
            $salon = $salon->having('price', '>=', $startingPrice);
            $freelancer = $freelancer->having('price', '>=', $startingPrice);
        }

        if ($endingPrice) {
            $salon = $salon->having('price', '<=', $endingPrice);
            $freelancer = $freelancer->having('price', '<=', $endingPrice);
        }

        if ($request->has('gender')) {
            
            $salon = $salon->where('salon_services.gender', $searchedGender);
            $freelancer = $freelancer->where('salon_services.gender', $searchedGender);
        }

        if ($orderBy) {
            $salon = $salon->orderBy('price', $orderBy);
            $freelancer = $freelancer->orderBy('price', $orderBy);
        }

        if ($totalRating) {
            $salon = $salon->having('rating', '>=', $totalRating);
            $freelancer = $freelancer->having('rating', '>=', $totalRating);
        }

        foreach ($facilities as $facility) {
            $salon->whereRaw("FIND_IN_SET(?, salon.facilities)", [$facility]);
            $freelancer->whereRaw("FIND_IN_SET(?, individual.facilities)", [$facility]);
        }

        $salon = $salon->get();
        $freelancer = $freelancer->get();

        foreach ($freelancer as $loop) {
            $loop->name = $loop->first_name . ' ' . $loop->last_name;
            $loop->distance = round($loop->distance, 2);
            $serviceReviewsCount = ServiceReviews::where('freelancer_id', $loop->uid)->get();
            $loop['reviewsCount'] = count($serviceReviewsCount);
            $loop['rating'] = ServiceReviews::where('freelancer_id', $loop->uid)->avg('rating') ?? 0;
            $loop['is_premium'] = $loop->upgrade == 1;
            unset($loop['first_name']);
            unset($loop['last_name']);
            unset($loop['upgrade']);
        }
        foreach ($salon as $loop) {
            $loop->distance = round($loop->distance, 2);
            $serviceReviewsCount = ServiceReviews::where('freelancer_id', $loop->uid)->get();
            $loop['reviewsCount'] = count($serviceReviewsCount);
            $loop['rating'] = ServiceReviews::where('freelancer_id', $loop->uid)->avg('rating') ?? 0;
            $loop['is_premium'] = $loop->upgrade == 1;
            unset($loop['upgrade']);
        }
        $response = [
            'partners' => $salon,
            'freelancers' => $freelancer,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getHomeData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'required',
            'lng' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.',
                $validator->errors(),
                'status' => 500
            ];
            return response()->json($response, 404);
        }
        $searchQuery = Settings::select('allowDistance', 'searchResultKind')->first();
        $categories = Category::where(['status' => 1])->get();
        if ($searchQuery->searchResultKind == 1) {
            $values = 3959; // miles
            $distanceType = 'miles';
        } else {
            $values = 6371; // km
            $distanceType = 'km';
        }

        $activeSalons = User::whereIn('type', ['salon'])->where('status', '1')->pluck('id');
        $salon = Salon::select(DB::raw('salon.id as id,salon.uid as uid,salon.name as name,salon.rating as rating,
        salon.total_rating as total_rating,salon.address as address,salon.cover as cover,salon.lat as salon_lat,salon.lng as salon_lng, ( ' . $values . ' * acos( cos( radians(' . $request->lat . ') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(' . $request->lng . ') ) + sin( radians(' . $request->lat . ') ) * sin( radians( lat ) ) ) ) AS distance'))
            //->having('distance', '<', (int)$searchQuery->allowDistance)
            ->orderBy('distance')
            ->where(['salon.status' => 1, 'salon.in_home' => 1])
            ->whereIn('salon.uid', $activeSalons)
            ->get();

        foreach ($salon as $sal) {
            $serviceReviewsCount = ServiceReviews::where('freelancer_id', $sal->uid)->get();
            $sal['reviewsCount'] = count($serviceReviewsCount);
            $sal['rating'] = ServiceReviews::where('freelancer_id', $sal->uid)->avg('rating') ?? 0;
        }

        $freelancer = Individual::select(DB::raw('individual.id as id,individual.uid as uid,individual.lat as lat,individual.lng as lng, ( ' . $values . ' * acos( cos( radians(' . $request->lat . ') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(' . $request->lng . ') ) + sin( radians(' . $request->lat . ') ) * sin( radians( lat ) ) ) ) AS distance'))
            //->having('distance', '<', (int)$searchQuery->allowDistance)
            ->orderBy('distance')
            ->where(['individual.status' => 1, 'individual.in_home' => 1])
            ->get();

        foreach ($freelancer as $loop) {
            $loop->userInfo = User::select('first_name', 'last_name', 'cover')->find($loop->uid);
            $serviceReviewsCount = ServiceReviews::where('freelancer_id', $loop->uid)->get();
            $loop['reviewsCount'] = count($serviceReviewsCount);
            $loop['rating'] = ServiceReviews::where('freelancer_id', $loop->uid)->avg('rating') ?? 0;
        }

        $categories =  Category::where('status', 1)->get();

        $cities  = Cities::select(DB::raw('cities.id as id,cities.name as name, ( ' . $values . ' * acos( cos( radians(' . $request->lat . ') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(' . $request->lng . ') ) + sin( radians(' . $request->lat . ') ) * sin( radians( lat ) ) ) ) AS distance'))
            //->having('distance', '<', (int)$searchQuery->allowDistance)
            ->orderBy('distance')
            ->where(['cities.status' => 1])
            ->first();
        /* $banners =[];
        if (isset($cities) && $cities) {
            $banners =Banners::where('city_id',$cities->id)->get();
        } */
        $banners = Banners::whereIn('position',['0'])->where('status', 1)->whereDate('from', '<=', Carbon::today())->whereDate('to', '>=', Carbon::today())->get();
        foreach($banners as $banner) {
            if($banner->link) {
                $banner->value = $banner->link;
                $banner->type = 5;
            }
            unset($banner->link);
        }
        $salonUID = $salon->pluck('uid')->toArray();
        $freelancerUID = $freelancer->pluck('uid')->toArray();
        $uidArray = Arr::collapse([$salonUID, $freelancerUID]);
        $products = Products::where('in_home', 1)->WhereIn('freelacer_id', $uidArray)->get();
        $response = [
            'salon' => $salon,
            'categories' => $categories,
            'individual' => $freelancer,
            'cities' => $cities,
            'banners' => $banners,
            'products' => $products,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getHomeDataWeb(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'required',
            'lng' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.',
                $validator->errors(),
                'status' => 500
            ];
            return response()->json($response, 404);
        }
        $searchQuery = Settings::select('allowDistance', 'searchResultKind')->first();
        $categories = Category::where(['status' => 1])->get();
        if ($searchQuery->searchResultKind == 1) {
            $values = 3959; // miles
            $distanceType = 'miles';
        } else {
            $values = 6371; // km
            $distanceType = 'km';
        }

        $salon = Salon::select(DB::raw('salon.id as id,salon.uid as uid,salon.name as name,salon.rating as rating,
        salon.total_rating as total_rating,salon.address as address,salon.cover as cover,salon.lat as salon_lat,salon.lng as salon_lng,salon.categories, ( ' . $values . ' * acos( cos( radians(' . $request->lat . ') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(' . $request->lng . ') ) + sin( radians(' . $request->lat . ') ) * sin( radians( lat ) ) ) ) AS distance'))
            ->having('distance', '<', (int)$searchQuery->allowDistance)
            ->orderBy('distance')
            ->where(['salon.status' => 1, 'salon.in_home' => 1])
            ->get();
        foreach ($salon as $loop) {
            $ids = explode(',', $loop->categories);
            $loop->categories = Category::select('id', 'name', 'cover')->WhereIn('id', $ids)->get();
        }

        $freelancer = Individual::select(DB::raw('individual.id as id,individual.fee_start as fee_start,individual.categories,individual.total_rating as total_rating,individual.rating as rating,individual.uid as uid,individual.lat as lat,individual.lng as lng, ( ' . $values . ' * acos( cos( radians(' . $request->lat . ') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(' . $request->lng . ') ) + sin( radians(' . $request->lat . ') ) * sin( radians( lat ) ) ) ) AS distance'))
            ->having('distance', '<', (int)$searchQuery->allowDistance)
            ->orderBy('distance')
            ->where(['individual.status' => 1, 'individual.in_home' => 1])
            ->get();
        foreach ($freelancer as $loop) {
            $loop->userInfo = User::select('first_name', 'last_name', 'cover')->find($loop->uid);
            $ids = explode(',', $loop->categories);
            $loop->categories = Category::select('id', 'name', 'cover')->WhereIn('id', $ids)->get();
        }
        // foreach($freelancer as $loop){
        //     $loop->userInfo = User::select('first_name','last_name','cover')->find($loop->uid);
        // }

        $categories =  Category::where('status', 1)->get();

        $cities  = Cities::select(DB::raw('cities.id as id,cities.name as name, ( ' . $values . ' * acos( cos( radians(' . $request->lat . ') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(' . $request->lng . ') ) + sin( radians(' . $request->lat . ') ) * sin( radians( lat ) ) ) ) AS distance'))
            ->having('distance', '<', (int)$searchQuery->allowDistance)
            ->orderBy('distance')
            ->where(['cities.status' => 1])
            ->first();
        $banners = [];
        if (isset($cities) && $cities) {
            $banners = Banners::where('city_id', $cities->id)->get();
        }
        $salonUID = $salon->pluck('uid')->toArray();
        $freelancerUID = $freelancer->pluck('uid')->toArray();
        $uidArray = Arr::collapse([$salonUID, $freelancerUID]);
        $products = Products::where('in_home', 1)->WhereIn('freelacer_id', $uidArray)->limit(10)->get();
        $response = [
            'salon' => $salon,
            'categories' => $categories,
            'individual' => $freelancer,
            'cities' => $cities,
            'banners' => $banners,
            'products' => $products,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getTopFreelancer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'required',
            'lng' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.',
                $validator->errors(),
                'status' => 500
            ];
            return response()->json($response, 404);
        }
        $searchQuery = Settings::select('allowDistance', 'searchResultKind')->first();
        $categories = Category::where(['status' => 1])->get();
        if ($searchQuery->searchResultKind == 1) {
            $values = 3959; // miles
            $distanceType = 'miles';
        } else {
            $values = 6371; // km
            $distanceType = 'km';
        }

        $activeIndividuals = User::whereIn('type', ['individual', 'freelancer'])->where('status', '1')->pluck('id');
        $data = Individual::select(DB::raw('individual.id as id,individual.uid as uid,individual.categories,individual.fee_start as fee_start,
        individual.rating as rating,individual.total_rating as total_rating, ( ' . $values . ' * acos( cos( radians(' . $request->lat . ') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(' . $request->lng . ') ) + sin( radians(' . $request->lat . ') ) * sin( radians( lat ) ) ) ) AS distance'))
            ->having('distance', '<', (int)$searchQuery->allowDistance)
            ->orderBy('distance')
            ->where(['individual.status' => 1, 'individual.in_home' => 1])
            ->whereIn('individual.uid', $activeIndividuals)
            ->get();
        foreach ($data as $loop) {
            $loop->userInfo = User::select('first_name', 'last_name', 'cover')->find($loop->uid);
            $ids = explode(',', $loop->categories);
            $loop->categories = Category::select('id', 'name', 'cover')->WhereIn('id', $ids)->get();
        }

        $response = [
            'data' => $data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getTopSalon(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'required',
            'lng' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.',
                $validator->errors(),
                'status' => 500
            ];
            return response()->json($response, 404);
        }
        $searchQuery = Settings::select('allowDistance', 'searchResultKind')->first();
        $categories = Category::where(['status' => 1])->get();
        if ($searchQuery->searchResultKind == 1) {
            $values = 3959; // miles
            $distanceType = 'miles';
        } else {
            $values = 6371; // km
            $distanceType = 'km';
        }

        $activeSalons = User::whereIn('type', ['salon'])->where('status', '1')->pluck('id');
        $data = Salon::select(DB::raw('salon.id as id,salon.uid as uid,salon.name as name,salon.rating as rating,
        salon.total_rating as total_rating,salon.address as address,salon.cover as cover,salon.categories as categories, ( ' . $values . ' * acos( cos( radians(' . $request->lat . ') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(' . $request->lng . ') ) + sin( radians(' . $request->lat . ') ) * sin( radians( lat ) ) ) ) AS distance'))
            ->having('distance', '<', (int)$searchQuery->allowDistance)
            ->orderBy('distance')
            ->where(['salon.status' => 1, 'salon.in_home' => 1])
            ->whereIn('salon.uid', $activeSalons)
            ->get();

        foreach ($data as $loop) {
            $ids = explode(',', $loop->categories);
            $loop->categories = Category::select('id', 'name', 'cover')->WhereIn('id', $ids)->get();
        }

        $response = [
            'data' => $data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getDataFromCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'required',
            'lng' => 'required',
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.',
                $validator->errors(),
                'status' => 500
            ];
            return response()->json($response, 404);
        }
        $searchQuery = Settings::select('allowDistance', 'searchResultKind')->first();
        $categories = Category::where(['status' => 1])->get();
        if ($searchQuery->searchResultKind == 1) {
            $values = 3959; // miles
            $distanceType = 'miles';
        } else {
            $values = 6371; // km
            $distanceType = 'km';
        }

        $salon = Salon::select(DB::raw('salon.id as id,salon.uid as uid,salon.name as name,salon.rating as rating,
        salon.total_rating as total_rating,salon.address as address,salon.cover as cover,salon.categories, ( ' . $values . ' * acos( cos( radians(' . $request->lat . ') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(' . $request->lng . ') ) + sin( radians(' . $request->lat . ') ) * sin( radians( lat ) ) ) ) AS distance'))
            ->having('distance', '<', (int)$searchQuery->allowDistance)
            ->orderBy('distance')
            ->where(['salon.status' => 1, 'salon.in_home' => 1])
            ->whereRaw("find_in_set('" . $request->id . "',salon.categories)")
            ->get();
        foreach ($salon as $loop) {
            $ids = explode(',', $loop->categories);
            $loop->categories = Category::select('id', 'name', 'cover')->WhereIn('id', $ids)->get();
        }

        $freelancer = Individual::select(DB::raw('individual.id as id,individual.uid as uid,individual.categories, ( ' . $values . ' * acos( cos( radians(' . $request->lat . ') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(' . $request->lng . ') ) + sin( radians(' . $request->lat . ') ) * sin( radians( lat ) ) ) ) AS distance'))
            ->having('distance', '<', (int)$searchQuery->allowDistance)
            ->orderBy('distance')
            ->where(['individual.status' => 1, 'individual.in_home' => 1])
            ->whereRaw("find_in_set('" . $request->id . "',individual.categories)")
            ->get();
        foreach ($freelancer as $loop) {
            $loop->userInfo = User::select('first_name', 'last_name', 'cover')->find($loop->uid);
            $ids = explode(',', $loop->categories);
            $loop->categories = Category::select('id', 'name', 'cover')->WhereIn('id', $ids)->get();
        }

        $response = [
            'salon' => $salon,
            'individual' => $freelancer,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getDataFromCategoryWeb(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'required',
            'lng' => 'required',
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.',
                $validator->errors(),
                'status' => 500
            ];
            return response()->json($response, 404);
        }
        $searchQuery = Settings::select('allowDistance', 'searchResultKind')->first();
        $categories = Category::where(['status' => 1])->get();
        if ($searchQuery->searchResultKind == 1) {
            $values = 3959; // miles
            $distanceType = 'miles';
        } else {
            $values = 6371; // km
            $distanceType = 'km';
        }

        $salon = Salon::select(DB::raw('salon.id as id,salon.uid as uid,salon.name as name,salon.rating as rating,
        salon.total_rating as total_rating,salon.address as address,salon.cover as cover,salon.categories, ( ' . $values . ' * acos( cos( radians(' . $request->lat . ') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(' . $request->lng . ') ) + sin( radians(' . $request->lat . ') ) * sin( radians( lat ) ) ) ) AS distance'))
            ->having('distance', '<', (int)$searchQuery->allowDistance)
            ->orderBy('distance')
            ->where(['salon.status' => 1, 'salon.in_home' => 1])
            ->whereRaw("find_in_set('" . $request->id . "',salon.categories)")
            ->get();
        foreach ($salon as $loop) {
            $ids = explode(',', $loop->categories);
            $loop->categories = Category::select('id', 'name', 'cover')->WhereIn('id', $ids)->get();
        }

        $freelancer = Individual::select(DB::raw('individual.id as id,individual.uid as uid,individual.categories,individual.fee_start as fee_start,
        individual.rating as rating,individual.total_rating as total_rating, ( ' . $values . ' * acos( cos( radians(' . $request->lat . ') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(' . $request->lng . ') ) + sin( radians(' . $request->lat . ') ) * sin( radians( lat ) ) ) ) AS distance'))
            ->having('distance', '<', (int)$searchQuery->allowDistance)
            ->orderBy('distance')
            ->where(['individual.status' => 1, 'individual.in_home' => 1])
            ->whereRaw("find_in_set('" . $request->id . "',individual.categories)")
            ->get();
        foreach ($freelancer as $loop) {
            $loop->userInfo = User::select('first_name', 'last_name', 'cover')->find($loop->uid);
            $ids = explode(',', $loop->categories);
            $loop->categories = Category::select('id', 'name', 'cover')->WhereIn('id', $ids)->get();
        }

        $response = [
            'salon' => $salon,
            'individual' => $freelancer,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.',
                $validator->errors(),
                'status' => 500
            ];
            return response()->json($response, 404);
        }
        $data = Salon::find($request->id)->update($request->all());

        if (is_null($data)) {
            $response = [
                'success' => false,
                'message' => 'Data not found.',
                'status' => 404
            ];
            return response()->json($response, 404);
        }
        $response = [
            'data' => $data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'uid' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.',
                $validator->errors(),
                'status' => 500
            ];
            return response()->json($response, 404);
        }
        $data = Salon::find($request->id);
        $data2 = User::find($request->uid);
        DB::table('commission')->where('uid', $request->uid)->delete();
        if ($data && $data2) {
            $data->delete();
            $data2->delete();
            $response = [
                'data' => $data,
                'success' => true,
                'status' => 200,
            ];
            return response()->json($response, 200);
        }
        $response = [
            'success' => false,
            'message' => 'Data not found.',
            'status' => 404
        ];
        return response()->json($response, 404);
    }

    public function getAll()
    {
        $data = Salon::all();

        if (is_null($data)) {
            $response = [
                'success' => false,
                'message' => 'Data not found.',
                'status' => 404
            ];
            return response()->json($response, 404);
        }
        foreach ($data as $loop) {
            if ($loop && $loop->categories && $loop->categories != null) {
                $ids = explode(',', $loop->categories);
                $cats = Category::WhereIn('id', $ids)->get();
                $loop->web_cates_data = $cats;
            }
            if ($loop && $loop->cid && $loop->cid != null) {
                $loop->city_data = Cities::find($loop->cid);
            }
        }
        $response = [
            'data' => $data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getListForOffers(Request $request)
    {
        $salon = Salon::all();
        $individuals =  DB::table('individual')
            ->select('individual.*', 'users.first_name as first_name', 'users.last_name as last_name')
            ->join('users', 'individual.uid', 'users.id')
            ->get();
        $data = [];

        foreach ($salon as $row) {
            array_push($data, (object)[
                'name' => $row->name,
                'id' => $row->uid,
            ]);
        }

        foreach ($individuals as $row) {
            array_push($data, (object)[
                'name' => $row->first_name . ' ' . $row->last_name,
                'id' => $row->uid,
            ]);
        }
        $response = [
            'data' => $data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getActiveCities(Request $request)
    {
        $data = Salon::where('status', 1)->get();
        $response = [
            'data' => $data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function importData(Request $request)
    {
        $request->validate([
            "csv_file" => "required",
        ]);
        $file = $request->file("csv_file");
        $csvData = file_get_contents($file);
        $rows = array_map("str_getcsv", explode("\n", $csvData));
        $header = array_shift($rows);
        foreach ($rows as $row) {
            if (isset($row[0])) {
                if ($row[0] != "") {

                    if (count($header) == count($row)) {
                        $row = array_combine($header, $row);
                        $insertInfo =  array(
                            'id' => $row['id'],
                            'name' => $row['name'],
                            'lat' => $row['lat'],
                            'lng' => $row['lng'],
                            'status' => $row['status'],
                        );
                        $checkLead  =  Salon::where("id", "=", $row["id"])->first();
                        if (!is_null($checkLead)) {
                            DB::table('cities')->where("id", "=", $row["id"])->update($insertInfo);
                        } else {
                            DB::table('cities')->insert($insertInfo);
                        }
                    }
                }
            }
        }
        $response = [
            'data' => 'Done',
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function salonDetails(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.',
                $validator->errors(),
                'status' => 500
            ];
            return response()->json($response, 404);
        }
        $data = Salon::where('uid', $request->id)->first();
        $userInfo = User::where('id', $request->id)->first();
        $data['email'] = $userInfo->email;
        $data['mobile'] = $userInfo->mobile;
        $serviceReviewsCount = ServiceReviews::where('freelancer_id', $request->id)->get();
        $data['reviewsCount'] = count($serviceReviewsCount);
        $data['rating'] = ServiceReviews::where('freelancer_id', $request->id)->avg('rating') ?? 0;
        $categories = json_decode($data->categories);
        $categories = is_array($categories) ? $categories : [$categories];
        $specialist = Specialist::where('salon_uid', $request->id)->get();
        $categories = Category::where('status', 1)->WhereIn('id', $categories)->get();
        $packages = Packages::where('uid', $request->id)->get();
        $services = SalonService::where('uid', $request->id)->get();
        foreach ($services as $ser) {
            $service = Services::find($ser->service_id);
            if ($service) {
                $ser->name = $service->name;
            }
        }
        $response = [
            'data' => $data,
            'categories' => $categories,
            'specialist' => $specialist,
            'packages' => $packages,
            'services' => $services,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function upgradeSalon(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'premium' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => false, 'message' => $validator->errors()->first()], 500);
        }

        $id = $request->id;
        $premium = $request->premium;
        $salon = Salon::where('uid', $id)->first();
        if ($salon) {
            $salon->upgrade = $premium;
            $salon->upgrade_date = Carbon::now()->setTimezone('Asia/Kolkata')->format('Y-m-d');
            $salon->save();
            return response()->json(['status' => true, 'message' => 'Salon upgraded successfully'], 200);
        } else {
            return response()->json(['status' => false, 'message' => 'Salon not found'], 500);
        }
    }

    public function checkCOD(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uid' => 'required'
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.',
                $validator->errors(),
                'status' => 500
            ];
            return response()->json($response, 404);
        }
        $cashOnly = false;
        if ($request->uid) {
            $cancelledAppointments = Appointments::where('uid', $request->id)->where('status', 5)->where('pay_method', 1)->count();
            $cashOnly = $cancelledAppointments > 3;
        }
        $response = [
            'cash_only' => $cashOnly,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }
}
