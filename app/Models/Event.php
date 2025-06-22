<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Event",
 *     type="object",
 *     title="Event",
 *     required={"id", "user_id", "status", "title", "date"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=5),
 *     @OA\Property(property="status", type="string", example="published"),
 *     @OA\Property(property="title", type="string", example="Community Gathering"),
 *     @OA\Property(property="description", type="string", example="A great community event."),
 *     @OA\Property(property="location", type="string", example="Central Park"),
 *     @OA\Property(property="date", type="string", format="date", example="2025-06-30"),
 *     @OA\Property(property="time", type="string", example="18:00"),
 *     @OA\Property(property="duration", type="integer", example=2),
 *     @OA\Property(property="duration_unit", type="string", example="hours"),
 *     @OA\Property(property="gender", type="string", example="any"),
 *     @OA\Property(property="members_count", type="integer", example=50),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-06-19T12:34:56Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-06-19T12:34:56Z")
 * )
 */
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
