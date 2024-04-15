<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Work extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    protected $fillable = [
        'title',
        'slug',
        'subtitle',
        'summary',
        'content',
        'thumbnail',
        'user_id',
        'owner',
        'role',
        'link',
        'date',
        'created_at',
        'updated_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
    public function registerMediaCollections() : void
    {
        $this->addMediaCollection('images');
    }
}
