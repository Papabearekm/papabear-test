<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Facilities;
use App\Models\Individual;
use App\Models\Salon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FacilitiesController extends Controller
{
    public function getAll(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uid' => 'required'
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
        $data = Facilities::where('status','Active')->select('id','name')->get();
        if (is_null($data)) {
            $response = [
                'success' => false,
                'message' => 'Data not found.',
                'status' => 404
            ];
            return response()->json($response, 404);
        }
        $selectedFacilities = null;
        $salon = Salon::where('uid', $request->uid)->first();
        if($salon)
        {
            if($salon->facilities)
            {
                $selectedFacilities = explode(",",$salon->facilities);
            }
        }
        $individual = Individual::where('uid',$request->uid)->first();
        if($individual)
        {
            if($individual->facilities)
            {
                $selectedFacilities = explode(",",$individual->facilities);
            }
        }
        foreach($data as &$facility)
        {
            $facility['status'] = 0;
            if($selectedFacilities)
            {
                if(in_array($facility['id'], $selectedFacilities))
                {
                    $facility['status'] = 1;
                }
            }
        }

        $response = [
            'data'=>$data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function index() {
        $data = Facilities::select('id','name')->get();

        $response = [
            'data'=>$data,
            'success' => true,
            'status' => 200
        ];
        return response()->json($response, 200);
    }
}
