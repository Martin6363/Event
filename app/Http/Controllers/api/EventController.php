<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\EventStoreRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Services\Admin\EventService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Throwable;

class EventController extends Controller
{
    use AuthorizesRequests;

    public const PER_PAGE = 15;

    public function __construct(readonly EventService $eventService)
    {
    }

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
        $this->authorize('update', $event);
        $updated = $this->eventService->update($event, [...$request->validated()]);

        return response()->json([
            'success' => true,
            'event' => new EventResource($updated),
        ]);
    }

    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);
        $this->eventService->delete($event);

        return response()->json([
            'success' => true,
            'message' => 'Event deleted successful.'
        ]);
    }
}
