@extends('layouts.app')

@section('title', 'Create issue')

@section('content')
    <div class="mx-auto max-w-3xl">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-slate-900">Create issue</h1>
            <p class="mt-1 text-sm text-slate-600">
                Add an issue and assign it to a project.
            </p>
        </div>

        <form
            method="POST"
            action="{{ route('issues.store') }}"
            class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm"
        >
            @csrf

            @include('issues._form', [
                'submitLabel' => 'Create issue',
            ])
        </form>
    </div>
@endsection