<div class="space-y-6">
    <div>
        <label for="project_id" class="mb-2 block text-sm font-medium text-slate-700">
            Project
        </label>

        <select
            id="project_id"
            name="project_id"
            required
            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
        >
            <option value="">Select a project</option>

            @foreach ($projects as $project)
                <option
                    value="{{ $project->id }}"
                    @selected(
                        (string) old(
                            'project_id',
                            $issue->project_id ?? $selectedProjectId ?? ''
                        ) === (string) $project->id
                    )
                >
                    {{ $project->name }}
                </option>
            @endforeach
        </select>

        @error('project_id')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="title" class="mb-2 block text-sm font-medium text-slate-700">
            Title
        </label>

        <input
            id="title"
            name="title"
            type="text"
            value="{{ old('title', $issue->title ?? '') }}"
            required
            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
        >

        @error('title')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="description" class="mb-2 block text-sm font-medium text-slate-700">
            Description
        </label>

        <textarea
            id="description"
            name="description"
            rows="6"
            required
            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
        >{{ old('description', $issue->description ?? '') }}</textarea>

        @error('description')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <label for="status" class="mb-2 block text-sm font-medium text-slate-700">
                Status
            </label>

            <select
                id="status"
                name="status"
                required
                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
            >
                <option value="">Select status</option>

                @foreach ($statuses as $status)
                    <option
                        value="{{ $status->value }}"
                        @selected(
                            old(
                                'status',
                                isset($issue) ? $issue->status->value : ''
                            ) === $status->value
                        )
                    >
                        {{ str($status->value)->replace('_', ' ')->title() }}
                    </option>
                @endforeach
            </select>

            @error('status')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="priority" class="mb-2 block text-sm font-medium text-slate-700">
                Priority
            </label>

            <select
                id="priority"
                name="priority"
                required
                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
            >
                <option value="">Select priority</option>

                @foreach ($priorities as $priority)
                    <option
                        value="{{ $priority->value }}"
                        @selected(
                            old(
                                'priority',
                                isset($issue) ? $issue->priority->value : ''
                            ) === $priority->value
                        )
                    >
                        {{ str($priority->value)->title() }}
                    </option>
                @endforeach
            </select>

            @error('priority')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div>
        <label for="due_date" class="mb-2 block text-sm font-medium text-slate-700">
            Due date
            <span class="font-normal text-slate-500">(optional)</span>
        </label>

        <input
            id="due_date"
            name="due_date"
            type="date"
            value="{{ old(
                'due_date',
                isset($issue) ? $issue->due_date?->format('Y-m-d') : ''
            ) }}"
            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
        >

        @error('due_date')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex justify-end gap-3 border-t border-slate-200 pt-6">
        <a
            href="{{ isset($issue) ? route('issues.show', $issue) : route('issues.index') }}"
            class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50"
        >
            Cancel
        </a>

        <button
            type="submit"
            class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"
        >
            {{ $submitLabel }}
        </button>
    </div>
</div>