<?php

namespace App\Models;

use App\Contracts\HasModelFilter;
use App\Contracts\HasSortable;
use App\Enums\PostStatus;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @package App\Models\Post
 * @property string $id
 * @property string $title
 * @property string $content
 * @property string $created_by
 * @property string $updated_by
 * @property PostStatus $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $deleted_at
 * @property Carbon $published_at
 *
 * @property-read User $author
 * @property-read User $updater
 */
class Post extends Model
{
    use HasFactory;
    use HasUlids;
    use Filterable;
    use HasModelFilter;
    use HasSortable;

    /**
     * The attributes that are sortables.
     *
     * @var array<int, string>
     */
    public array $sortables = [
        'title',
        'status',
        'created_at',
        'published_at',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'content',
        'created_by',
        'updated_by',
        'status',
        'published_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<int, string>
     */
    protected $casts = [
        'status' => PostStatus::class,
        'published_at' => 'datetime',
    ];

    public static function boot(): void
    {
        parent::boot();
        static::addGlobalScope('order', function (Builder $builder) {
            $builder->orderBy('created_at', 'desc');
        });
        self::creating(
            function (Post $post) {
                if (auth()->id()) {
                    $post->created_by = auth()->id();
                    $post->updated_by = auth()->id();
                } else {
                    $user = User::query()->first();
                    $post->created_by = $user->id;
                    $post->updated_by = $user->id;
                }
            }
        );
        self::updating(
            function (Post $post) {
                if (auth()->id()) {
                    $post->updated_by = auth()->id();
                } else {
                    $user = User::query()->first();
                    $post->updated_by = $user->id;
                }
            }
        );
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
