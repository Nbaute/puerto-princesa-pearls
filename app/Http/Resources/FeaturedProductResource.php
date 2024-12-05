<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeaturedProductResource extends JsonResource
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
            'shop' => new ShopResource($this->shop),
            'description' => $this->description,
            'rating' => $this->rating,
            'price' => $this->price,
            'image_url' => $this->image_url,
            'tags' => $this->tags->map(fn($t) => $t->name)
        ];
        return parent::toArray($request);
    }
}
