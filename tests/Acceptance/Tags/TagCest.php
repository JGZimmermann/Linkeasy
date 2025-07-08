<?php

namespace Tests\Acceptance\Tag;

use App\Models\Tag;
use App\Models\User;
use Tests\Acceptance\BaseAcceptanceCest;
use Tests\Support\AcceptanceTester;

class TagCest extends BaseAcceptanceCest
{
    public function seeMyTags(AcceptanceTester $page): void
    {
        $user = new User([
        'name' => 'User 1',
        'email' => 'fulano@example.com',
        'password' => '123456',
        'role' => 'PROFESSOR'
        ]);
        $user->save();

        $tag = new Tag(['name' => 'Tag 1']);
        $tag->save();

        $page->login($user->email, $user->password);

        $page->amOnPage('/tags');

        $page->see($tag->name);
    }
}
