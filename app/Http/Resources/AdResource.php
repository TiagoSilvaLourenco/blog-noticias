<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'positions' => $this->positions->pluck('code'),
            'image'     => $this->image,
            'url'       => $this->link,
            'width'     => $this->positions->first()?->width,   // supondo que Position tem width/height
            'height'    => $this->positions->first()?->height,
        ];
    }
}
