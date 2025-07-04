<?php

namespace Tests\Unit\Models;

use App\Models\Post;
use App\Models\User;
use Tests\TestCase;

class ProblemTest extends TestCase
{
    private Post $problem;
    private User $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = new User([
            'name' => 'User 1',
            'email' => 'fulano@example.com',
            'password' => '123456',
            'password_confirmation' => '123456'
        ]);
        $this->user->save();

        $this->problem = new Post(['title' => 'Post 1', 'user_id' => $this->user->id]);
        $this->problem->save();
    }

    public function test_should_create_new_problem(): void
    {
        $this->assertTrue($this->problem->save());
        $this->assertCount(1, Post::all());
    }

    public function test_all_should_return_all_problems(): void
    {
        $problems[] = $this->problem;
        $problems[] = $this->user->problems()->new(['title' => 'Post 2']);
        $problems[1]->save();

        $all = Post::all();
        $this->assertCount(2, $all);
        $this->assertEquals($problems, $all);
    }

    public function test_destroy_should_remove_the_problem(): void
    {
        $problem2 = $this->user->problems()->new(['title' => 'Post 2']);

        $problem2->save();
        $problem2->destroy();

        $this->assertCount(1, Post::all());
    }

    public function test_set_title(): void
    {
        $problem = $this->user->problems()->new(['title' => 'Post 2']);
        $this->assertEquals('Post 2', $problem->title);
    }

    public function test_set_id(): void
    {
        $problem = $this->user->problems()->new(['title' => 'Post 2']);
        $problem->id = 7;

        $this->assertEquals(7, $problem->id);
    }

    public function test_errors_should_return_title_error(): void
    {
        $problem = $this->user->problems()->new(['title' => 'Post 2']);
        $problem->title = '';

        $this->assertFalse($problem->isValid());
        $this->assertFalse($problem->save());
        $this->assertFalse($problem->hasErrors());

        $this->assertEquals('não pode ser vazio!', $problem->errors('title'));
    }

    public function test_find_by_id_should_return_the_problem(): void
    {
        $problem2 = $this->user->problems()->new(['title' => 'Post 2']);
        $problem1 = $this->user->problems()->new(['title' => 'Post 1']);
        $problem3 = $this->user->problems()->new(['title' => 'Post 3']);

        $problem1->save();
        $problem2->save();
        $problem3->save();

        $this->assertEquals($problem1, Post::findById($problem1->id));
    }

    public function test_find_by_id_should_return_null(): void
    {
        $problem = $this->user->problems()->new(['title' => 'Post 2']);
        $problem->save();

        $this->assertNull(Post::findById(7));
    }
}
