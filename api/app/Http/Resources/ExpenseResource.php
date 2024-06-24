<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExpenseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "name" => $this->name,
            "value" => $this->value,
            "status" => $this->status->name,
            "due_date" => $this->due_date->format('d/m/Y'),
            "payment_method" => $this->payment_method,
            "payment_source" => $this->payment_source,
            "created_at" => $this->created_at->format('d/m/Y'),
            "recurrent" => boolval($this->recurrent),
            "category" => $this->category,
        ];
    }
}
