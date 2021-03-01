<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit', 10);
        $user_id = $request->input('user_id');

        if($id){
            $transaction = Transaction::with(['product','user'])->find($id);

            if($transaction){
                return ResponseFormatter::success(
                    $transaction,
                    'Successfully get data'
                );
            }else{
                return ResponseFormatter::error(
                    null,
                    'Data not found',
                    404
                );
            }
        }

        $transaction = Transaction::with(['product','user'])->where('user_id', Auth::user()->id);
        if ($user_id) {
            $transaction->where('user_id', $user_id);
        }

        return ResponseFormatter::success(
            $transaction->paginate($limit),
            'Successfully get data'
        );

    }

    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        $transaction->update($request->all());
        return ResponseFormatter::success($transaction, 'Please Chekout');
    }

     public function getItemCart(Request $request)
     {
         $data = Product::join('transaction_items', 'products.code', '=', 'transaction_items.product_code')->where('transaction_items.invoice', $request->invoice)
         ->get(['products.*', 'transaction_items.invoice', 'transaction_items.qty']);
         if ($data) {
             return ResponseFormatter::success($data, 'Sukses');
         } else {
           return ResponseFormatter::error([
                'message' => 'Not found',
            ], 'Not found', 404);
         }
    }

    public function addToCart(Request $request)
    {
        // It not exists
        if (!Transaction::where('invoice', $request->invoice)->first()) {
            Transaction::create([
                'user_id'       => $request->user_id,
                'invoice'       => $request->invoice,
                'product_price' => $request->product_price,
                'total'         => $request->total,
                'status'        => $request->status,
            ]);
            TransactionItem::create([
                'invoice'       => $request->invoice,
                'product_code'  => $request->product_code,
                'qty'           => $request->qty,
            ]);
            return ResponseFormatter::success('', 'Menambahkan ke keranjang');
        }else{

            $transaction = Transaction::where('invoice', $request->invoice)->first();
            $transaction->total = $transaction->total+$request->total;
            $transaction->update();

            if (!TransactionItem::where('invoice', $request->invoice)->where('product_code', $request->product_code)->first()) 
            {
                TransactionItem::create([
                    'invoice'       => $request->invoice,
                    'product_code'  => $request->product_code,
                    'qty'           => $request->qty,
                ]);
                return ResponseFormatter::success('', 'Menambahkan ke keranjang');
            }else{
                $transactionItem = TransactionItem::where('invoice', $request->invoice)->where('product_code', $request->product_code)->first();
                $transactionItem->qty = $transactionItem->qty+$request->qty;
                $transactionItem->update();
            }

            return ResponseFormatter::success('', 'Menambahkan ke keranjang');
        }
    }

    public function updateAddQty(Request $request)
    {
        // dd($request);
        $num = 1;
        $transactionItem = TransactionItem::where('invoice', $request->invoice)->where('product_code', $request->code)->first();
        $transactionItem->qty = $transactionItem->qty + $num;
        $transactionItem->update();

        $transaction = Transaction::where('invoice', $request->invoice)->first();
        $transaction->total = $transaction->total+$request->price;
        $transaction->update();

        return ResponseFormatter::success('', 'Menambahkan ke qty');
        
    }
    public function updateMinusQty(Request $request)
    {
        $num = 1;
        $transactionItem = TransactionItem::where('invoice', $request->invoice)->where('product_code', $request->code)->first();
        $transactionItem->qty = $transactionItem->qty - $num;
        $transactionItem->update();

        $transaction = Transaction::where('invoice', $request->invoice)->first();
        $transaction->total = $transaction->total-$request->price;
        $transaction->update();

        return ResponseFormatter::success('', 'Mengurangi ke qty');
        
    }
    public function updateStatusPayment(Request $request)
    {
        $transaction = Transaction::where('invoice', $request->invoice)->first();
        $transaction->status = 'PAYMENT_PROCCESS';
        $transaction->update();

        return ResponseFormatter::success('', 'Sukses');
        
    }

    public function getTotalPrice(Request $request)
    {
        $transaction = Transaction::where('invoice', $request->invoice)->first();
        return ResponseFormatter::success($transaction, 'Sukses');
    }
    public function deletItemOnCart(Request $request)
    {
        $transactionItem = TransactionItem::where('invoice', $request->invoice)->where('product_code', $request->code)->first();
        $transactionItem->delete();
        return ResponseFormatter::success('', 'Item di hapus dari keranjang');
    }

    public function updateStatusPaymentConfirm(Request $request)
    {
        $transaction = Transaction::where('invoice', $request->invoice)->first();
        $transaction->status = 'PAID';
        $transaction->update();

        return ResponseFormatter::success('', 'Sukses');
        
    }

}
