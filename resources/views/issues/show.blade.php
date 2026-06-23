@extends('layouts.app')

@section('title', $issue->title)

@section('content')
    <div class="mb-6">
        <a
            href="{{ route('issues.index') }}"
            class="text-sm font-medium text-blue-600 hover:text-blue-800"
        >
            ← Back to issues
        </a>
    </div>

    <div class="grid gap-6 lg:grid-cols-[minmax(0,2fr)_minmax(280px,1fr)]">
        <main class="space-y-6">
            <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <div class="mb-3 flex flex-wrap gap-2">
                            <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-700">
                                {{ str($issue->status->value)->replace('_', ' ')->title() }}
                            </span>

                            <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-semibold text-amber-700">
                                {{ str($issue->priority->value)->title() }}
                            </span>
                        </div>

                        <h1 class="text-3xl font-bold text-slate-900">
                            {{ $issue->title }}
                        </h1>
                    </div>

                    <div class="flex gap-3">
                        <a
                            href="{{ route('issues.edit', $issue) }}"
                            class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
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
                                class="rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white hover:bg-red-700"
                            >
                                Delete
                            </button>
                        </form>
                    </div>
                </div>

                <div class="mt-6 border-t border-slate-200 pt-6">
                    <h2 class="text-sm font-semibold uppercase tracking-wide text-slate-500">
                        Description
                    </h2>

                    <div class="mt-3 whitespace-pre-line text-slate-700">
                        {{ $issue->description }}
                    </div>
                </div>
            </section>

            <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Tags</h2>
                        <p class="mt-1 text-sm text-slate-600">
                            Labels currently attached to this issue.
                        </p>
                    </div>
                </div>

                <div id="issue-tags" class="mt-4 flex flex-wrap gap-2">
                    @forelse ($issue->tags as $tag)
                        <span class="rounded-full bg-slate-100 px-3 py-1 text-sm text-slate-700">
                            {{ $tag->name }}
                        </span>
                    @empty
                        <span class="text-sm text-slate-500">No tags attached.</span>
                    @endforelse
                </div>
            </section>

            <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-bold text-slate-900">
                    Comments
                    <span class="text-sm font-normal text-slate-500">
                        ({{ $issue->comments_count }})
                    </span>
                </h2>

                <p class="mt-2 text-sm text-slate-600">
                    Paginated comments and the comment form will be loaded here via AJAX.
                </p>
            </section>
        </main>

        <aside>
            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="font-bold text-slate-900">Issue details</h2>

                <dl class="mt-5 space-y-5">
                    <div>
                        <dt class="text-sm text-slate-500">Project</dt>
                        <dd class="mt-1">
                            <a
                                href="{{ route('projects.show', $issue->project) }}"
                                class="font-medium text-blue-600 hover:text-blue-800"
                            >
                                {{ $issue->project->name }}
                            </a>
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm text-slate-500">Due date</dt>
                        <dd class="mt-1 font-medium text-slate-900">
                            {{ $issue->due_date?->format('M j, Y') ?? 'No due date' }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm text-slate-500">Created</dt>
                        <dd class="mt-1 font-medium text-slate-900">
                            {{ $issue->created_at->format('M j, Y') }}
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm text-slate-500">Last updated</dt>
                        <dd class="mt-1 font-medium text-slate-900">
                            {{ $issue->updated_at->diffForHumans() }}
                        </dd>
                    </div>
                </dl>
            </div>
        </aside>
    </div>
@endsection