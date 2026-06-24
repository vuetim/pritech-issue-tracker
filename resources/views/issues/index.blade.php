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
        id="issue-filter-form"
        method="GET"
        action="{{ route('issues.index') }}"
        class="mb-6 rounded-xl border border-slate-200 bg-white p-5 shadow-sm"
    >
        <div class="grid gap-4 md:grid-cols-3">
            <div class="md:col-span-3">
    <label for="issue-search" class="mb-2 block text-sm font-medium text-slate-700">
        Search updates automatically while you type.
    </label>

    <input
        id="issue-search"
        name="search"
        type="search"
        value="{{ $filters['search'] ?? '' }}"
        placeholder="Search title or description..."
        autocomplete="off"
        class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
    >

    <p class="mt-2 text-xs text-slate-500">
        Search ...
    </p>
</div>
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

        <div class="mt-4 flex justify-end gap-3 items-center">
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

   <div
    id="issues-results"
    aria-live="polite"
>
    @include('issues._results')
</div>
@endsection