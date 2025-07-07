<?php

namespace Tests\Unit\Models;

use App\Models\Post;
use App\Models\User;
use DateTime;
use Tests\TestCase;

class PostTest extends TestCase
{
    private Post $post;
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

        $this->post = new Post(['title' => 'Post 1', 'user_id' => $this->user->id, 'body' => 'Body 1', 'date' => '2025-07-05 22:57:10']);
        $this->post->save();
    }

    public function test_should_create_new_post(): void
    {
        $this->assertTrue($this->post->save());
        $this->assertCount(1, Post::all());
    }

    public function test_all_should_return_all_posts(): void
    {
        $posts[] = $this->post;
        $posts[] = $this->user->post()->new(['title' => 'Post 2', 'body' => 'Body 1', 'date' => '2025-07-05 22:57:10']);
        $posts[1]->save();

        $all = Post::all();
        $this->assertCount(2, $all);
        $this->assertEquals($posts, $all);
    }

    public function test_destroy_should_remove_the_post(): void
    {
        $post2 = $this->user->post()->new(['title' => 'Post 2', 'body' => 'Body 1', 'date' => '2025-07-05 22:57:10']);

        $post2->save();
        $post2->destroy();

        $this->assertCount(1, Post::all());
    }

    public function test_set_title(): void
    {
        $post = $this->user->post()->new(['title' => 'Post 2', 'body' => 'Body 1', 'date' => '2025-07-05 22:57:10']);
        $this->assertEquals('Post 2', $post->title);
    }

    public function test_set_id(): void
    {
        $post = $this->user->post()->new(['title' => 'Post 2', 'body' => 'Body 1', 'date' => '2025-07-05 22:57:10']);
        $post->id = 7;

        $this->assertEquals(7, $post->id);
    }

    public function test_errors_should_return_title_error(): void
    {
        $post = $this->user->post()->new(['title' => 'Post 2', 'body' => 'Body 1', 'date' => '2025-07-05 22:57:10']);
        $post->title = '';

        $this->assertFalse($post->isValid());
        $this->assertFalse($post->save());
        $this->assertFalse($post->hasErrors());

        $this->assertEquals('não pode ser vazio!', $post->errors('title'));
    }

    public function test_find_by_id_should_return_the_post(): void
    {
        $post2 = $this->user->post()->new(['title' => 'Post 2', 'body' => 'Body 1', 'date' => '2025-07-05 22:57:10']);
        $post1 = $this->user->post()->new(['title' => 'Post 1', 'body' => 'Body 1', 'date' => '2025-07-05 22:57:10']);
        $post3 = $this->user->post()->new(['title' => 'Post 3', 'body' => 'Body 1', 'date' => '2025-07-05 22:57:10']);

        $post2->save();
        $post1->save();
        $post3->save();

        $this->assertEquals($post1, Post::findById($post1->id));
    }

    public function test_find_by_id_should_return_null(): void
    {
        $post = $this->user->post()->new(['title' => 'Post 2', 'body' => 'Body 1', 'date' => '2025-07-05 22:57:10']);
        $post->save();

        $this->assertNull(Post::findById(7));
    }
}
