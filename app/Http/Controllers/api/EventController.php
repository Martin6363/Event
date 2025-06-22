<?php

namespace App\Http\Controllers\api;

use App\DTO\EventData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Event\EventStoreRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Services\Admin\EventService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

class EventController extends Controller
{
    use AuthorizesRequests;

    public const PER_PAGE = 15;

    public function __construct(readonly EventService $eventService)
    {
    }

    /**
     * @return JsonResponse
     * @OA\Get(
     *     path="/api/v1/events",
     *     tags={"User - Events"},
     *     summary="List Events",
     *     description="Returns a paginated list of events",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="success: true",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Event")),
     *             @OA\Property(property="pagination", type="object",
     *                 @OA\Property(property="total", type="integer", example=50),
     *                 @OA\Property(property="current_page", type="integer", example=1),
     *                 @OA\Property(property="per_page", type="integer", example=10),
     *                 @OA\Property(property="last_page", type="integer", example=5),
     *             )
     *         )
     *     )
     * )
     */
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

    /**
     * @param EventStoreRequest $eventStoreRequest
     * @return JsonResponse
     * @throws UnknownProperties
     */
    public function store(EventStoreRequest $eventStoreRequest): JsonResponse
    {
        $eventData = new EventData([
            ...$eventStoreRequest->validated(),
            'user_id' => $eventStoreRequest->user()->id,
            'banner' => $eventStoreRequest->file('banner'),
            'status' => 'moderation',
        ]);
        $event = $this->eventService->create($eventData);

        return response()->json([
            'success' => true,
            'event' => new EventResource($event),
        ]);
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

        $eventData = new EventData([
            ...$request->validated(),
            'user_id' => $event->user_id,
            'banner' => $request->file('banner'),
            'status' => 'moderation',
        ]);

        $updated = $this->eventService->update($event, $eventData);

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
