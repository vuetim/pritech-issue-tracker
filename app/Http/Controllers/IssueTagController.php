<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttachTagRequest;
use App\Models\Issue;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;

class IssueTagController extends Controller
{
    public function store(
        AttachTagRequest $request,
        Issue $issue
    ): JsonResponse {
        $tag = Tag::findOrFail($request->integer('tag_id'));

        $result = $issue->tags()->syncWithoutDetaching([$tag->id]);

        $status = count($result['attached']) > 0 ? 201 : 200;

        return response()->json([
            'message' => 'Tag attached successfully.',
            'tag' => [
                'id' => $tag->id,
                'name' => $tag->name,
                'color' => $tag->color,
            ],
        ], $status);
    }

    public function destroy(Issue $issue, Tag $tag): JsonResponse
    {
        $detached = $issue->tags()->detach($tag->id);

        if ($detached === 0) {
            return response()->json([
                'message' => 'Tag is not attached to this issue.',
            ], 404);
        }

        return response()->json([
            'message' => 'Tag detached successfully.',
        ]);
    }
}
