<?php

namespace App\Http\Resources;

use App\Models\Language;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
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
                    'excerpt' => $language->excerpt,
                    'description' => $language->description,
                    'outcomes_json' => $language->outcomes_json ?? [],
                    'requirements_json' => $language->requirements_json ?? [],
                ]
            ];
        });

        // Rather than pluck, build the keys for each field by language_id for all present languages
        return [
            'id' => $this->id,
            'category_id' => $this->category_id,
            'instructor_id' => $this->instructor_id,
            'price' => $this->price,
            'is_free' => $this->is_free,
            'has_certificate' => $this->has_certificate,
            'status' => $this->status,
            'published_at' => $this->published_at,
            'title' => $languages->mapWithKeys(fn($lang, $id) => [$id => $lang['title']]),
            'excerpt' => $languages->mapWithKeys(fn($lang, $id) => [$id => $lang['excerpt']]),
            'description' => $languages->mapWithKeys(fn($lang, $id) => [$id => $lang['description']]),
            'outcomes_json' => $languages->mapWithKeys(fn($lang, $id) => [$id => $lang['outcomes_json']]),
            'requirements_json' => $languages->mapWithKeys(fn($lang, $id) => [$id => $lang['requirements_json']]),
            'created_at' => $this->created_at,
            'promo_video_url' => $this->promo_video_url,
            'promo_video_duration_seconds' => $this->promo_video_duration_seconds,
            'discount_type' => $this->activeDiscount()->first()?->type,
            'discount_value' => $this->activeDiscount()->first()?->value,
            'discount_starts_at' => $this->activeDiscount()->first()?->starts_at,
            'discount_ends_at' => $this->activeDiscount()->first()?->ends_at,



            'relations' => [
                'category' => [
                    'id' => $this->category?->id,
                    'name' => $this->category?->langs?->first()?->name ?? $this->category?->name,
                ],

                'instructor' => $this->whenLoaded('instructor', function () {
                    return $this->instructor
                        ? [
                            'id'   => $this->instructor?->id,
                            'name' => $this->instructor?->full_name,
                        ]
                        : null;
                }),

            
            ],
        ];
    }
}
