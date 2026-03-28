<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FaqResource extends JsonResource
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
                    'question' => $language->question,
                    'answer' => $language->answer,
                ],
            ];
        });

        return [
            'id' => $this->id,
            'status' => $this->status,
            'sort_order' => $this->sort_order,
            'question' => $languages->mapWithKeys(fn ($lang, $id) => [$id => $lang['question']]),
            'answer' => $languages->mapWithKeys(fn ($lang, $id) => [$id => $lang['answer']]),
            'relations' => [
                'langs' => $languages->values(),
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
