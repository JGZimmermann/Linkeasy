<?php

namespace Tests\Unit\Controllers;

use App\Models\Tag;
use App\Models\User;

class TagControllerTest extends ControllerTestCase
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
    public function test_list_all_tags(): void
    {
        $tags[] = new Tag(['name' => 'Tag 1']);
        $tags[] = new Tag(['name' => 'Tag 2']);

        foreach ($tags as $tag) {
            $tag->save();
        }

        $response = $this->get(action: 'index', controllerName: 'App\Controllers\TagController');

        foreach ($tags as $tag) {
            $this->assertMatchesRegularExpression("/{$tag->name}/", $response);
        }
    }

    public function test_successfully_create_tag(): void
    {
        $params = ['tag' => ['name' => 'Tag test']];

        $response = $this->post(
            action: 'create',
            controllerName: 'App\Controllers\TagController',
            params: $params
        );

        $this->assertMatchesRegularExpression("/Location: \/tags/", $response);
    }

    public function test_unsuccessfully_create_tag(): void
    {
        $params = ['tag' => ['name' => '']];

        $response = $this->post(
            action: 'create',
            controllerName: 'App\Controllers\TagController',
            params: $params
        );

        $this->assertMatchesRegularExpression("/não pode ser vazio!/", $response);
    }
}
