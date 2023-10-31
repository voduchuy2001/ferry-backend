<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class PaymentController extends Controller
{
    public function redirect(Request $request)
    {
        $vnpUrl = 'http://sandbox.vnpayment.vn/paymentv2/vpcpay.html';

        if (!App::environment('local')) {
            $vnpUrl = 'https://sandbox.vnpayment.vn/merchant_webapi/merchant.html';
        }

        $vnpReturnurl = env('APP_URL') . '/payments/vnpay/callback';
        $vnpTmnCode = config('services.vnpay.vnp_tmn_code');
        $vnpHashSecret = config('services.vnpay.vnp_hash_secret');
        $vnpTxnRef = rand(1, 1000000);
        $vnpOrderInfo = 'Online payment';
        $vnpOrderType = 'billpayment';
        $vnpAmount = 100000 * 100;
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
            'message' => __('Generated sucesss')
        ]);
    }

    public function callback(Request $request)
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

        return response()->json([
            'data' => $message,
            'message' => __('Transaction success'),
        ]);
    }
}
