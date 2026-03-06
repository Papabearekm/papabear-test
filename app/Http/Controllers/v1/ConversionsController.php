<?php

namespace App\Http\Controllers\v1;

use App\Events\ChatEvent;
use App\Http\Controllers\Controller;
use App\Models\ChatRooms;
use Illuminate\Http\Request;
use App\Models\Conversions;
use Validator;

class ConversionsController extends Controller
{
    public function save(Request $request){
        $validator = Validator::make($request->all(), [
            'room_id'=>'required',
            'sender_id' => 'required',
            'message_type' => 'required',
            'message' => 'required',
            'status'=>'required'
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
                'status'=> 500
            ];
            return response()->json($response, 404);
        }

        $data = Conversions::create($request->all());
        if (is_null($data)) {
            $response = [
            'data'=>$data,
            'message' => 'error',
            'status' => 500,
           ];
            return response()->json($response, 200);
        }
        $room = ChatRooms::find($request->room_id);
        $sender_id   = (int) $request->sender_id;
        $reciever_id = (int) ($room->receiver_id == $request->sender_id
                                ? $room->sender_id
                                : $room->receiver_id);
        event(new ChatEvent($sender_id, $reciever_id));
        $response = [
            'data'=>$data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }

    public function getById(Request $request){
        $validator = Validator::make($request->all(), [
            'room_id' => 'required',
        ]);
        if ($validator->fails()) {
            $response = [
                'success' => false,
                'message' => 'Validation Error.', $validator->errors(),
                'status'=> 500
            ];
            return response()->json($response, 404);
        }

        $data = Conversions::where('room_id',$request->room_id)->get();

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
}
