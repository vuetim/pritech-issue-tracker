@extends('layouts.app')

@section('title', 'Log in')

@section('content')
    <div class="mx-auto max-w-md">
        <div class="rounded-xl border border-slate-200 bg-white p-8 shadow-sm">
            <div class="text-center">
                <h1 class="text-2xl font-bold text-slate-900">Welcome back</h1>
                <p class="mt-2 text-sm text-slate-600">
                    Log in to manage projects and issues.
                </p>
            </div>

            <form
                method="POST"
                action="{{ route('login.store') }}"
                class="mt-8 space-y-5"
            >
                @csrf

                <div>
                    <label for="email" class="mb-2 block text-sm font-medium text-slate-700">
                        Email
                    </label>

                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        autocomplete="email"
                        autofocus
                        required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                    >

                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="mb-2 block text-sm font-medium text-slate-700">
                        Password
                    </label>

                    <input
                        id="password"
                        name="password"
                        type="password"
                        autocomplete="current-password"
                        required
                        class="w-full rounded-lg border border-slate-300 px-3 py-2 outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200"
                    >

                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <label class="flex items-center gap-2 text-sm text-slate-600">
                    <input
                        name="remember"
                        type="checkbox"
                        value="1"
                        class="rounded border-slate-300"
                    >

                    Remember me
                </label>

                <button
                    type="submit"
                    class="w-full rounded-lg bg-blue-600 px-4 py-2 font-semibold text-white hover:bg-blue-700"
                >
                    Log in
                </button>
            </form>

            <div class="mt-6 rounded-lg bg-slate-50 p-4 text-sm text-slate-600">
                <div class="font-semibold text-slate-700 pt-2">Demo account</div>
                <div class="mt-2">Email: alexmorgan@pritech.test</div>
                <div>Password: password</div>

                <div class="font-semibold text-slate-700 pt-2">Demo 2 account</div>
                <div class="mt-2">Email: jamielee@pritech.test</div>
                <div>Password: password</div>


                <div class="font-semibold text-slate-700 pt-2">Demo 3 account</div>
                <div class="mt-2">Email: taylorkim@pritech.test</div>
                <div>Password: password</div>
            </div>
        </div>
    </div>
@endsection