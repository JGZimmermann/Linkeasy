<?php

namespace Tests\Acceptance\Post;

use App\Models\Post;
use App\Models\User;
use Tests\Acceptance\BaseAcceptanceCest;
use Tests\Support\AcceptanceTester;

class PostCest extends BaseAcceptanceCest
{
    public function seeMyPosts(AcceptanceTester $page): void
    {
        $user = new User([
        'name' => 'User 1',
        'email' => 'fulano@example.com',
        'password' => '123456',
        'password_confirmation' => '123456'
        ]);
        $user->save();

        $post = new Post(['title' => 'Post 1','body' => 'Body 1', 'user_id' => $user->id, 'date' => '2025-07-05 22:57:10']);
        $post->save();

        $page->login($user->email, $user->password);

        $page->amOnPage('/posts');

        $tableSelector = 'table';

        $page->see('#1', '//table//tr[1]//td[1]');
        $page->see('Post 1', '//table//tr[1]//td[2]');
    }
}
