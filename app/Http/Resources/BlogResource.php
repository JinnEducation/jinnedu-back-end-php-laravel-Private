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
         $translation = $this->whenLoaded('langs', function () {
            return optional($this->langs->first());
        });

        
        return [
            'id' => $this->id,
            'title'         => $translation?->title,
            'slug'          => $translation?->slug,
            'description'   => $translation?->description,
            'image' => $this->image_url,
            'date' => $this->date,
            'status' => $this->status,
            'published_at' => $this->published_at,


            'relations' => [
                'categ_blog_id' => [
                    'id' => $this->category?->id,
                    'name' => $this->category?->name,
                ],

               

                'courses' => [
                    'id' => $this->course?->id,
                    'name' => $this->course?->name,
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
