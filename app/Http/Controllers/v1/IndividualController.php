<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Facilities;
use App\Models\SalonService;
use App\Models\UserService;
use Illuminate\Http\Request;
use App\Models\Individual;
use App\Models\Category;
use App\Models\User;
use App\Models\Cities;
use App\Models\Services;
use App\Models\Packages;
use App\Models\Commission;
use App\Models\Salon;
use App\Models\ServiceReviews;
use Carbon\Carbon;
use Validator;
use DB;

class IndividualController extends Controller
{
    public function save(Request $request){
        $validator = Validator::make($request->all(), [
            'uid' => 'required',
            'background' => 'required',
            'categories' => 'required',
            'address' => 'required',
            'about' => 'required',
            'lat' => 'required',
            'lng' => 'required',
            'rating' => 'required',
            'total_rating' => 'required',
            'website' => 'required',
            'timing' => 'required',
            'images' => 'required',
            'zipcode' => 'required',
            'verified' => 'required',
            'status' => 'required',
            'in_home'=>'required',
            'popular'=>'required',
            'have_shop'=>'required',
            'rate' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
                'status'=> 500
            ];
            return response()->json($response, 404);
        }

        $data = Individual::create($request->all());
        if (is_null($data)) {
            $response = [
            'data'=>$data,
            'message' => 'error',
            'status' => 500,
        ];
        return response()->json($response, 200);
        }
        Commission::create([
            'uid'=>$request->uid,
            'rate'=>$request->rate,
            'status'=>1,
        ]);
        $response = [
            'data'=>$data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getById(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
                'status'=> 500
            ];
            return response()->json($response, 404);
        }

        $data = Individual::where('uid',$request->id)->first();
        if($data && $data->categories && $data->categories !=null){
            $ids = explode(',',$data->categories);
            $cats = Category::WhereIn('id',$ids)->get();
            $data->web_cates_data = $cats;
        }
        if($data && $data->cid && $data->cid !=null){
            $data->city_data = Cities::find($data->cid);
        }
        if($data && $data->uid && $data->uid !=null){
            $data->user_data = User::find($data->uid);
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
            'data'=>$data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
                'status'=> 500
            ];
            return response()->json($response, 404);
        }
        $requestData = $request->all();
        unset($requestData['cover']);
        $individual = Individual::find($request->id);
        $data = $individual->update($requestData);

        if (is_null($data)) {
            $response = [
                'success' => false,
                'message' => 'Data not found.',
                'status' => 404
            ];
            return response()->json($response, 404);
        }
        User::find($individual->uid)->update(['cover' => $request->cover]);
        $response = [
            'data'=>$data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function delete(Request $request){
     $validator = Validator::make($request->all(), [
            'id' => 'required',
            'uid' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
                'status'=> 500
            ];
            return response()->json($response, 404);
        }
        $data = Individual::find($request->id);
        $data2 = User::find($request->uid);
        DB::table('commission')->where('uid',$request->uid)->delete();
        if ($data && $data2) {
            $data->delete();
            $data2->delete();
            $response = [
                'data'=>$data,
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

    public function getAll(){
        $data = Individual::all();

        if (is_null($data)) {
            $response = [
                'success' => false,
                'message' => 'Data not found.',
                'status' => 404
            ];
            return response()->json($response, 404);
        }
        foreach($data as $loop){
            if($loop && $loop->categories && $loop->categories !=null){
                $ids = explode(',',$loop->categories);
                $cats = Category::WhereIn('id',$ids)->get();
                $loop->web_cates_data = $cats;
            }
            if($loop && $loop->cid && $loop->cid !=null){
                $loop->city_data = Cities::find($loop->cid);
            }
            if($loop && $loop->uid && $loop->uid !=null){
                $loop->user_data = User::find($loop->uid);
            }
        }
        $response = [
            'data'=>$data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getActiveCities(Request $request){
        $data = Individual::where('status',1)->get();
        $response = [
            'data'=>$data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function importData(Request $request){
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

                    if(count($header) == count($row)){
                        $row = array_combine($header, $row);
                        $insertInfo =  array(
                            'id' => $row['id'],
                            'name' => $row['name'],
                            'lat' => $row['lat'],
                            'lng' => $row['lng'],
                            'status' => $row['status'],
                        );
                        $checkLead  =  Individual::where("id", "=", $row["id"])->first();
                        if (!is_null($checkLead)) {
                            DB::table('cities')->where("id", "=", $row["id"])->update($insertInfo);
                        }
                        else {
                            DB::table('cities')->insert($insertInfo);
                        }
                    }
                }
            }
        }
        $response = [
            'data'=>'Done',
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function individualDetails(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
                'status'=> 500
            ];
            return response()->json($response, 404);
        }
        $data = Individual::where('uid',$request->id)->first();
        $userInfo = User::where('id',$request->id)->first();
        $data['email']= $userInfo->email;
        $data['mobile'] = $userInfo->mobile;
        $serviceReviewsCount = ServiceReviews::where('freelancer_id', $request->id)->get();
        $data['reviewsCount'] = count($serviceReviewsCount);
        $data['rating'] = ServiceReviews::where('freelancer_id', $request->id)->avg('rating') ?? 0;
        $categories = json_decode($data->categories);
        $categories = Category::where('status', 1)->WhereIn('id', $categories)->get();
        $packages = Packages::where('uid', $request->id)->get();
        $services = SalonService::where('uid', $request->id)->get();
        foreach($services as $ser) {
            $service = Services::find($ser->service_id);
            if($service) {
                $ser->name = $service->name;
            }
        }
        $response = [
            'data'=>$data,
            'categories'=>$categories,
            'specialist' => null,
            'packages'=>$packages,
            'services' => $services,
            'userInfo'=>$userInfo,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getIndividualInfo(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
                'status'=> 500
            ];
            return response()->json($response, 404);
        }

        $data = Individual::where('uid',$request->id)->first();
        if($data && $data->categories && $data->categories !=null){
            $ids = json_decode($data->categories);
            $cats = Category::WhereIn('id',$ids)->get();
            $data->web_cates_data = $cats;
        }
        if($data && $data->cid && $data->cid !=null){
            $data->city_data = Cities::find($data->cid);
        }
        if($data && $data->facilities && $data->facilities != null)
        {
            $ids = explode(",", $data->facilities);
            $facilities = Facilities::whereIn('id', $ids)->select('id','name')->get();
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
            'data'=>$data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function upgradeIndividual(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' =>'required',
            'premium' => 'required',
        ]);

        if($validator->fails())
        {
            return response()->json(['status' => false,'message' => $validator->errors()->first()], 500);
        }

        $id = $request->id;
        $premium = $request->premium;
        $individual = Individual::where('uid', $id)->first();
        if($individual)
        {   
            $individual->upgrade = $premium;
            $individual->upgrade_date = Carbon::now()->setTimezone('Asia/Kolkata')->format('Y-m-d');
            $individual->save();
            return response()->json(['status' => true,'message' => 'Freelancer upgraded successfully'], 200);
        }
        else
        {
            return response()->json(['status' => false, 'message' => 'Freelancer not found'], 500);
        }
    }

    public function checkPremium(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uid' => 'required'
        ]);

        if($validator->fails())
        {
            return response()->json([
                'success' => false,
                'message' => 'Validation Error '. $validator->errors(),
                'status' => 500 
            ],404);
        }

        $user = User::find($request->uid);
        if($user)
        {
            $individual = Individual::where('uid', $request->uid)->first();
            if($individual)
            {
                return response()->json([
                    'success' => true,
                    'is_premium' => $individual->upgrade == 1
                ]);
            }
            $salon = Salon::where('uid', $request->uid)->first();
            if($salon)
            {
                return response()->json([
                    'success' => true,
                    'is_premium' => $salon->upgrade == 1
                ]);
            }
            else
            {
                return response()->json([
                    'success' => false,
                    'message' => 'Some error occurred',
                    'status' => 404
                ],404);
            }
        }
        else
        {
            return response()->json(['success' => false, 'message' => 'User not found', 'status' => 500],500);
        }
    }
}
