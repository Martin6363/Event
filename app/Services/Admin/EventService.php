<?php

namespace App\Services\Admin;

use App\Models\Event;
use Illuminate\Support\Facades\DB;

class EventService
{
    public function create(array $data): Event|null
    {
        return DB::transaction(function () use ($data) {
            $banner = $data['banner'] ?? null;
            unset($data['banner']);

            $event = Event::create($data);

            if ($banner) {
                $event->addMedia($banner)->toMediaCollection('banner');
            }

            return $event;
        });
    }

    public function update(Event $event, array $data): Event|null
    {
        return DB::transaction(function () use ($event, $data) {
            $banner = $data['banner'] ?? null;
            unset($data['banner']);

            $event->update($data);

            if ($banner) {
                $event->clearMediaCollection('banner');
                $event->addMedia($banner)->toMediaCollection('banner');
            }

            return $event;
        });
    }

    public function delete(Event $event): void
    {
        $event->clearMediaCollection('banner');
        $event->delete();
    }

    public function approve(Event $event): Event
    {
        return DB::transaction(function () use ($event) {
            if (!auth()->user()->can('approve_event')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                ], 403);
            }

            if ($event->status !== 'moderation') {
                return response()->json([
                    'success' => false,
                    'message' => 'Event already processed.',
                ], 400);
            }

            $event->update(['status' => 'published']);

            return $event;
        });
    }
}
