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
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
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
