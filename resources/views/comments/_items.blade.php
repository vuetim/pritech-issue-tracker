@forelse ($comments as $comment)
    @include('comments._comment', ['comment' => $comment])
@empty
    <p
        data-empty-comments
        class="rounded-lg border border-dashed border-slate-300 p-6 text-center text-sm text-slate-500"
    >
        No comments yet.
    </p>
@endforelse