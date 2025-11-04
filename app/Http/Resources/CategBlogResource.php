<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CategBlogResource extends JsonResource
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
                    'name' => $language->name,
                    'slug' => $language->slug,
                ]
            ];
        });

        // Rather than pluck, build the keys for each field by language_id for all present languages
        return [
            'id' => $this->id,
            'name' => $languages->mapWithKeys(fn($lang, $id) => [$id => $lang['name']]),
            'slug' => $languages->mapWithKeys(fn($lang, $id) => [$id => $lang['slug']]),
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
        ];
    
    }
}
