<?php

namespace Tests\Unit\Controllers;

use App\Models\Post;
use App\Models\User;

class PostControllerTest extends ControllerTestCase
{
    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = new User([
        'name' => 'User 1',
        'email' => 'fulano@example.com',
        'password' => '123456',
        'role' => 'PROFESSOR'
        ]);
        $this->user->save();
        $_SESSION['user']['id'] = $this->user->id;
    }

    public function test_list_all_posts(): void
    {
        $posts[] = new Post(['title' => 'Post 1', 'user_id' => $this->user->id, 'body' => "Body 1", 'date' => '2025-07-05 22:57:10']);
        $posts[] = new Post(['title' => 'Post 2', 'user_id' => $this->user->id, 'body' => "Body 2", 'date' => '2025-07-05 22:57:10']);

        foreach ($posts as $post) {
            $post->save();
        }

        $response = $this->get(action: 'index', controllerName: 'App\Controllers\PostController');

        foreach ($posts as $post) {
            $this->assertMatchesRegularExpression("/{$post->title}/", $response);
        }
    }

    public function test_show_post(): void
    {
        $post = new Post(['title' => 'Post 1', 'user_id' => $this->user->id, 'body' => "Body 1", 'date' => '2025-07-05 22:57:10']);
        $post->save();

        $response = $this->get(
            action: 'show',
            controllerName: 'App\Controllers\PostController',
            params: ['id' => $post->id]
        );

        $this->assertMatchesRegularExpression("/Visualização do Post #{$post->id}/", $response);
        $this->assertMatchesRegularExpression("/{$post->title}/", $response);
    }

    public function test_successfully_create_post(): void
    {
        $params = ['post' => ['title' => 'Post test', 'body' => "Body 1", 'date' => '2025-07-20 22:57:10']];

        $response = $this->post(
            action: 'create',
            controllerName: 'App\Controllers\PostController',
            params: $params
        );

        $this->assertMatchesRegularExpression("/Location: \/posts/", $response);
    }

    public function test_unsuccessfully_create_post(): void
    {
        $params = ['problem' => ['title' => '']];

        $response = $this->post(
            action: 'create',
            controllerName: 'App\Controllers\PostController',
            params: $params
        );

        $this->assertMatchesRegularExpression("/não pode ser vazio!/", $response);
    }

    public function test_edit_post(): void
    {
        $post = new Post(['title' => 'Post 1', 'user_id' => $this->user->id, 'body' => "Body 1", 'date' => '2025-07-05 22:57:10']);
        $post->save();

        $response = $this->get(
            action: 'edit',
            controllerName: 'App\Controllers\PostController',
            params: ['id' => $post->id]
        );

        $this->assertMatchesRegularExpression("/Editar Post #{$post->id}/", $response);

        $regex = '/<input\s[^>]*type=[\'"]text[\'"][^>]*name=[\'"]post\[title\][\'"][^>]*value=[\'"]Post 1[\'"][^>]*>/i';
        $this->assertMatchesRegularExpression($regex, $response);
    }


    public function test_successfully_update_post(): void
    {
        $post = new Post(['title' => 'Post 1', 'user_id' => $this->user->id, 'body' => "Body 1", 'date' => '2025-07-20 22:57:10']);
        $post->save();
        $params = ['id' => $post->id, 'post' => ['title' => 'Post updated','body' => $post->body, 'date' => $post->date]];

        $response = $this->put(
            action: 'update',
            controllerName: 'App\Controllers\PostController',
            params: $params
        );

        $this->assertMatchesRegularExpression("/Location: \/posts/", $response);
    }

    public function test_unsuccessfully_update_post(): void
    {
        $post = new Post(['title' => 'Post 1', 'user_id' => $this->user->id, 'body' => "Body 1", 'date' => '2025-07-05 22:57:10']);
        $post->save();
        $params = ['id' => $post->id, 'problem' => ['title' => '']];

        $response = $this->put(
            action: 'update',
            controllerName: 'App\Controllers\PostController',
            params: $params
        );

      $this->assertMatchesRegularExpression(
        "/Location: \/posts\/" . $post->id . "\/edit/",
        $response
      );
    }
}
