<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class MenuResource extends JsonResource
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
            'p_id' => (int) ($this->p_id ?? 0),
            'title' => $this->title,
            'name' => $this->name,
            'route' => $this->route,
            'slug' => $this->slug,
            'type' => $this->type,
            'active_routes' => $this->active_routes, // pipe string
            'status' => (int) $this->status,
            'invisible' => (int) $this->invisible,
            'sortable' => (int) $this->sortable,
            'svg' => $this->svg ? Storage::url($this->svg) : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
