<?php

namespace App\Models;

use App\Enums\IssuePriority;
use App\Enums\IssueStatus;
use Database\Factories\IssueFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Issue extends Model
{
    /** @use HasFactory<IssueFactory> */
    use HasFactory;

    protected $fillable = [
        'project_id',
        'title',
        'description',
        'status',
        'priority',
        'due_date',
    ];

    protected function casts(): array
    {
        return [
            'status' => IssueStatus::class,
            'priority' => IssuePriority::class,
            'due_date' => 'date',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when(
                $filters['search'] ?? null,
                function (Builder $query, string $search): void {
                    $query->where(
                        function (Builder $query) use ($search): void {
                            $query
                                ->where('title', 'like', "%{$search}%")
                                ->orWhere('description', 'like', "%{$search}%");
                        }
                    );
                }
            )
            ->when(
                $filters['status'] ?? null,
                fn (Builder $query, string $status) => $query->where('status', $status)
            )
            ->when(
                $filters['priority'] ?? null,
                fn (Builder $query, string $priority) => $query->where('priority', $priority)
            )
            ->when(
                $filters['tag'] ?? null,
                fn (Builder $query, string $tagId) => $query->whereHas(
                    'tags',
                    fn (Builder $tagQuery) => $tagQuery->where('tags.id', $tagId)
                )
            );
    }

    public function members(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
