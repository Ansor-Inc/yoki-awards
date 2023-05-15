<?php

namespace App\Traits;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

trait HasTagsTrait
{
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function syncTags(array $tags): static
    {
        $tagIds = collect($tags)->map(fn($tag) => Tag::query()->firstOrCreate(['name' => $tag])->id);
        $this->tags()->sync($tagIds);

        return $this;
    }

    public function getModelTags(): Collection
    {
        return DB::table('taggables')
            ->join('tags', 'tags.id', '=', 'taggables.tag_id')
            ->where('taggable_type', self::class)
            ->select('name')
            ->distinct()
            ->pluck('name');
    }
}
