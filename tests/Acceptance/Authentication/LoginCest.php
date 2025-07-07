<?php

namespace Tests\Acceptance\Authentication;

use App\Models\User;
use Tests\Acceptance\BaseAcceptanceCest;
use Tests\Support\AcceptanceTester;

class LoginCest extends BaseAcceptanceCest
{
  public function loginSuccessfully(AcceptanceTester $page): void
  {
    $user = new User([
      'name' => 'User 1',
      'email' => 'fulano@example.com',
      'password' => '123456',
      'role' => 'PROFESSOR'
    ]);
    $user->save();

    $page->amOnPage('/login');

    $page->fillField('user[email]', $user->email);
    $page->fillField('user[password]', $user->password);

    $page->click('Entrar');

    $page->see('Login realizado com sucesso!');
    $page->seeInCurrentUrl('/posts');
  }

  public function loginUnsuccessfully(AcceptanceTester $page): void
  {
    $page->amOnPage('/login');

    $page->fillField('user[email]', 'fulano@example.com');
    $page->fillField('user[password]', 'wrong_password');

    $page->click('Entrar');

    $page->see('Email e/ou senha inválidos!');
    $page->seeInCurrentUrl('/login');
  }
}
