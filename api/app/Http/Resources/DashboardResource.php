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
                'expenses_total_not_recurrent' => $expensesMonth->where('recurrent', false)->sum('value')
            ]
        ];
    }
}
