<?php

namespace App\Http\Resources;

use App\Models\Language;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseItemResource extends JsonResource
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
                    'description' => $language->description,
                ]
            ];
        });

        $external_video_url = '';
        $source_type = '';
        $content_source = '';
        $type = $this->type;
        if($type == 'lesson_video' || $type == 'intro_recording' || $type == 'workshop_recording'){
            $media = $this->media()?->first();
            $external_video_url = $media?->media_url;
            $content_source = $media?->source_type;
        }else{
            $content_source = 'zoom';
        }

        // Rather than pluck, build the keys for each field by language_id for all present languages
        return [
            'id' => $this->id,
            'course_id' => $this->course_id,
            'section_id' => $this->section_id,
            'type' => $this->type,
            'is_free_preview' => $this->is_free_preview,
            'duration_seconds' => $this->duration_seconds,
            'sort_order' => $this->sort_order,
            'external_video_url' => $external_video_url,
            'content_source' => $content_source,
            'title' => $languages->mapWithKeys(fn($lang, $id) => [$id => $lang['title']]),
            'description' => $languages->mapWithKeys(fn($lang, $id) => [$id => $lang['description']]),
            'created_at' => $this->created_at,
            'zoom_start_at' => $this->liveSession?->first()?->start_at,


            'media' => $this->media,
            'liveSession' => $this->liveSession,
        ];
    }
}
