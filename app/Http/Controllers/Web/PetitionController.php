<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Petition;
use App\Services\PetitionService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PetitionController extends Controller
{
    protected $petitionService;

    public function __construct(PetitionService $petitionService)
    {
        $this->petitionService = $petitionService;
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(Request $request)
    {
        $query = Petition::with('creator')
            ->active()
            ->public();

        // Search
        if ($request->has('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Filter by category
        if ($request->has('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Sort
        $sortBy = $request->get('sort', 'recent');
        switch ($sortBy) {
            case 'popular':
                $query->orderBy('upvote_count', 'desc');
                break;
            case 'recent':
            default:
                $query->latest();
                break;
        }

        $petitions = $query->paginate(12);

        return Inertia::render('Petitions/Index', [
            'petitions' => $petitions,
            'filters' => $request->only(['search', 'category', 'sort']),
        ]);
    }

    public function show(Petition $petition)
    {
        $petition->load([
            'creator',
            'threads' => function($query) {
                $query->whereNull('parent_id')
                      ->with(['user', 'replies.user'])
                      ->latest();
            },
            'events'
        ]);

        $hasVoted = auth()->check() ? $petition->hasVoted(auth()->id()) : false;

        return Inertia::render('Petitions/Show', [
            'petition' => $petition,
            'hasVoted' => $hasVoted,
        ]);
    }

    public function create()
    {
        return Inertia::render('Petitions/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'threshold' => 'nullable|integer|min:10',
            'image' => 'nullable|image|max:2048',
        ]);

        $petition = $this->petitionService->createPetition(
            auth()->user(),
            $validated
        );

        return redirect()->route('petitions.show', $petition)
            ->with('success', 'Petition created successfully!');
    }

    public function vote(Petition $petition)
    {
        $result = $this->petitionService->toggleVote(auth()->user(), $petition);

        return back()->with('success', $result['voted'] ? 'Vote added!' : 'Vote removed!');
    }

    public function comment(Request $request, Petition $petition)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'parent_id' => 'nullable|exists:petition_threads,id',
        ]);

        $this->petitionService->addComment(
            auth()->user(),
            $petition,
            $validated
        );

        return back()->with('success', 'Comment added!');
    }
}
