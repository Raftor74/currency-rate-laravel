<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CurrencyResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'char_code' => $this->char_code,
            'name' => $this->name,
            'rate' => $this->rate,
            'relevant_on' => $this->relevant_on,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
