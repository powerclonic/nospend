<?php

namespace App\Http\Controllers;

use App\Http\Requests\Expense\ListRequest;
use App\Http\Requests\Expense\StoreRequest;
use App\Http\Resources\DashboardResource;
use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ListRequest $request)
    {
        if ($request->validated()) {
            return ExpenseResource::collection(
                $request->user()->expensesMonth(...$request->validated())->get()
            );
        } else {
            return new DashboardResource(
                $request->user()
                    ->with('expenses')
                    ->first()
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $request->user()->expenses()->create($request->validated());

        return response(__('expense.created'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        return new ExpenseResource($expense);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Expense $expense)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        //
    }
}
