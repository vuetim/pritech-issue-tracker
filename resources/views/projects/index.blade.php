@extends('layouts.app')

@section('title', 'Projects')

@section('content')
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Projects</h1>
            <p class="mt-1 text-sm text-slate-600">
                Manage projects and review their issues.
            </p>
        </div>

        <a
            href="{{ route('projects.create') }}"
            class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"
        >
            Create project
        </a>
    </div>

    @if ($projects->isEmpty())
        <div class="rounded-xl border border-dashed border-slate-300 bg-white p-12 text-center">
            <h2 class="font-semibold text-slate-900">No projects found</h2>
            <p class="mt-1 text-sm text-slate-600">
                Create your first project to start tracking issues.
            </p>
        </div>
    @else
        <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                                Project
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                                Schedule
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                                Issues
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-semibold uppercase text-slate-500">
                                Actions
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-slate-200">
                        @foreach ($projects as $project)
                            <tr>
                                <td class="px-6 py-4">
                                    <a
                                        href="{{ route('projects.show', $project) }}"
                                        class="font-semibold text-blue-600 hover:text-blue-800"
                                    >
                                        {{ $project->name }}
                                    </a>

                                    <p class="mt-1 max-w-xl text-sm text-slate-600">
                                        {{ str($project->description)->limit(90) }}
                                    </p>
                                </td>

                                <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-600">
                                    <div>{{ $project->start_date->format('M j, Y') }}</div>
                                    <div class="mt-1">to {{ $project->deadline->format('M j, Y') }}</div>
                                </td>

                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $project->issues_count }}
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex justify-end gap-3">
                                        <a
                                            href="{{ route('projects.edit', $project) }}"
                                            class="text-sm font-medium text-slate-600 hover:text-slate-900"
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
                                                class="text-sm font-medium text-red-600 hover:text-red-800"
                                            >
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-200 px-6 py-4">
                {{ $projects->links() }}
            </div>
        </div>
    @endif
@endsection