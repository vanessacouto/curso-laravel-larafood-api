<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            //'tenant_id' => $this->tenant_id,
            'title' => $this->title,
            'identify' => $this->uuid,
            'image' => url("storage/{$this->image}"),
            'price' => $this->price,
            'description' => $this->description,
        ];
    }
}
