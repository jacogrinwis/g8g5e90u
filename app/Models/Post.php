<?php

namespace App\Models;

use Parsedown;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'category_id',
        'status',
    ];

    protected static function booted()
    {
        static::saving(function ($post) {
            // Genereer slug automatisch als die leeg is
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }

            // Genereer excerpt automatisch als die leeg is
            $parsedown = new Parsedown();

            if (empty($post->excerpt) && !empty($post->content)) {
                $html = $parsedown->text($post->content); // Markdown → HTML
                $post->excerpt = Str::limit(strip_tags($html), 150); // HTML → platte tekst → limit
            }
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }
}
