<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>綠界 API 測試</title>
</head>
<body>
    <h2>訂單編號：{{ $trade_no }}</h2>
    <form method="post" action="https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5">
        @foreach ($params as $key => $value)
            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach
        <input type="submit" value="送出測試訂單">
    </form>
</body>
</html>
