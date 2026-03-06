<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Models\Appointments;
use App\Models\Individual;
use App\Models\Notification;
use App\Models\ProductOrders;
use App\Models\Salon;
use App\Models\User;
use App\Models\Withdrawal;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;

class WithdrawalController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uid' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validation Error ' . $validator->errors(), 'status' => 500], 404);
        }

        $user = User::find($request->uid);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found with this uid', 'status' => 500], 404);
        }
        $withdrawals = Withdrawal::where('uid', $request->uid)->get();
        foreach ($withdrawals as $withdrawal) {
            $withdrawal->withdrawal_date = $withdrawal->withdrawal_date ?? Carbon::parse($withdrawal->created_at)->format('Y-m-d');
        }
        $totalAmount = (float) $user->withdrawal_balance ?? 0;
        $CODCommission = (float) $user->cod_balance ?? 0;
        return response()->json(['success' => true, 'withdrawals' => $withdrawals, 'totalAmount' => $totalAmount, 'CODCommission' => $CODCommission], 200);
    }

    public function filter(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uid' => 'required',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validation Error ' . $validator->errors()->first(), 'status' => 500], 404);
        }

        $user = User::find($request->uid);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found with this uid', 'status' => 500], 404);
        }
        $start = Carbon::parse($request->start_date)->startOfDay();
        $end = Carbon::parse($request->end_date)->endOfDay();
        $withdrawals = Withdrawal::where('uid', $request->uid)
            ->whereBetween('created_at', [$start, $end])
            ->get();
        foreach ($withdrawals as $withdrawal) {
            $withdrawal->withdrawal_date = $withdrawal->withdrawal_date ?? Carbon::parse($withdrawal->created_at)->format('Y-m-d');
        }
        return response()->json(['success' => true, 'withdrawals' => $withdrawals], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'uid' => 'required',
            'amount' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validation Error ' . $validator->errors(), 'status' => 500], 404);
        }

        $user = User::find($request->uid);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found with this uid', 'status' => 500], 404);
        }
        $recievedAmount = (float) $request->amount;
        $totalAmount = (float) $user->withdrawal_balance ?? 0;
        if ($recievedAmount > $totalAmount) {
            return response()->json(['success' => false, 'message' => 'Withdrawal amount cannot be greater than total amount', 'status' => 500], 500);
        }
        $formData = $request->all();
        $formData['withdrawal_date'] = Carbon::now()->format('Y-m-d');
        $formData['cod_commission'] = 0;
        $formData['paid_cod_commission'] = 0;
        $data = Withdrawal::create($formData);
        $user->withdrawal_balance = $totalAmount - $recievedAmount;
        $user->cod_balance = 0;
        $user->save();
        if (is_null($data)) {
            $response = [
                'data' => $data,
                'message' => 'error',
                'status' => 500,
            ];
        }
        try {
            if ($user && $user->fcm_token) {
                $firebase = (new Factory)->withServiceAccount(public_path('uploads/firebase/papabear-8b9f9-firebase-adminsdk-nphrh-304edd4d1b.json'));
                $messaging = $firebase->createMessaging();
                $message = CloudMessage::fromArray([
                    'token' => $user->fcm_token,
                    'notification' => [
                        'title' => 'Withdrawal initiated',
                        'body' => 'Your withdrawal is processing...',
                    ],
                ]);
                $messaging->send($message);
            }
        } catch (Exception $e) {
            $user->fcm_token = null;
            $user->save();
            Log::error($e->getMessage());
        }
        $response = [
            'data' => $data,
            'success' => true,
            'status' => 200,
        ];
        return response()->json($response, 200);
    }
}
