<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Issue;
use Illuminate\Http\JsonResponse;

class IssueCommentController extends Controller
{
    public function index(Issue $issue): JsonResponse
    {
        $comments = $issue->comments()
            ->latest()
            ->paginate(5);

        return response()->json([
            'html' => view('comments._items', compact('comments'))->render(),
            'next_page_url' => $comments->nextPageUrl(),
            'total' => $comments->total(),
        ]);
    }

    public function store(
        StoreCommentRequest $request,
        Issue $issue
    ): JsonResponse {
        $comment = $issue->comments()->create($request->validated());

        return response()->json([
            'message' => 'Comment added successfully.',
            'html' => view('comments._comment', compact('comment'))->render(),
            'total' => $issue->comments()->count(),
        ], 201);
    }
}
