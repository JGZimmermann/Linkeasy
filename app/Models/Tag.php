<?php

namespace App\Models;

use Core\Database\ActiveRecord\HasMany;
use Core\Database\ActiveRecord\BelongsToMany;
use Lib\Validations;
use Core\Database\ActiveRecord\Model;

/**
 * @property int $id
 * @property string $name
 */
class Tag extends Model
{
    protected static string $table = 'tags';
    protected static array $columns = ['name'];

  public function tagsInPosts(): HasMany
  {
    return $this->hasMany(TagInPost::class, 'tag_id');
  }

  public function posts(): BelongsToMany
  {
    return $this->belongsToMany(Post::class, 'tags_in_posts', 'tag_id', 'post_id');
  }

    public function validates(): void
    {
      Validations::notEmpty('name', $this);
    }
}

