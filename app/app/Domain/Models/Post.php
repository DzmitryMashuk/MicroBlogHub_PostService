<?php

declare(strict_types=1);

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'content',
        'category_id',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'post_tags');
    }

    public function getUserIdAttribute(): int
    {
        return $this->attributes['user_id'];
    }

    public function setUserIdAttribute($value): void
    {
        $this->attributes['user_id'] = $value;
    }

    public function getCategoryIdAttribute(): int
    {
        return $this->attributes['category_id'];
    }

    public function setCategoryIdAttribute($value): void
    {
        $this->attributes['category_id'] = $value;
    }

    public function getCreatedAtAttribute(): ?string
    {
        return $this->attributes['created_at'] ?? null;
    }

    public function getUpdatedAtAttribute(): ?string
    {
        return $this->attributes['updated_at'] ?? null;
    }
}
