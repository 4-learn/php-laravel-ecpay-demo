namespace App\Services;

class ECPayAPI
{
    public $api_url = "https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5";
    public $merchant_id = '3002607';
    public $hash_key = 'pwFHCqoQZGmho4w6';
    public $hash_iv = 'EkRm7iFT261dpevs';

    public function createPaymentForm($orderData)
    {
        $params = [
            'MerchantID' => $this->merchant_id,
            'MerchantTradeNo' => $orderData['trade_no'],
            'MerchantTradeDate' => now()->format('Y/m/d H:i:s'),
            'PaymentType' => 'aio',
            'TotalAmount' => $orderData['amount'],
            'TradeDesc' => $orderData['description'],
            'ItemName' => $orderData['item_name'],
            'ReturnURL' => 'https://www.ecpay.com.tw/return_url.php',
            'ChoosePayment' => 'Credit',
            'EncryptType' => 1,
        ];

        $params['CheckMacValue'] = $this->generateCheckMacValue($params);
        return $params;
    }

    public function generateCheckMacValue($params)
    {
        unset($params['CheckMacValue']);
        ksort($params);
        $query = http_build_query($params);
        $raw = "HashKey={$this->hash_key}&{$query}&HashIV={$this->hash_iv}";

        $encoded = strtolower(urlencode($raw));
        $replaceMap = [
            '%2d' => '-', '%5f' => '_', '%2e' => '.',
            '%21' => '!', '%2a' => '*', '%28' => '(', '%29' => ')'
        ];

        foreach ($replaceMap as $k => $v) {
            $encoded = str_replace($k, $v, $encoded);
        }

        return strtoupper(hash('sha256', $encoded));
    }
}
