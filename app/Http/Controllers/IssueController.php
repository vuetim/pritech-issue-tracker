<?php

namespace App\Http\Controllers;

use App\Enums\IssuePriority;
use App\Enums\IssueStatus;
use App\Http\Requests\StoreIssueRequest;
use App\Http\Requests\UpdateIssueRequest;
use App\Models\Issue;
use App\Models\Project;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IssueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View|JsonResponse
    {
        $filters = $request->only([
            'search',
            'status',
            'priority',
            'tag',
        ]);

        $issues = Issue::query()
            ->with(['project', 'tags'])
            ->withCount('comments')
            ->filter($filters)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->expectsJson()) {
            return response()->json([
                'html' => view(
                    'issues._results',
                    compact('issues')
                )->render(),
                'total' => $issues->total(),
            ]);
        }

        return view('issues.index', [
            'issues' => $issues,
            'tags' => Tag::query()->orderBy('name')->get(),
            'statuses' => IssueStatus::cases(),
            'priorities' => IssuePriority::cases(),
            'filters' => $filters,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        return view('issues.create', $this->formOptions() + [
            'selectedProjectId' => $request->integer('project_id') ?: null,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreIssueRequest $request): RedirectResponse
    {
        $issue = Issue::create($request->validated());

        return redirect()
            ->route('issues.show', $issue)
            ->with('success', 'Issue created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Issue $issue): View
    {
        $issue->load(['project', 'tags']);
        $issue->loadCount('comments');

        $availableTags = Tag::query()
            ->whereDoesntHave(
                'issues',
                fn ($query) => $query->whereKey($issue->id)
            )
            ->orderBy('name')
            ->get();

        return view('issues.show', compact('issue', 'availableTags'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Issue $issue): View
    {
        return view('issues.edit', $this->formOptions() + [
            'issue' => $issue,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UpdateIssueRequest $request,
        Issue $issue
    ): RedirectResponse {
        $issue->update($request->validated());

        return redirect()
            ->route('issues.show', $issue)
            ->with('success', 'Issue updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Issue $issue): RedirectResponse
    {
        $issue->delete();

        return redirect()
            ->route('issues.index')
            ->with('success', 'Issue deleted successfully.');
    }

    /**
     * @return array{
     *     projects: Collection<int, Project>,
     *     statuses: array<IssueStatus>,
     *     priorities: array<IssuePriority>
     * }
     */
    private function formOptions(): array
    {
        return [
            'projects' => Project::query()
                ->orderBy('name')
                ->get(['id', 'name']),
            'statuses' => IssueStatus::cases(),
            'priorities' => IssuePriority::cases(),
        ];
    }
}
