<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use App\Models\Salon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AgentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function registerSalon(Request $request)
    {
        $validator = Validator::make($request->all(), 
        [
            'email' => 'required',
            'first_name'=>'required',
            'last_name'=>'required',
            'mobile'=>'required',
            'country_code'=>'required',
            'password' => 'required',
            'gender' => 'required',
            'cover' => 'required',
            'name' => 'required',
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
            'agent_id' => 'required'
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
                'status'=> 500
            ];
            return response()->json($response, 500);
        }
        $emailValidation = User::where('email',$request->email)->first();
        if (is_null($emailValidation) || !$emailValidation) {

            $matchThese = ['country_code' => $request->country_code, 'mobile' => $request->mobile];
            $data = User::where($matchThese)->first();
            if (is_null($data) || !$data) {
                $user = User::create([
                    'email' => $request->email,
                    'first_name'=>$request->first_name,
                    'last_name'=>$request->last_name,
                    'type'=>'salon',
                    'status'=>1,
                    'mobile'=>$request->mobile,
                    'cover'=>$request->cover,
                    'country_code'=>$request->country_code,
                    'gender'=>$request->gender,
                    'password' => Hash::make($request->password),
                ]);
                $salonData = $request->except('email','first_name','last_name','type','status','mobile','country_code','gender','password');
                $salonData['uid'] = $user->id;
                $salonData['agent_id'] = $request->executive_id;
                unset($salonData['executive_id']);
                
                $data = Salon::create($salonData);
                if (is_null($data)) {
                    $response = [
                        'data' => $data,
                        'message' => 'error',
                        'status' => 500,
                    ];
                    return response()->json($response, 200);
                }
                Commission::create([
                    'uid' => $user->id,
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
            $response = [
                'success' => false,
                'message' => 'Mobile is already registered.',
                'status' => 500
            ];
            return response()->json($response, 500);
        }
        $response = [
            'success' => false,
            'message' => 'Email is already taken',
            'status' => 500
        ];
        return response()->json($response, 500);
    }
}
