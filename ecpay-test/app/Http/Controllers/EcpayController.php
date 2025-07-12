<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class EcpayController extends Controller
{
    public function checkout()
    {
        $merchant_id = '3002607';
        $hash_key = 'pwFHCqoQZGmho4w6';
        $hash_iv = 'EkRm7iFT261dpevs';
        $trade_no = 'TEST' . time();
        $trade_date = now()->format('Y/m/d H:i:s');

        $params = [
            'MerchantID' => '3002607',
            'MerchantTradeNo' => $trade_no,
            'MerchantTradeDate' => $trade_date,
            'PaymentType' => 'aio',
            'TotalAmount' => '100',
            'TradeDesc' => '測試商品',
            'ItemName' => '測試商品 XD x 1',
            'ReturnURL' => 'https://www.ecpay.com.tw/return_url.php',
            'ClientBackURL' => 'https://www.ecpay.com.tw',
            'NeedExtraPaidInfo' => 'N',
            'EncryptType' => 1,
            'ChoosePayment' => 'Credit'
        ];


        $params['CheckMacValue'] = $this->generateCheckMacValue($params, $hash_key, $hash_iv);
        # $params['CheckMacValue'] = substr($params['CheckMacValue'], 0, -1) . 'X';


        return view('ecpay.checkout', [
            'params' => $params,
            'trade_no' => $trade_no
        ]);
    }

    private function generateCheckMacValue($params, $HashKey, $HashIV)
    {
        unset($params['CheckMacValue']);
        ksort($params);
        $encoded = "HashKey={$HashKey}";
        foreach ($params as $k => $v) {
            $encoded .= "&{$k}={$v}";
        }
        $encoded .= "&HashIV={$HashIV}";
        $encoded = urlencode($encoded);
        $encoded = strtolower($encoded);
        $search = ['%2d','%5f','%2e','%21','%2a','%28','%29'];
        $replace = ['-','_','.','!','*','(',')'];
        $encoded = str_replace($search, $replace, $encoded);
        return strtoupper(hash('sha256', $encoded));
    }
}
