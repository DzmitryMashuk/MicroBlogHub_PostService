<?php

declare(strict_types=1);

namespace App\Domain\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
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
