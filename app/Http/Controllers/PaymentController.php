<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PayPal\Api\Amount;
use PayPal\Api\Details;
// use PayPal\Api\Payment;
use App\Models\Payment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Payer;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaymentController extends Controller
{
    private $apiContext;

    public function paypal(Request $request) {

        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->createOrder([
            "intent"=> "CAPTURE",
            "application_context" => [
                "return_url" => route('success'),
                "cancel_url" => route('cancel')
            ],
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $request->price
                    ]
                ]
            ]
        ]);

        if(isset($response['id']) && $response['id'] != null) {
            foreach($response['links'] as $link) {
                if($link['rel'] == 'approve') {
                    session()->put('product_name', $request->product_name);
                    session()->put('quantity', $request->quantity);
                    return redirect()->away($link['href']);
                }
            }
        } else {
            return redirect()->route('cancel');
        }

        // dd($response);
        
    }

    public function success(Request $request){

         $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request->token);
        //  dd($response);
        if(isset($response['status']) && $response['status'] == 'COMPLETED') {

            // Insert data into database
            $payment = new Payment;
            $payment->payment_id = $response['id'];
            $payment->product_name = session()->get('product_name');
            $payment->quantity = session()->get('quantity');
            $payment->amount = $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'];
            $payment->currency = $response['purchase_units'][0]['payments']['captures'][0]['amount']['currency_code'];
            $payment->payer_name = $response['payer']['name']['given_name'];
            $payment->payer_email = $response['payer']['email_address'];
            $payment->payment_tatus = $response['status'];
            $payment->payment_method = "Paypal";
            $payment->save();

            return "Payment is successful";

            unset($_SESSION['product_name']);
            unset($_SESSION['quantity']);
        } else {
            return redirect()->route('cancel');
        }
        // dd($response);
    }

    public function cancel(){
        return 'Payment cancel';
    }

    // public function __construct()
    // {
    //     $this->apiContext = new ApiContext(
    //         new OAuthTokenCredential(
    //             env('PAYPAL_CLIENT_ID'),
    //             env('PAYPAL_SECRET')
    //         )
    //     );
    //     $this->apiContext->setConfig([
    //         'mode' => env('PAYPAL_MODE', 'sandbox')  // Use 'sandbox' for testing
    //     ]);
    // }

    // public function createPayment(Request $request)
    // {
    //     $payer = new Payer();
    //     $payer->setPaymentMethod("paypal");  // Use "paypal" for PayPal payments or "credit_card" for direct credit card payments

    //     // Amount details
    //     $amount = new Amount();
    //     $amount->setTotal($request->input('amount'))
    //            ->setCurrency("USD");

    //     $transaction = new Transaction();
    //     $transaction->setAmount($amount)
    //                 ->setDescription("Payment for products/services");

    //     // Redirect URLs
    //     $redirectUrls = new RedirectUrls();
    //     $redirectUrls->setReturnUrl(route('payment.success'))
    //                  ->setCancelUrl(route('payment.cancel'));

    //     // Create payment
    //     $payment = new Payment();
    //     $payment->setIntent("sale")
    //             ->setPayer($payer)
    //             ->setTransactions([$transaction])
    //             ->setRedirectUrls($redirectUrls);

    //     try {
    //         $payment->create($this->apiContext);
    //     } catch (\Exception $ex) {
    //         return redirect()->route('payment.cancel');
    //     }

    //     return redirect()->away($payment->getApprovalLink());
    // }

    // public function paymentSuccess(Request $request)
    // {
    //     $paymentId = $request->input('paymentId');
    //     $payerID = $request->input('PayerID');

    //     $payment = Payment::get($paymentId, $this->apiContext);

    //     $execution = new PaymentExecution();
    //     $execution->setPayerId($payerID);

    //     try {
    //         $result = $payment->execute($execution, $this->apiContext);
    //         return redirect()->route('payment.success')->with('success', 'Payment completed successfully!');
    //     } catch (\Exception $ex) {
    //         return redirect()->route('payment.cancel');
    //     }
    // }

    // public function paymentCancel()
    // {
    //     return "Payment was canceled.";
    // }

    // public function createCreditCardPayment(Request $request)
    // {
    //     $payer = new Payer();
    //     $payer->setPaymentMethod("credit_card");

    //     $creditCard = new \PayPal\Api\CreditCard();
    //     $creditCard->setType($request->input('card_type'))
    //             ->setNumber($request->input('card_number'))
    //             ->setExpireMonth($request->input('expire_month'))
    //             ->setExpireYear($request->input('expire_year'))
    //             ->setCvv2($request->input('cvv'))
    //             ->setFirstName($request->input('first_name'))
    //             ->setLastName($request->input('last_name'));

    //     $payer->setFundingInstruments([['credit_card' => $creditCard]]);

    //     $amount = new Amount();
    //     $amount->setTotal($request->input('amount'))
    //         ->setCurrency('USD');

    //     $transaction = new Transaction();
    //     $transaction->setAmount($amount)
    //                 ->setDescription('Payment for products/services');

    //     $redirectUrls = new RedirectUrls();
    //     $redirectUrls->setReturnUrl(route('payment.success'))
    //                 ->setCancelUrl(route('payment.cancel'));

    //     $payment = new Payment();
    //     $payment->setIntent("sale")
    //             ->setPayer($payer)
    //             ->setTransactions([$transaction])
    //             ->setRedirectUrls($redirectUrls);

    //     try {
    //         $payment->create($this->apiContext);
    //     } catch (\Exception $ex) {
    //         return redirect()->route('payment.cancel');
    //     }

    //     return redirect()->away($payment->getApprovalLink());
    // }

}