<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatBlockedWordResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // Rather than pluck, build the keys for each field by language_id for all present languages
        return [
            'id' => $this->id,
            'word' => $this->word,
            'is_active' => $this->is_active,
        ];
    }
}
