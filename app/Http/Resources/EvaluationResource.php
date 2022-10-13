<?php

namespace App\Http\Resources;

use App\Http\Resources\OrderResource;
use App\Http\Resources\ClientResource;
use Illuminate\Http\Resources\Json\JsonResource;

class EvaluationResource extends JsonResource
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
            'stars' => $this->stars,
            'comment' => $this->comment,
            'client' => new ClientResource($this->client),
            'order' => new OrderResource($this->order),
        ];
    }
}
