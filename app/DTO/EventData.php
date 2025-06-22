<?php

namespace App\DTO;

use Illuminate\Http\UploadedFile;
use Spatie\DataTransferObject\DataTransferObject;

class EventData extends DataTransferObject
{
    public string $title;
    public ?string $description;
    public string $location;
    public string $date;
    public string $time;
    public string $duration;
    public string $duration_unit;
    public string $gender;
    public int $members_count;
    public ?string $status;
    public int $user_id;
    public ?UploadedFile $banner;
}
