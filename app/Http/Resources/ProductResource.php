<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'title' => '14 Karat Gold Pearl Earrings',
            'shop' => [
                'name' => 'Hannies',
                'username' => '@hannies'
            ],
            'is_new' => true,
            'rating' => 5,
            'price' => 6800,
            'image_url' => '/images/static/sample-earring.jpg',
            'tags' => ['Earrings', 'Bracelets']
        ];
        return parent::toArray($request);
    }
}
