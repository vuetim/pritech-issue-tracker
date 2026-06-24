<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\IssueCommentController;
use App\Http\Controllers\IssueController;
use App\Http\Controllers\IssueMemberController;
use App\Http\Controllers\IssueTagController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('login.store');
});

Route::middleware('auth')->group(function (): void {
    Route::redirect('/', '/projects');

    Route::post(
        '/logout',
        [AuthenticatedSessionController::class, 'destroy']
    )->name('logout');

    Route::resource('projects', ProjectController::class);
    Route::resource('issues', IssueController::class);

    Route::resource('tags', TagController::class)
        ->only(['index', 'store']);

    Route::post(
        '/issues/{issue}/tags',
        [IssueTagController::class, 'store']
    )->name('issues.tags.store');

    Route::delete(
        '/issues/{issue}/tags/{tag}',
        [IssueTagController::class, 'destroy']
    )->name('issues.tags.destroy');

    Route::get(
        '/issues/{issue}/comments',
        [IssueCommentController::class, 'index']
    )->name('issues.comments.index');

    Route::post(
        '/issues/{issue}/comments',
        [IssueCommentController::class, 'store']
    )->name('issues.comments.store');
    Route::post(
        '/issues/{issue}/members',
        [IssueMemberController::class, 'store']
    )->name('issues.members.store');

    Route::delete(
        '/issues/{issue}/members/{user}',
        [IssueMemberController::class, 'destroy']
    )->name('issues.members.destroy');
});
