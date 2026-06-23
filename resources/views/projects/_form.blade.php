<div class="space-y-6">
    <div>
        <label for="name" class="mb-2 block text-sm font-medium text-slate-700">
            Name
        </label>

        <input
            id="name"
            name="name"
            type="text"
            value="{{ old('name', $project->name ?? '') }}"
            required
            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
        >

        @error('name')
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
            rows="5"
            required
            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
        >{{ old('description', $project->description ?? '') }}</textarea>

        @error('description')
            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        <div>
            <label for="start_date" class="mb-2 block text-sm font-medium text-slate-700">
                Start date
            </label>

            <input
                id="start_date"
                name="start_date"
                type="date"
                value="{{ old('start_date', isset($project) ? $project->start_date?->format('Y-m-d') : '') }}"
                required
                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
            >

            @error('start_date')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="deadline" class="mb-2 block text-sm font-medium text-slate-700">
                Deadline
            </label>

            <input
                id="deadline"
                name="deadline"
                type="date"
                value="{{ old('deadline', isset($project) ? $project->deadline?->format('Y-m-d') : '') }}"
                required
                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
            >

            @error('deadline')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="flex justify-end gap-3 border-t border-slate-200 pt-6">
        <a
            href="{{ isset($project) ? route('projects.show', $project) : route('projects.index') }}"
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