<?php

namespace App\Http\Resources;

use App\Models\Language;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseSectionResource extends JsonResource
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
        $languages = $this->langs->mapWithKeys(function ($language) {
            $lang = Language::where('shortname', $language->lang)->first();
            return [
                $lang?->id ?? ($language->lang ?? '1') => [
                    'id' => $language->id,
                    'lang' => $language->lang,
                    'language_id' => $lang?->id ?? '1',
                    'title' => $language->title,
                ]
            ];
        });

        // Rather than pluck, build the keys for each field by language_id for all present languages
        return [
            'id' => $this->id,
            'category_id' => $this->category_id,
            'course_id' => $this->course_id,
            'sort_order' => $this->sort_order,
            'title' => $languages->mapWithKeys(fn($lang, $id) => [$id => $lang['title']]),
            'created_at' => $this->created_at,


            'relations' => [
                'items' => [
                    'id' => $this->category?->id,
                    'name' => $this->category?->langs?->first()?->name ?? $this->category?->name,
                ],
            ],
        ];
    }
}
