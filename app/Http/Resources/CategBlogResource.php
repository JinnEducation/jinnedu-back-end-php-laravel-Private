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
       $translation = $this->whenLoaded('langs', function () {
            return optional($this->langs->first());
        });

        
        return [
            'id' => $this->id,
            'name'         => $translation?->title,
            'slug'          => $translation?->slug,
            'sort_order' => $this->sort_order,
            'is_active' => $this->is_active,
           


            'relations' => [
                'blog_id' => [
                    'id' => $this->blogs?->id,
                    'name' => $this->blogs?->name,
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
