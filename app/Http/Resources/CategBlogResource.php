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
        $translation = $this->relationLoaded('langs') ? optional($this->langs->first()) : null;

        
        return [
            'id' => $this->id,
            'categ_blog_id' => $this->categ_blog_id,
            'language_id' => $this->language_id,
            'name'         => $translation?->name,
            'slug'          => $translation?->slug,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
        ];
    
    }
}
