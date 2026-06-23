@extends('layouts.app')

@section('title', 'Issues')

@section('content')
    <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Issues</h1>
            <p class="mt-1 text-sm text-slate-600">
                Filter and manage issues across all projects.
            </p>
        </div>

        <a
            href="{{ route('issues.create') }}"
            class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"
        >
            Create issue
        </a>
    </div>

    <form
        method="GET"
        action="{{ route('issues.index') }}"
        class="mb-6 rounded-xl border border-slate-200 bg-white p-5 shadow-sm"
    >
        <div class="grid gap-4 md:grid-cols-3">
            <div>
                <label for="status" class="mb-2 block text-sm font-medium text-slate-700">
                    Status
                </label>

                <select
                    id="status"
                    name="status"
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2"
                >
                    <option value="">All statuses</option>

                    @foreach ($statuses as $status)
                        <option
                            value="{{ $status->value }}"
                            @selected(($filters['status'] ?? '') === $status->value)
                        >
                            {{ str($status->value)->replace('_', ' ')->title() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="priority" class="mb-2 block text-sm font-medium text-slate-700">
                    Priority
                </label>

                <select
                    id="priority"
                    name="priority"
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2"
                >
                    <option value="">All priorities</option>

                    @foreach ($priorities as $priority)
                        <option
                            value="{{ $priority->value }}"
                            @selected(($filters['priority'] ?? '') === $priority->value)
                        >
                            {{ str($priority->value)->title() }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="tag" class="mb-2 block text-sm font-medium text-slate-700">
                    Tag
                </label>

                <select
                    id="tag"
                    name="tag"
                    class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2"
                >
                    <option value="">All tags</option>

                    @foreach ($tags as $tag)
                        <option
                            value="{{ $tag->id }}"
                            @selected((string) ($filters['tag'] ?? '') === (string) $tag->id)
                        >
                            {{ $tag->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mt-4 flex justify-end gap-3">
            <a
                href="{{ route('issues.index') }}"
                class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
            >
                Clear
            </a>

            <button
                type="submit"
                class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700"
            >
                Apply filters
            </button>
        </div>
    </form>

    @if ($issues->isEmpty())
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

        <div class="mt-6">
            {{ $issues->links() }}
        </div>
    @endif
@endsection