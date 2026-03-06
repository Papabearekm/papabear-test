<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banners;
use App\Models\Category;
use App\Models\User;
use App\Models\Cities;
use App\Models\Individual;
use App\Models\Salon;
use App\Models\ProductCategory;
use App\Models\Products;
use Carbon\Carbon;
use Validator;
use DB;

class BannersController extends Controller
{
    public function save(Request $request){
        $validator = Validator::make($request->all(), [
            'uid' => 'required',
            'cover' => 'required',
            'days' => 'required',
            'position' => 'required',
            'title' => 'required',
            'price' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
                'status'=> 500
            ];
            return response()->json($response, 404);
        }
        $formData['user_id'] = $request->uid;
        $formData['position'] = $request->position;
        $formData['title'] = $request->title;
        $formData['price'] = $request->price;
        $formData['cover'] = $request->cover;
        $formData['link'] = $request->link;
        $formData['from'] = Carbon::now()->format('Y-m-d');
        $formData['to'] = Carbon::now()->addDays($request->days)->format('Y-m-d');
        $individual = Individual::where('uid', $request->uid)->first();
        $salon = Salon::where('uid', $request->uid)->first();
        if($individual)
        {
            $formData['type'] = "1";
            $formData['value'] = $request->uid;
            $formData['lat'] = $individual->lat;
            $formData['lng'] = $individual->lng;
        }
        else if($salon)
        {
            $formData['type'] = "2";
            $formData['value'] = $request->uid;
            $formData['lat'] = $salon->lat;
            $formData['lng'] = $salon->lng;
        }
        else
        {
            $response = [
            'message' => 'invalid uid',
            'status' => 500
            ];
            return response()->json($response, 200);
        }
        $data = Banners::create($formData);
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

        $data = Banners::find($request->id);

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
        $data = Banners::find($request->id)->update($request->all());

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
        $data = Banners::find($request->id);
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


    public function getMoreData(Request $request){
        $response = [
            'categories'=>Category::all(),
            'salons'=>Salon::all(),
            'individual'=>User::where('type','individual')->get(),
            'cities'=>Cities::all(),
            'product_category'=>ProductCategory::all(),
            'products'=>Products::all(),
            'success' => true,
            'status' => 200,
        ];
        // ProductCategory
        // Products
        return response()->json($response, 200);
    }

    public function getAll(){
        $data = Banners::select('position','title','price','lat','lng','cover','from','to')->get();
        // $data = DB::table('banners')
        // ->select('banners.id as id','banners.city_id as city_id','banners.cover as cover','banners.type as type','banners.value as value','banners.title as title',
        // 'banners.from as from','banners.to as to','banners.status as status','banners.extra_field as extra_field','cities.name as city_name')
        // ->join('cities','banners.city_id','cities.id')
        // ->get();
        // foreach($data as $loop){

        //     if($loop->type == 0){
        //         $loop->cateInfo = Category::find($loop->value);
        //     }

        //     if($loop && $loop->type && $loop->type !=null && $loop->type == 1){
        //         $loop->individualInfo = User::where('id',$loop->value)->first();
        //     }

        //     if($loop && $loop->type && $loop->type !=null && $loop->type == 2){
        //         $loop->salonInfo = Salon::where('uid',$loop->value)->first();
        //     }

        //     if($loop && $loop->type && $loop->type !=null && $loop->type == 3){
        //         $loop->categoryInfo = ProductCategory::where('id',$loop->value)->first();
        //     }

        //     if($loop && $loop->type && $loop->type !=null && $loop->type == 4){
        //         $loop->productInfo = Products::where('id',$loop->value)->first();
        //     }

        // }
        $response = [
            'data'=>$data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getAllAdded(Request $request){
        $validator = Validator::make($request->all(), [
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
        $data = Banners::select('id','position','title','price','lat','lng','cover','from','to', 'link')->where('user_id',$request->uid)->get();
        $response = [
            'data'=>$data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }


    public function getInfoById(Request $request){
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


        $data = Banners::find($request->id);

        if (is_null($data)) {
            $response = [
                'success' => false,
                'message' => 'Data not found.',
                'status' => 404
            ];
            return response()->json($response, 404);
        }

        if($data->type == 2 || $data->type == '2'){
            $data['restInfo'] = Stores::select('id','name')->where('id',$data->value)->get();
        }
        if($data->type == 3 || $data->type == '3'){
            $ids = explode(',',$data->value);
            $data['restInfo'] = Stores::select('id','name')->WhereIn('id',$ids)->get();
        }

        $response = [
            'data'=>$data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }
}
