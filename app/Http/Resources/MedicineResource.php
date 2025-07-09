<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicineResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
            return [
                'id' => $this->id,
                'name' => $this->name,
                'description' => $this->description,
                'company' => $this->company,
                'price' => $this->price,
                'quantity' => $this->quantity,
                'expiry_date' => $this->expiry_date,
                'category' => $this->whenLoaded('category', fn () => $this->category->name),
                'created_by' => $this->whenLoaded('creator', fn () => $this->creator->username),
            ];
    }
}
