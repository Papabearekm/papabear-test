<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class PartnerAdsController extends Controller
{
    public function getAll(){
        $data = DB::table('partner_ads')
        ->select('partner_ads.id as id','partner_ads.cover as cover','partner_ads.title as title','partner_ads.link as link',
        'partner_ads.status as status',)
        ->get();
        $response = [
            'data'=>$data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }
}
