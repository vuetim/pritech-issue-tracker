<article
    data-comment
    class="rounded-lg border border-slate-200 bg-slate-50 p-4"
>
    <div class="flex items-center justify-between gap-4">
        <h3 class="font-semibold text-slate-900">
            {{ $comment->author_name }}
        </h3>

        <time
            datetime="{{ $comment->created_at->toIso8601String() }}"
            class="text-xs text-slate-500"
        >
            {{ $comment->created_at->diffForHumans() }}
        </time>
    </div>

    <p class="mt-3 whitespace-pre-line text-sm text-slate-700">
        {{ $comment->body }}
    </p>
</article>