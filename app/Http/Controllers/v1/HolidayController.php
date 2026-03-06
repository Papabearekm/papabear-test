<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HolidayController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'uid' => 'required',
            'date' => 'required|date|date_format:Y-m-d',
            'status' => 'required'
        ]);

        if($validator->fails())
        {
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
                'status'=> 500
            ];
            return response()->json($response, 404);
        }

        $user = User::find($request->uid);
        if($user){
            if(!in_array($user->type,['salon','freelancer']))
            {
                return response()->json(['success' => false, 'message' => 'User is not salon or freelancer']);
            }
        }
        else
        {
            return response()->json(['success' => false, 'message' => 'User not found']);
        }
        if($request->status == "1")
        {
            $data = Holiday::create($request->all());
        }
        else
        {
            Holiday::where('uid',$request->uid)->where('date',$request->date)->delete();
            $response = [
                'success' => true,
                'status' => 200,
            ];
            return response()->json($response, 200);
        }
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

    public function index(Request $request)
    {
        if($request->uid)
        {
            $data = Holiday::where('uid', $request->uid)->pluck('date');
            if(count($data) == 0)
            {
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
        else
        {
            $response = [
                'success' => false,
                'message' => 'Validation Error. uid is required',
                'status'=> 500
            ];
            return response()->json($response, 404);
        }
    }
}
