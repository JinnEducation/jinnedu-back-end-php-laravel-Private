<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SliderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
         $translation = $this->whenLoaded('langs', function () {
            return optional($this->langs->first());
        });

       return [
            'id' => $this->id,
            'title'         => $translation?->title,
            'sub_title'         => $translation?->sub_title,
            'btn_name'         => $translation?->btn_name,
            'image' => $this->image_url,
            'btn_url' => $this->btn_url,
        ];
    }
}
