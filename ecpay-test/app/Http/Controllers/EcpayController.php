namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Services\ECPayAPI;

class EcpayController extends Controller
{
    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|integer',
            'description' => 'required|string',
            'item_name' => 'required|string',
        ]);

        $validated['trade_no'] = 'ORDER' . time();

        $ecpay = new ECPayAPI();
        $formParams = $ecpay->createPaymentForm($validated);

        $response = Http::asForm()->post($ecpay->api_url, $formParams);

        return response($response->body(), 200)->header('Content-Type', 'text/html');
    }
}
