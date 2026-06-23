<?php

use App\Http\Controllers\IssueController;
use App\Http\Controllers\IssueTagController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/projects');

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
