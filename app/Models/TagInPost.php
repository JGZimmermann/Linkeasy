<?php

namespace App\Models;

use Core\Database\ActiveRecord\BelongsTo;
use Lib\Validations;
use Core\Database\ActiveRecord\Model;

/**
 * @property int $id
 */
class TagInPost extends Model
{
    protected static string $table = 'tags_in_posts';
    protected static array $columns = ['tag_id', 'post_id'];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class, 'tag_id');
    }

    public function validates(): void
    {
        Validations::notEmpty('tag_id', $this);
        Validations::notEmpty('post_id', $this);
    }
}
