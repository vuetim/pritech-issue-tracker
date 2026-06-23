@extends('layouts.app')

@section('title', 'Create project')

@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-900">Create project</h1>
            <p class="mt-1 text-sm text-slate-600">
                Add a new project and define its schedule.
            </p>
        </div>

        <form
            method="POST"
            action="{{ route('projects.store') }}"
            class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm"
        >
            @csrf

            @include('projects._form', [
                'submitLabel' => 'Create project',
            ])
        </form>
    </div>
@endsection