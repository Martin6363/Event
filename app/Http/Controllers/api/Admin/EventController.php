<?php

namespace App\Http\Controllers\api\Admin;

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
    public const PER_PAGE = 10;

    public function __construct(readonly EventService $eventService){}

    /**
     * @OA\Get(
     *     path="/api/v1/admin/events",
     *     tags={"Admin - Events"},
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

    public function store(EventStoreRequest $request)
    {
        $event = $this->eventService->create([
            ...$request->validated(),
            'user_id' => $request->user()->id,
        ]);

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
        $updated = $this->eventService->update($event, [
            ...$request->validated(),
        ]);

        return response()->json([
            'success' => true,
            'event' => new EventResource($updated),
        ]);
    }

    public function approve(Event $event)
    {
        $this->authorize('approve', $event);
        $event = $this->eventService->approve($event);

        return response()->json([
            'success' => true,
            'message' => 'Event approved successfully',
            'event' => $event,
        ]);
    }
}
