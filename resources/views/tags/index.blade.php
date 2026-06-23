@extends('layouts.app')

@section('title', 'Tags')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-slate-900">Tags</h1>
        <p class="mt-1 text-sm text-slate-600">
            Create and review labels available for issues.
        </p>
    </div>

    <div class="grid gap-6 lg:grid-cols-[360px_minmax(0,1fr)]">
        <section class="h-fit rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-bold text-slate-900">Create tag</h2>

            <form
                method="POST"
                action="{{ route('tags.store') }}"
                class="mt-5 space-y-5"
            >
                @csrf

                <div>
                    <label for="name" class="mb-2 block text-sm font-medium text-slate-700">
                        Name
                    </label>

                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name') }}"
                        required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                    >

                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="color" class="mb-2 block text-sm font-medium text-slate-700">
                        Color
                        <span class="font-normal text-slate-500">(optional)</span>
                    </label>

                    <input
                        id="color"
                        name="color"
                        type="text"
                        value="{{ old('color') }}"
                        placeholder="#2563eb"
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                    >

                    @error('color')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="w-full rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700"
                >
                    Create tag
                </button>
            </form>
        </section>

        <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            @if ($tags->isEmpty())
                <div class="p-12 text-center text-sm text-slate-600">
                    No tags found.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                                    Tag
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-semibold uppercase text-slate-500">
                                    Color
                                </th>
                                <th class="px-6 py-3 text-right text-xs font-semibold uppercase text-slate-500">
                                    Issues
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-slate-200">
                            @foreach ($tags as $tag)
                                <tr>
                                    <td class="px-6 py-4 font-medium text-slate-900">
                                        <div class="flex items-center gap-3">
                                            <span
                                                class="h-3 w-3 rounded-full border border-slate-200"
                                                @if ($tag->color)
                                                    style="background-color: {{ $tag->color }}"
                                                @endif
                                            ></span>

                                            {{ $tag->name }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-sm text-slate-600">
                                        {{ $tag->color ?? 'No color' }}
                                    </td>

                                    <td class="px-6 py-4 text-right text-sm text-slate-600">
                                        {{ $tag->issues_count }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="border-t border-slate-200 px-6 py-4">
                    {{ $tags->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection