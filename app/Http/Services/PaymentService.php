<?php

namespace App\Http\Services;

// Classes
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

// Models
use App\Models\UserProduct;
use App\Models\Transaction;
use App\Models\Order;
use App\Models\OrderProduct;

class PaymentService {

    private $FatoorahService;
    private $Auth;
    private $address;

    public function __construct(FatoorahService $FatoorahService, Auth $Auth) {
        $this -> FatoorahService = $FatoorahService;
        $this -> Auth = $Auth::user();
    }

    private function insertTransation($InvoiceId) {

        return Transaction::create([
            'InvoiceId' => $InvoiceId,
            'user_id'   => $this -> Auth -> id
        ]);

    }

    private function deleteFromCart() {

        UserProduct::where('user_id', $this -> Auth -> id)
                    -> where('addToCart', 1)
                    -> delete();

    }

    private function UpdateTransaction($InvoiceId) {

        $transaction = Transaction::where('InvoiceId', $InvoiceId)
                    -> where('user_id', $this -> Auth -> id);

        $transaction -> update([
            'paid' => 1
        ]);

        return $transaction -> first();

    }

    private function createOrder($data) {
        return Order::create($data);
    }

    private function createOrderProducts($OrderId) {
        $products = UserProduct::cartProducts($OrderId);
        OrderProduct::insert(collect($products) -> toArray());
    }

    public function cartPayment($request) {

        $this -> address = $request -> address;

        if ($request -> PaymentMethod == 'credit') {
            return $this -> creditPayment();
        }

        return $this -> cashPayment($request);

    }

    public function creditPayment() {

        $InvoiceValue = UserProduct::totalInvoiceValue();

        if ($InvoiceValue) {

            DB::beginTransaction();

            try {

                $RequestData = [
                    'CustomerName'       => $this -> Auth -> name,
                    'InvoiceValue'       => $InvoiceValue,
                    'NotificationOption' => 'LNK',
                    'DisplayCurrencyIso' => 'EGP',
                    'CallBackUrl'        => env('FATORRAH_CALLBACK_URL'),
                    'ErrorUrl'           => env('FATORRAH_ERROR_URL')
                ];

                $PaymentInfo = $this -> FatoorahService -> sendPayment($RequestData);
                $this -> insertTransation($PaymentInfo['Data']['InvoiceId']);

                DB::commit();
                return $PaymentInfo;

            } catch (\Throwable $th) {

                DB::rollBack();
                return response() -> json([
                    'status' => 'error',
                    'error' => $th
                ]);

            }

        }

        return response() -> json([
            'status' => 'error',
            'error' => 'No items found'
        ], 404);

    }

    public function cashPayment($request) {

        DB::beginTransaction();

        try {

            $InvoiceValue = UserProduct::totalInvoiceValue();

            $data = [
                'transaction_id'    => null,
                'user_id'           => $this -> Auth -> id,
                'paid'              => 0,
                'payment'           => 'cash',
                'address'           => $this -> address,
                'InvoiceValue'      => $InvoiceValue
            ];

            $order = $this -> createOrder($data);
                    $this -> createOrderProducts($order -> id);
                    $this -> deleteFromCart();

            DB::commit();
            return response() -> json([
                'status' => 'success'
            ]);

        } catch (\Throwable $th) {
            DB::rollBack();
            return response() -> json([
                'status' => 'error',
                'error' => $th
            ]);
        }

    }

    public function paymentCallbackSuccess($PaymentId) {

        $RequestData = [
            'Key' => $PaymentId,
            'KeyType' => 'paymentId'
        ];

        $PaymentStatus = $this -> FatoorahService -> getPaymentStatus($RequestData);
        $transaction = $this -> UpdateTransaction($PaymentStatus['Data']['InvoiceId']);
        if ($PaymentStatus['IsSuccess'] && $transaction) {

            DB::beginTransaction();

            try {

                $InvoiceValue = UserProduct::totalInvoiceValue();

                $data = [
                    'transaction_id'    => $transaction -> id,
                    'user_id'           => $this -> Auth -> id,
                    'paid'              => 1,
                    'payment'           => 'credit',
                    'address'           => $this -> address,
                    'InvoiceValue'      => $InvoiceValue
                ];

                $order = $this -> createOrder($data);
                        $this -> createOrderProducts($order -> id);
                        $this -> deleteFromCart();

                DB::commit();
                return $PaymentStatus;

            } catch (\Throwable $th) {
                DB::rollBack();
                return response() -> json([
                    'status' => 'error',
                    'error' => $th
                ]);
            }

        }

        return $PaymentStatus;

    }

    // public function paymentCallbackFaild() {
    // }

}