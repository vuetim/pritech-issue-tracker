<?php

namespace App\Http\Controllers;

use App\Http\Requests\AttachMemberRequest;
use App\Models\Issue;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class IssueMemberController extends Controller
{
    public function store(
        AttachMemberRequest $request,
        Issue $issue
    ): JsonResponse {
        $member = User::findOrFail($request->integer('user_id'));

        $result = $issue->members()
            ->syncWithoutDetaching([$member->id]);

        $status = count($result['attached']) > 0 ? 201 : 200;

        return response()->json([
            'message' => 'Member assigned successfully.',
            'member' => [
                'id' => $member->id,
                'name' => $member->name,
                'email' => $member->email,
            ],
        ], $status);
    }

    public function destroy(Issue $issue, User $user): JsonResponse
    {
        $detached = $issue->members()->detach($user->id);

        if ($detached === 0) {
            return response()->json([
                'message' => 'Member is not assigned to this issue.',
            ], 404);
        }

        return response()->json([
            'message' => 'Member removed successfully.',
        ]);
    }
}
