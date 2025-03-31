<?php

namespace App\Http\Resources;

use App\Enums\Status;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DashboardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $expensesMonth = $this->expensesMonth(today()->month, today()->year)->get();

        return [
            'name' => explode(' ', $this->name)[0],
            'today_expenses' => ExpenseResource::collection($this->expensesToday()->get()->slice(0, 3)->all()),
            'month_statistics' => [
                'expenses_quantity' => $expensesMonth->count(),
                'expenses_total_value' => $expensesMonth->sum('value'),
                'expenses_total_paid' => $expensesMonth->where('status', Status::PAID)->sum('value'),
                'expenses_total_unpaid' => $expensesMonth->where('status', '!=', Status::PAID)->sum('value'),
                'expenses_total_not_recurrent' => $expensesMonth->where('recurrent', false)->sum('value'),
                'expenses_total_recurrent' => $expensesMonth->where('recurrent', true)->sum('value'),
            ],
            'expenses_by_category' => $expensesMonth->groupBy('category')->map(function ($expenses, $category) {
                return [
                    'category' => $category ?: __('app.expense.no_category'),
                    'total_value' => $expenses->sum('value'),
                ];
            })->values(),
            'expenses_by_payment_source' => $expensesMonth->groupBy('payment_source')->map(function ($expenses, $source) {
                return [
                    'payment_source' => $source ?: __('app.expense.no_payment_source'),
                    'total_value' => $expenses->sum('value'),
                ];
            })->values(),
            'expenses_by_payment_method' => $expensesMonth->groupBy('payment_method')->map(function ($expenses, $paymentMethod) {
                return [
                    'payment_method' => $paymentMethod ?: __('app.expense.no_payment_method'),
                    'total_value' => $expenses->sum('value'),
                ];
            })->values(),
        ];
    }
}
