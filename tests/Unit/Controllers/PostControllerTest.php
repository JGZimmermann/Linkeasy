<?php

namespace Tests\Unit\Controllers;

use App\Models\Post;
use App\Models\User;
use DateTime;

class PostControllerTest extends ControllerTestCase
{
    public function test_list_all_problems(): void
    {
        $user = new User([
            'name' => 'User 1',
            'email' => 'fulano@example.com',
            'password' => '123456',
            'role' => 'PROFESSOR'
        ]);
        $user->save();
        $_SESSION['user']['id'] = $user->id;

        $posts[] = new Post(['title' => 'Post 1', 'user_id' => $user->id, 'body' => 'Body 1', 'date' => '2025-07-05 22:57:10']);
        $posts[] = new Post(['title' => 'Post 2',  'user_id' => $user->id, 'body' => 'Body 2', 'date' => '2025-07-05 22:57:10']);

        foreach ($posts as $post) {
            $post->save();
        }

        $response = $this->get(action: 'index', controller: 'App\Controllers\PostController');

        foreach ($posts as $post) {
            $this->assertMatchesRegularExpression("/{$post->title}/", $response);
        }
    }

}
