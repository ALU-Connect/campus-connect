<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Petition;
use App\Services\EventService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class EventController extends Controller
{
    protected $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(Request $request)
    {
        $query = Event::with(['creator', 'petition'])
            ->where('status', 'upcoming')
            ->where('event_date', '>=', now());

        // Filter by search
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $events = $query->orderBy('event_date', 'asc')->paginate(12);

        return Inertia::render('Events/Index', [
            'events' => $events,
            'filters' => $request->only(['search']),
        ]);
    }

    public function show(Event $event)
    {
        $event->load(['creator', 'petition', 'rsvps.user']);

        $hasRsvped = auth()->check() 
            ? $event->rsvps()->where('user_id', auth()->id())->exists()
            : false;

        return Inertia::render('Events/Show', [
            'event' => $event,
            'hasRsvped' => $hasRsvped,
            'attendeeCount' => $event->rsvps()->count(),
        ]);
    }

    public function create()
    {
        $petitions = Petition::active()
            ->where('creator_id', auth()->id())
            ->get(['id', 'title']);

        return Inertia::render('Events/Create', [
            'petitions' => $petitions,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string',
            'event_date' => 'required|date|after:now',
            'petition_id' => 'nullable|exists:petitions,id',
            'max_attendees' => 'nullable|integer|min:1',
        ]);

        $event = $this->eventService->createEvent(
            auth()->user(),
            $validated
        );

        return redirect()->route('events.show', $event)
            ->with('success', 'Event created successfully!');
    }

    public function rsvp(Event $event)
    {
        $result = $this->eventService->toggleRsvp(auth()->user(), $event);

        return back()->with('success', $result['rsvped'] ? 'RSVP confirmed!' : 'RSVP cancelled!');
    }
}
