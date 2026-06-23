@extends('layouts.app')

@section('title', 'Edit project')

@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-900">Edit project</h1>
            <p class="mt-1 text-sm text-slate-600">
                Update {{ $project->name }}.
            </p>
        </div>

        <form
            method="POST"
            action="{{ route('projects.update', $project) }}"
            class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm"
        >
            @csrf
            @method('PUT')

            @include('projects._form', [
                'submitLabel' => 'Save changes',
            ])
        </form>
    </div>
@endsection