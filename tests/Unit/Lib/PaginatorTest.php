<?php

namespace Tests\Unit\Lib;

use App\Models\Post;
use App\Models\User;
use Lib\Paginator;
use Tests\TestCase;

class PaginatorTest extends TestCase
{
    private Paginator $paginator;
    /** @var mixed[] $posts */
    private array $posts;

    public function setUp(): void
    {
        parent::setUp();
        $user = new User([
            'name' => 'User 1',
            'email' => 'fulano@example.com',
            'password' => '123456',
            'role' => 'PROFESSOR'
        ]);
        $user->save();

        for ($i = 0; $i < 10; $i++) {
            $post = new Post(['title' => "Post $i", 'user_id' => $user->id, 'body' => "Body $i", 'date' => '2025-07-05 22:57:10']);
            $post->save();
            $this->posts[] = $post;
        }
        $this->paginator = new Paginator(Post::class, 1, 5, 'posts', ['title']);
    }

    public function test_total_of_registers(): void
    {
        $this->assertEquals(10, $this->paginator->totalOfRegisters());
    }

    public function test_total_of_pages(): void
    {
        $this->assertEquals(2, $this->paginator->totalOfPages());
    }

    public function test_total_of_pages_when_the_division_is_not_exact(): void
    {
        $post = new Post(['title' => 'Post 11', 'user_id' => '1', 'body' => "Body 11", 'date' => '2025-07-05 22:57:10']);
        $post->save();
        $this->paginator = new Paginator(Post::class, 1, 5, 'posts', ['title']);

        $this->assertEquals(3, $this->paginator->totalOfPages());
    }

    public function test_previous_page(): void
    {
        $this->assertEquals(0, $this->paginator->previousPage());
    }

    public function test_next_page(): void
    {
        $this->assertEquals(2, $this->paginator->nextPage());
    }

    public function test_has_previous_page(): void
    {
        $this->assertFalse($this->paginator->hasPreviousPage());

        $paginator = new Paginator(Post::class, 2, 5, 'posts', ['title', 'user_id', 'body', 'date']);
        $this->assertTrue($paginator->hasPreviousPage());
    }

    public function test_has_next_page(): void
    {
        $this->assertTrue($this->paginator->hasNextPage());

        $paginator = new Paginator(Post::class, 2, 5, 'posts', ['title']);
        $this->assertFalse($paginator->hasNextPage());
    }

    public function test_is_page(): void
    {
        $this->assertTrue($this->paginator->isPage(1));
        $this->assertFalse($this->paginator->isPage(2));
    }

    public function test_entries_info(): void
    {
        $entriesInfo = 'Mostrando 1 - 5 de 10';
        $this->assertEquals($entriesInfo, $this->paginator->entriesInfo());
    }

    public function test_register_return_all(): void
    {
        $this->assertCount(5, $this->paginator->registers());

        $paginator = new Paginator(Post::class, 1, 10, 'posts', ['title', 'user_id', 'body', 'date']);
        $this->assertEquals($this->posts, $paginator->registers());
    }
}
