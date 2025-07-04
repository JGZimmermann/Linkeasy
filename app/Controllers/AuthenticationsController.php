<?php

namespace App\Controllers;

use App\Models\User;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\Authentication\Auth;
use Lib\FlashMessage;

class AuthenticationsController extends Controller
{
    protected string $layout = 'login';

    public function new(): void
    {
        $this->render('authentications/new');
    }

    public function newUser(): void
    {
        $this->render('authentications/newUser');
    }

    public function createUser(Request $request): void
    {
        $params = $request->getParam('user');

        if(User::findByEmail($params["email"])){
          FlashMessage::danger('Usuário já cadastrado!');
          $this->redirectTo(route('users.new'));
        }
        $user = new User($params);
        if ($user->save()) {
            FlashMessage::success('Usuário criado com sucesso!');
            $this->redirectTo(route('posts.index'));
        } else {
            FlashMessage::danger('Existem dados incorretos! Por favor verifique!');
            $this->redirectTo(route('users.new'));
        }
    }

    public function authenticate(Request $request): void
    {
        $params = $request->getParam('user');
        $user = User::findByEmail($params['email']);

        if ($user && $user->authenticate($params['password'])) {
            Auth::login($user);

            FlashMessage::success('Login realizado com sucesso!');
            $this->redirectTo(route('posts.index'));
        } else {
            FlashMessage::danger('Email e/ou senha inválidos!');
            $this->redirectTo(route('users.login'));
        }
    }

    public function destroy(): void
    {
        Auth::logout();
        FlashMessage::success('Logout realizado com sucesso!');
        $this->redirectTo(route('users.login'));
    }
}
