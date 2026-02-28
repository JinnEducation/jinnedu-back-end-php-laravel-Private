<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConferenceRecording extends Model
{
    use HasFactory;

     protected $table = 'conference_recordings';

    protected $fillable = [
        'conference_id',
        'source_type',
        'media_url',
    ];

    public const SOURCE_UPLOAD = 'upload';
    public const SOURCE_URL    = 'url';

    public function conference()
    {
        return $this->belongsTo(Conference::class, 'conference_id');
    }
}
