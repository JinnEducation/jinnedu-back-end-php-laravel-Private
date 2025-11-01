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
       return [
            'data' => [
                'id' => $this->id,
                'images' => $this->images ?? [],
    
                'relations' => [
                    'langs' => $this->langs->map(fn($lang) => [
                        'id' => $lang->id,
                        'language_id' => $lang->language_id,
                        'language_name' => $lang->language->name ?? null,
                        'title' => $lang->title,
                        'sub_title' => $lang->sub_title,
                        'btn_name' => $lang->btn_name,
                
                    ]),
                ],
            ],
        ];
    }
}
