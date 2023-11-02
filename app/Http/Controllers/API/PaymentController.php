<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Bill;
use App\Models\Ticket;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function redirect(OrderRequest $request)
    {
        $ticketsData = $request->validated();

        $bill = Bill::create([
            'amount' => $ticketsData['amount'],
            'ticket_quantity' => $ticketsData['ticket_quantity'],
            'user_id' => Auth::id(),
        ]);

        $tickets = [];
        $fieldCount = count($ticketsData['phone_number']);

        for ($i = 0; $i < $fieldCount; $i++) {
            $ticketData = [
                'phone_number' => $ticketsData['phone_number'][$i],
                'identity' => $ticketsData['identity'][$i],
                'name' => $ticketsData['name'][$i],
                'date_of_birth' => $ticketsData['date_of_birth'][$i],
                'place_of_birth' => $ticketsData['place_of_birth'][$i],
                'nationality' => $ticketsData['nationality'][$i],
                'sex' => $ticketsData['sex'][$i],
                'email' => $ticketsData['email'][$i],
                'address' => $ticketsData['address'][$i],
                'seat_id' => $ticketsData['seat_id'][$i],
                'ferry_trip_id' => $ticketsData['ferry_trip_id'][$i],
                'ferry_id' => $ticketsData['ferry_id'][$i],
            ];

            $ticket = Ticket::create($ticketData);
            $tickets[] = $ticket;

            DB::table('ferry_seat')
                ->where('seat_id', $ticket['seat_id'])
                ->where('ferry_id', $ticket['ferry_id'])
                ->update(['status' => 'book']);
        }

        $vnpUrl = 'http://sandbox.vnpayment.vn/paymentv2/vpcpay.html';

        if (!App::environment('local')) {
            $vnpUrl = 'https://sandbox.vnpayment.vn/merchant_webapi/merchant.html';
        }

        $vnpReturnurl = env('CLIENT_APP_URL') . '/payments/vnpay/callback';
        $vnpTmnCode = config('services.vnpay.vnp_tmn_code');
        $vnpHashSecret = config('services.vnpay.vnp_hash_secret');
        $vnpTxnRef = $bill->id;
        $vnpOrderInfo = 'Online payment';
        $vnpOrderType = 'billpayment';
        $vnpAmount = $bill->amount * 100;
        $vnpLocale = config('app.locale');
        $vnpBankCode = 'NCB';
        $vnpIpAddr = request()->ip();

        $inputData = array(
            'vnp_Version' => '2.1.0',
            'vnp_TmnCode' => $vnpTmnCode,
            'vnp_Amount' => $vnpAmount,
            'vnp_Command' => 'pay',
            'vnp_CreateDate' => date('YmdHis'),
            'vnp_CurrCode' => 'VND',
            'vnp_IpAddr' => $vnpIpAddr,
            'vnp_Locale' => $vnpLocale,
            'vnp_OrderInfo' => $vnpOrderInfo,
            'vnp_OrderType' => $vnpOrderType,
            'vnp_ReturnUrl' => $vnpReturnurl,
            'vnp_TxnRef' => $vnpTxnRef,
        );

        if (isset($vnpBankCode) && $vnpBankCode != '') {
            $inputData['vnp_BankCode'] = $vnpBankCode;
        }

        if (isset($vnpBillState) && $vnpBillState != '') {
            $inputData['vnp_Bill_State'] = $vnpBillState;
        }

        ksort($inputData);
        $query = '';
        $i = 0;
        $hashdata = '';
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . '=' . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . '=' . urlencode($value);
                $i = 1;
            }

            $query .= urlencode($key) . '=' . urlencode($value) . '&';
        }

        $vnpUrl = $vnpUrl . '?' . $query;

        if (isset($vnpHashSecret)) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnpHashSecret);
            $vnpUrl .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        return response()->json([
            'data' => $vnpUrl,
            'tikets' =>  $tickets,
            'message' => __('Generated sucesss')
        ]);
    }

    public function callback()
    {
        $message = 'Transaction success';
        $vnpSecureHash = request('vnp_SecureHash');
        $vnpHashSecret = config('services.vnpay.vnp_hash_secret');

        $inputData = array();
        foreach (request()->query() as $key => $value) {
            if (substr($key, 0, 4) == 'vnp_') {
                $inputData[$key] = $value;
            }
        }

        unset($inputData['vnp_SecureHash']);
        ksort($inputData);
        $i = 0;
        $hashData = '';

        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData = $hashData . '&' . urlencode($key) . '=' . urlencode($value);
            } else {
                $hashData = $hashData . urlencode($key) . '=' . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnpHashSecret);

        if ($secureHash != $vnpSecureHash) {
            return $message = 'Invalid signature!';
        }

        if (request('vnp_ResponseCode') != '00') {
            return $message = 'Transaction failed!';
        }

        $bill = Bill::where('id', request('vnp_TxnRef'))
            ->with('user')
            ->firstOrFail();

        $bill->update([
            'payment_status' => 'Paid'
        ]);

        return response()->json([
            'data' => $message,
            'billInfo' => $bill,
            'message' => __('Transaction success'),
        ]);
    }
}
