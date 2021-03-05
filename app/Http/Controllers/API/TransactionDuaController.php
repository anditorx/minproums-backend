<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\DuaItemCart;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionDuaController extends Controller
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

    public function addToCart(Request $request)
    {
        DuaItemCart::create([
                'product_code'       => $request->product_code,
                'user_email'       => $request->user_email,
                'product_price' => $request->product_price,
                'total'         => $request->total,
                'qty'         => $request->qty,
                'status'        => 'ADD_TO_CART',
        ]);
        return ResponseFormatter::success('', 'Menambahkan ke keranjang');
    }
    public function getCart(Request $request)
    {
        // dd($request);
        $data = Product::join('dua_item_carts', 'products.code', '=', 'dua_item_carts.product_code')
        ->where('dua_item_carts.status', 'ADD_TO_CART')
        ->where('dua_item_carts.user_email', $request->email)
         ->get(['dua_item_carts.*','products.name','products.picture_path','products.description']);
        return ResponseFormatter::success($data, 'Sukses');
    }

    public function updateCart(Request $request)
    {
        
        $data = DuaItemCart::where('user_email', $request->user_email)->where('status', 'ADD_TO_CART')->get();
        dd($data);
    }
}
