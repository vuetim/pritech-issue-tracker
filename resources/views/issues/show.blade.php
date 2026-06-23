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
    <div>
        <h2 class="text-lg font-bold text-slate-900">Tags</h2>
        <p class="mt-1 text-sm text-slate-600">
            Attach or remove tags without reloading the page.
        </p>
    </div>

    <div
        id="tag-feedback"
        role="status"
        class="mt-4 hidden rounded-lg px-4 py-3 text-sm"
    ></div>

    <form
        id="attach-tag-form"
        data-url="{{ route('issues.tags.store', $issue) }}"
        data-detach-template="{{ route('issues.tags.destroy', [$issue, '__TAG__']) }}"
        class="mt-5 flex flex-col gap-3 sm:flex-row"
    >
        <select
            id="tag-select"
            name="tag_id"
            class="min-w-0 flex-1 rounded-lg border border-slate-300 bg-white px-3 py-2"
        >
            <option value="">Select a tag</option>

            @foreach ($availableTags as $tag)
                <option value="{{ $tag->id }}">
                    {{ $tag->name }}
                </option>
            @endforeach
        </select>

        <button
            type="submit"
            class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"
        >
            Attach tag
        </button>
    </form>

    <p id="tag-error" class="mt-2 hidden text-sm text-red-600"></p>

    <div id="issue-tags" class="mt-5 flex flex-wrap gap-2">
        @foreach ($issue->tags as $tag)
            <span
                data-tag-item
                data-tag-id="{{ $tag->id }}"
                data-tag-name="{{ $tag->name }}"
               class="inline-flex items-center gap-2 rounded-full bg-slate-100 px-3 py-1 text-sm text-slate-700"
            >
             <span
    class="h-2.5 w-2.5 rounded-full"
    @style([
        'background-color: ' . ($tag->color ?? '#cbd5e1'),
    ])
></span>

                <span>{{ $tag->name }}</span>

                <button
                    type="button"
                    data-detach-tag
                    data-url="{{ route('issues.tags.destroy', [$issue, $tag]) }}"
                    class="font-bold text-slate-400 hover:text-red-600"
                    aria-label="Detach {{ $tag->name }}"
                >
                    ×
                </button>
            </span>
        @endforeach
    </div>

    <p
        id="empty-tags-message"
        class="mt-4 text-sm text-slate-500 {{ $issue->tags->isNotEmpty() ? 'hidden' : '' }}"
    >
        No tags attached.
    </p>
</section>

          <section
    id="comments-panel"
    data-index-url="{{ route('issues.comments.index', $issue) }}"
    data-store-url="{{ route('issues.comments.store', $issue) }}"
    class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm"
>
    <div>
        <h2 class="text-lg font-bold text-slate-900">
            Comments
            <span
                id="comments-count"
                class="text-sm font-normal text-slate-500"
            >
                ({{ $issue->comments_count }})
            </span>
        </h2>

        <p class="mt-1 text-sm text-slate-600">
            Discussion and updates related to this issue.
        </p>
    </div>

    <div
        id="comment-feedback"
        role="status"
        class="mt-4 hidden rounded-lg px-4 py-3 text-sm"
    ></div>

    <form id="comment-form" class="mt-5 space-y-4">
        <div>
            <label for="author_name" class="mb-2 block text-sm font-medium text-slate-700">
                Your name
            </label>

            <input
                id="author_name"
                name="author_name"
                type="text"
                autocomplete="name"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
            >

            <p
                id="author-name-error"
                class="mt-2 hidden text-sm text-red-600"
            ></p>
        </div>

        <div>
            <label for="body" class="mb-2 block text-sm font-medium text-slate-700">
                Comment
            </label>

            <textarea
                id="body"
                name="body"
                rows="4"
                class="w-full rounded-lg border border-slate-300 px-3 py-2 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
            ></textarea>

            <p
                id="body-error"
                class="mt-2 hidden text-sm text-red-600"
            ></p>
        </div>

        <div class="flex justify-end">
            <button
                type="submit"
                class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-60"
            >
                Add comment
            </button>
        </div>
    </form>

    <div class="my-6 border-t border-slate-200"></div>

    <div id="comments-list" class="space-y-3">
        <p class="text-sm text-slate-500">Loading comments...</p>
    </div>

    <div class="mt-5 text-center">
        <button
            id="load-more-comments"
            type="button"
            class="hidden rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
        >
            Load more comments
        </button>
    </div>
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