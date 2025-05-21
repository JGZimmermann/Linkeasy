<?php

namespace App\Controllers;

use App\Models\User;
use Core\Http\Request;
use Lib\Authentication\Auth;
use Lib\FlashMessage;

class AuthenticationsController
{
    private string $layout = 'login';

    public function new(): void
    {
        $this->render('new');
    }

    public function newUser(): void
    {
        $this->render('newUser');
    }

    public function createUser(Request $request): void
    {
        $params = $request->getParam('user');

        if(User::findByEmail($params["email"])){
          FlashMessage::danger('Usuário já cadastrado!');
          $this->redirectTo(route('users.new'));
        } else{
          $user = new User(
            name: $params["name"],
            email: $params["email"],
            password: $params["password"],
            password_confirmation: $params["password"],
            role: $params["role"]
          );
          $user->save();
          FlashMessage::success('Usuário criado com sucesso!');
          $this->redirectTo(route('problems.index'));
        }
    }

    public function authenticate(Request $request): void
    {
        $params = $request->getParam('user');
        $user = User::findByEmail($params['email']);

        if ($user && $user->authenticate($params['password'])) {
            Auth::login($user);

            FlashMessage::success('Login realizado com sucesso!');
            $this->redirectTo(route('problems.index'));
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


    /**
     * @param array<string, mixed> $data
     */
    private function render(string $view, array $data = []): void
    {
        extract($data);

        $view = '/var/www/app/views/authentications/' . $view . '.phtml';
        require '/var/www/app/views/layouts/' . $this->layout . '.phtml';
    }

    private function redirectTo(string $location): void
    {
        header('Location: ' . $location);
        exit;
    }
}
