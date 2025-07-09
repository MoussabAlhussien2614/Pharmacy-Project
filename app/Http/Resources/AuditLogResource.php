<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuditLogResource extends JsonResource
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
            'user' => new UserResource($this->user),
            'action' => $this->action,
            'model_type' => $this->model_type,
            'model_id' => $this->model_id,
            'changes' => $this->changes,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
