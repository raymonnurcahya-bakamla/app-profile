<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $value = $this->value;
        if ($this->type === 'image' && $value) {
            $value = str_starts_with($value, 'http') ? $value : asset('storage/'.$value);
        }

        return [
            'group' => $this->group,
            'key' => $this->key,
            'value' => $value,
            'type' => $this->type,
        ];
    }
}
