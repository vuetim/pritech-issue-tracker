@extends('layouts.app')

@section('title', $project->name)

@section('content')
    <div class="mb-6 flex flex-wrap items-start justify-between gap-4">
        <div>
            <a
                href="{{ route('projects.index') }}"
                class="text-sm font-medium text-blue-600 hover:text-blue-800"
            >
                ← Back to projects
            </a>

            <h1 class="mt-3 text-3xl font-bold text-slate-900">
                {{ $project->name }}
            </h1>

            <p class="mt-2 max-w-3xl text-slate-600">
                {{ $project->description }}
            </p>
        </div>

        <div class="flex gap-3 items-center">
            <a
                href="{{ route('projects.edit', $project) }}"
                class="rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
            >
                Edit
            </a>

            <form
                method="POST"
                action="{{ route('projects.destroy', $project) }}"
                onsubmit="return confirm('Delete this project and all of its issues?')"
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

    <div class="mb-8 grid gap-4 sm:grid-cols-3">
        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="text-sm text-slate-500">Start date</div>
            <div class="mt-1 font-semibold text-slate-900">
                {{ $project->start_date->format('M j, Y') }}
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="text-sm text-slate-500">Deadline</div>
            <div class="mt-1 font-semibold text-slate-900">
                {{ $project->deadline->format('M j, Y') }}
            </div>
        </div>

        <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="text-sm text-slate-500">Issues</div>
            <div class="mt-1 font-semibold text-slate-900">
                {{ $issues->total() }}
            </div>
        </div>
    </div>

  <section>
    <div class="mb-4 flex items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Project issues</h2>
            <p class="mt-1 text-sm text-slate-600">
                Issues currently associated with this project.
            </p>
        </div>

        <a
            href="{{ route('issues.create', ['project_id' => $project->id]) }}"
            class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"
        >
            Create issue
        </a>
    </div>

        @if ($issues->isEmpty())
            <div class="rounded-xl border border-dashed border-slate-300 bg-white p-10 text-center">
                <p class="text-sm text-slate-600">This project has no issues yet.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($issues as $issue)
                    <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div>
                              <a
    href="{{ route('issues.show', $issue) }}"
    class="font-semibold text-blue-600 hover:text-blue-800"
>
    {{ $issue->title }}
</a>

                                <p class="mt-1 text-sm text-slate-600">
                                    {{ str($issue->description)->limit(140) }}

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

                        <div class="mt-4 flex flex-wrap items-center gap-2">
                            @forelse ($issue->tags as $tag)
                                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs text-slate-700">
                                    {{ $tag->name }}
                                </span>
                            @empty
                                <span class="text-xs text-slate-500">No tags</span>
                            @endforelse
                        </div>

                        <div class="mt-4 text-xs text-slate-500">
                            Due:
                            {{ $issue->due_date?->format('M j, Y') ?? 'No due date' }}
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $issues->links() }}
            </div>
        @endif
    </section>
@endsection