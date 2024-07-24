<?php

namespace App\Http\Controllers;

use App\Http\Requests\Expense\ListRequest;
use App\Http\Requests\Expense\StoreRequest;
use App\Http\Requests\Expense\UpdateRequest;
use App\Http\Resources\DashboardResource;
use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ListRequest $request)
    {
        info($request->user());

        if ($request->validated()) {
            return ExpenseResource::collection(
                $request->user()->expensesMonth(...$request->validated())->get()
            );
        } else {
            return new DashboardResource(
                $request->user()
            );
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $request->user()->expenses()->create($request->validated());

        return response(__('app.expense.created'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Expense $expense)
    {
        Gate::authorize('view', $expense);

        return new ExpenseResource($expense);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Expense $expense)
    {
        Gate::authorize('update', $expense);

        $expense->update($request->validated());

        return response(__('app.expense.updated'), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Expense $expense)
    {
        Gate::authorize('delete', $expense);

        $expense->delete();

        return response(__('app.expense.deleted'), 200);
    }
}
