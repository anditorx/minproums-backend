<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
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
}
