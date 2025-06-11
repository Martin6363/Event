<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\EventStoreRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Services\Admin\EventService;
use Illuminate\Http\JsonResponse;
use Throwable;

class EventController extends Controller
{
    public const PER_PAGE = 15;
    public function __construct(readonly EventService $eventService) {}

    public function index(): JsonResponse
    {
        $events = Event::latest()->orderByDesc('created_at')->paginate(self::PER_PAGE);

        return response()->json([
            'data' => EventResource::collection($events),
            'pagination' => [
                'total' => $events->total(),
                'current_page' => $events->currentPage(),
                'per_page' => $events->perPage(),
                'last_page' => $events->lastPage(),
            ]
        ]);
    }

    public function store(EventStoreRequest $request)
    {
        try {
            $event = $this->eventService->create([
                ...$request->validated(),
                'user_id' => $request->user()->id,
            ]);

            return response()->json([
                'success' => true,
                'event' => new EventResource($event),
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create event.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show(Event $event)
    {
        return response()->json([
            'success' => true,
            'data' => new EventResource($event),
        ]);
    }

    public function update(EventStoreRequest $request, Event $event)
    {
        try {
            if (auth()->id() !== $event->user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to update this event.',
                ], 403);
            }

            $updated = $this->eventService->update($event, [
                ...$request->validated(),
            ]);

            if (!$updated) {
                return response()->json([
                    'success' => false,
                    'message' => 'Event update failed.',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'event' => new EventResource($updated),
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred while updating the event.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function destroy(Event $event)
    {
        try {
            if (auth()->id() !== $event->user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are not authorized to delete this event.'
                ]);
            }

            $this->eventService->delete($event);

            return response()->json([
                'success' => true,
                'message' => 'Event deleted successful.'
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete event.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
