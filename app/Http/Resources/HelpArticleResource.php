<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HelpArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $languages = $this->langsAll->mapWithKeys(function ($language) {
            return [
                $language->language_id => [
                    'id' => $language->id,
                    'language_id' => $language->language_id,
                    'shortname' => $language->language->shortname ?? null,
                    'title' => $language->title,
                    'description' => $language->description,
                ],
            ];
        });

        return [
            'id' => $this->id,
            'audience' => $this->audience,
            'slug' => $this->slug,
            'icon' => $this->icon_url,
            'icon_svg' => $this->icon_svg,
            'status' => $this->status,
            'average_rating' => $this->average_rating,
            'ratings_count' => $this->ratings_count,
            'title' => $languages->mapWithKeys(fn ($lang, $id) => [$id => $lang['title']]),
            'description' => $languages->mapWithKeys(fn ($lang, $id) => [$id => $lang['description']]),
            'relations' => [
                'langs' => $languages->values(),
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
