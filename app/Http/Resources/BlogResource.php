<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BlogResource extends JsonResource
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
                    'slug' => $language->slug,
                    'description' => $language->description,
                ]
            ];
        });

        // Rather than pluck, build the keys for each field by language_id for all present languages
        return [
            'id' => $this->id,
            'categ_blog_id' => $this->categ_blog_id,
            'title' => $languages->mapWithKeys(fn($lang, $id) => [$id => $lang['title']]),
            'slug' => $languages->mapWithKeys(fn($lang, $id) => [$id => $lang['slug']]),
            'description' => $languages->mapWithKeys(fn($lang, $id) => [$id => $lang['description']]),
            'image' => $this->image_url,
            'date' => $this->date,
            'status' => $this->status,
            'published_at' => $this->published_at,


            'relations' => [
                'categ_blog_id' => [
                    'id' => $this->category?->id,
                    'name' => $this->category?->langs?->first()?->name ?? $this->category?->name,
                    'slug' => $this->category?->langs?->first()?->slug,
                ],

                'user' => $this->whenLoaded('users', function () {
                    return $this->users
                        ? [
                            'id'   => $this->users?->id,
                            'name' => $this->users?->name,
                        ]
                        : null;
                }),

            
            ],
        ];
    }
}
