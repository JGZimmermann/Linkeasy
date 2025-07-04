<?php

namespace App\Models;

use Core\Database\ActiveRecord\BelongsTo;
use Core\Database\ActiveRecord\BelongsToMany;
use Core\Database\ActiveRecord\HasMany;
use Lib\Validations;
use Core\Database\ActiveRecord\Model;

/**
 * @property int $id
 * @property string $title
 * @property string $body
 * @property string $date
 */
class Post extends Model
{
    protected static string $table = 'posts';
    protected static array $columns = ['title', 'body', 'date', 'user_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function postUpdates(): HasMany
    {
        return $this->hasMany(PostUpdate::class, 'post_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'tags_in_posts', 'post_id', 'tag_id');
    }


  public function validates(): void
    {
        Validations::notEmpty('title', $this);
        Validations::notEmpty('body', $this);
        Validations::notEmpty('date', $this);
        Validations::notEmpty('user_id', $this);
    }
}
