<?php

namespace Database\Populate;

use App\Models\Post;

class PostsPopulate
{
    public static function populate()
    {
      date_default_timezone_set('Brazil/East');
      $data =  [
        'title' => 'Teste',
        'body' => 'Teste Body, isso é um teste teste teste teste',
        'date' => date('Y-m-d H:i:s'),
        'user_id' => '1'
      ];

      $post = new Post($data);
      $post->save();
  }
}
