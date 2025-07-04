<?php

namespace App\Models;

use Core\Database\ActiveRecord\BelongsTo;
use Lib\Validations;
use Core\Database\ActiveRecord\Model;

/**
 * @property int $id
 * @property string $content
 * @property string $status
 */
class PostUpdate extends Model
{
    protected static string $table = 'posts_updates';
    protected static array $columns = ['content', 'status', 'user_id', 'post_id'];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function validates(): void
    {
      Validations::notEmpty('content', $this);
      Validations::notEmpty('status', $this);
      Validations::notEmpty('user_id', $this);
      Validations::notEmpty('post_id', $this);
    }
}

