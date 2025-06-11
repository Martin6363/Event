<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Event extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'status',
        'title',
        'description',
        'location',
        'date',
        'time',
        'duration',
        'duration_unit',
        'gender',
        'members_count',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('banner')->singleFile();
    }

    public function getAvailableEvents(){
        return self::where(now(), '>', Carbon::create($this->date . ' ' ));
    }
}
