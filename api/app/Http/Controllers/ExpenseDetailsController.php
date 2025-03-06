<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ExpenseDetailsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        if (Cache::has('expense_details_' . $request->user()->id)) {
            return response()->json(Cache::get('expense_details_' . $request->user()->id));
        }

        $expenses = $request->user()
            ->expenses()
            ->get(['category', 'payment_source', 'payment_method']);

        $detailValues = [
            'category' => $expenses->pluck('category')->unique()->values(),
            'payment_source' => $expenses->pluck('payment_source')->unique()->values(),
            'payment_method' =>  $expenses->pluck('payment_method')->unique()->values(),
        ];

        Cache::put('expense_details_' . $request->user()->id, $detailValues);

        return response()->json($detailValues);
    }
}
