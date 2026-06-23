 @if($issues->isEmpty())
        <div class="rounded-xl border border-dashed border-slate-300 bg-white p-12 text-center">
            <h2 class="font-semibold text-slate-900">No issues found</h2>
            <p class="mt-1 text-sm text-slate-600">
                Try clearing the filters or create a new issue.
            </p>
        </div>
    @else
        <div class="space-y-4">
            @foreach ($issues as $issue)
                <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                    <div class="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <a
                                href="{{ route('issues.show', $issue) }}"
                                class="text-lg font-semibold text-blue-600 hover:text-blue-800"
                            >
                                {{ $issue->title }}
                            </a>

                            <div class="mt-1 text-sm text-slate-500">
                                {{ $issue->project->name }}
                            </div>

                            <p class="mt-3 max-w-3xl text-sm text-slate-600">
                                {{ str($issue->description)->limit(160) }}
                            </p>
                        </div>

                        <div class="flex gap-2">
                            <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                                {{ str($issue->status->value)->replace('_', ' ')->title() }}
                            </span>

                            <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">
                                {{ str($issue->priority->value)->title() }}
                            </span>
                        </div>
                    </div>

                    <div class="mt-4 flex flex-wrap items-center justify-between gap-4 border-t border-slate-100 pt-4">
                        <div class="flex flex-wrap items-center gap-2">
                            @forelse ($issue->tags as $tag)
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs text-slate-700">
                                    {{ $tag->name }}
                                </span>
                            @empty
                                <span class="text-xs text-slate-500">No tags</span>
                            @endforelse

                            <span class="text-xs text-slate-500">
                                {{ $issue->comments_count }} comments
                            </span>

                            <span class="text-xs text-slate-500">
                                Due: {{ $issue->due_date?->format('M j, Y') ?? 'No due date' }}
                            </span>
                        </div>

                        <div class="flex gap-3">
                            <a
                                href="{{ route('issues.edit', $issue) }}"
                                class="text-sm font-medium text-slate-600 hover:text-slate-900"
                            >
                                Edit
                            </a>

                            <form
                                method="POST"
                                action="{{ route('issues.destroy', $issue) }}"
                                onsubmit="return confirm('Delete this issue?')"
                            >
                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="text-sm font-medium text-red-600 hover:text-red-800"
                                >
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

<div data-pagination class="mt-6">
    {{ $issues->links() }}
</div>
    @endif