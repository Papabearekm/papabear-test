<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Services;
use Validator;
use App\Models\Category;
use App\Models\SalonService;
use App\Models\UserService;
use DB;

class ServicesController extends Controller
{
    public function save(Request $request){
        $validator = Validator::make($request->all(), [
            'uid' => 'required',
            'service_id' => 'required',
            'duration' => 'required',
            'price' => 'required',
            'off' => 'required',
            'discount' => 'required',
            'images' => 'required',
            'cover' => 'required',
            'extra_field' => 'required',
            'status' => 'required',
            'descriptions'=>'required',
            'gender' => 'required'
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
                'status'=> 500
            ];
            return response()->json($response, 404);
        }

        $data = SalonService::create($request->all());
        if (is_null($data)) {
            $response = [
            'data'=>$data,
            'message' => 'error',
            'status' => 500,
        ];
        return response()->json($response, 200);
        }
        $response = [
            'data'=>$data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getServiceById(Request $request){
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

        $data = SalonService::find($request->id);
        $service = Services::find($data->service_id);
    
        if($data && $service->cate_id && $service->cate_id !=null){
            $cats = Category::where('id',$service->cate_id)->first();
            $data->web_cates_data = $cats;
            $data->name = $service->name;
            $data->cate_id = $service->cate_id;
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
    public function getByUID(Request $request){
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

        $data = Services::where('uid',$request->id)->first();

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

    public function getMyServices(Request $request){
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

        $data = SalonService::where('uid',$request->id)->get();
        foreach($data as $loop){
            $service = Services::find($loop->service_id);
            if($service)
            {
                if($service && $service->cate_id && $service->cate_id !=null){
                    $cats = Category::where('id',$service->cate_id)->first();
                    $loop->web_cates_data = $cats;
                    $loop->cate_id = $service->cate_id;
                    $loop->name = $service->name;
                }
            }
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
        $data = SalonService::find($request->id)->update($request->all());

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

    public function delete(Request $request){
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
        $data = SalonService::find($request->id);
        if ($data) {
            $data->delete();
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
        $data = Services::all();
        foreach($data as $loop){
            if($loop && $loop->served_category && $loop->served_category !=null){
                $ids = explode(',',$loop->served_category);
                $cats = Category::WhereIn('id',$ids)->get();
                $loop->web_cates_data = $cats;
            }
            if($loop && $loop->cid && $loop->cid !=null){
                $loop->city_data = Cities::find($loop->cid);
            }
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

    public function getActiveItem(Request $request){
        $data = Services::where('status',1)->get();

        $response = [
            'data'=>$data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getByCategoryId(Request $request){
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
        $data = Services::where(['cate_id'=>$request->id,'uid'=>$request->uid,'status'=>1])->get();
        $response = [
            'data'=>$data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getCompleteServices()
    {
        $freelanceServices = UserService::distinct('service_id')->pluck('service_id');
        $services = Services::whereIn('id',$freelanceServices)->pluck('name');
        return $services;
    }

    public function searchServices($keyword)
    {
        $salonServices = SalonService::distinct('service_id')->pluck('service_id')->toArray();
        $freelanceServices = UserService::distinct('service_id')->pluck('service_id')->toArray();
        $serviceIds = array_unique(array_merge($freelanceServices, $salonServices));
        $services = Services::whereIn('id',$serviceIds)->where('name','like','%' . $keyword . '%')->pluck('name');
        return $services;
    }

    public function importData(Request $request){
        // $request->validate([
        //     "csv_file" => "required",
        // ]);
        // $file = $request->file("csv_file");
        // $csvData = file_get_contents($file);
        // $rows = array_map("str_getcsv", explode("\n", $csvData));
        // $header = array_shift($rows);
        // foreach ($rows as $row) {
        //     if (isset($row[0])) {
        //         if ($row[0] != "") {

        //             if(count($header) == count($row)){
        //                 $row = array_combine($header, $row);
        //                 $insertInfo =  array(
        //                     'id' => $row['id'],
        //                     'name' => $row['name'],
        //                     'cover' => $row['cover'],
        //                     'status' => $row['status'],
        //                 );
        //                 $checkLead  =  Services::where("id", "=", $row["id"])->first();
        //                 if (!is_null($checkLead)) {
        //                     DB::table('category')->where("id", "=", $row["id"])->update($insertInfo);
        //                 }
        //                 else {
        //                     DB::table('category')->insert($insertInfo);
        //                 }
        //             }
        //         }
        //     }
        // }
        // $response = [
        //     'data'=>'Done',
        //     'success' => true,
        //     'status' => 200,
        // ];
        // return response()->json($response, 200);
    }
}
