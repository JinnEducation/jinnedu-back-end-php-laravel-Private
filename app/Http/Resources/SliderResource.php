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

        // Ensure all languages are included in the mapped result and properly grouped by language_id
        $languages = $this->langsAll->mapWithKeys(function ($language) {
            return [
                $language->language_id => [
                    'id' => $language->id,
                    'language_id' => $language->language_id,
                    'shortname' => $language->language->shortname ?? null,
                    'title' => $language->title,
                    'sub_title' => $language->sub_title,
                    'btn_name' => $language->btn_name,
                ]
            ];
        });

        // Rather than pluck, build the keys for each field by language_id for all present languages
        return [
            'id' => $this->id,
            'title' => $languages->mapWithKeys(fn($lang, $id) => [$id => $lang['title']]),
            'sub_title' => $languages->mapWithKeys(fn($lang, $id) => [$id => $lang['sub_title']]),
            'btn_name' => $languages->mapWithKeys(fn($lang, $id) => [$id => $lang['btn_name']]),
            'image' => $this->image_url,
            'btn_url' => $this->date,
        ];
    }
}
