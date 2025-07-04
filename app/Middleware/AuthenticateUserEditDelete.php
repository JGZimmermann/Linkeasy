<?php

namespace App\Middleware;

use Core\Http\Middleware\Middleware;
use Core\Http\Request;
use Lib\Authentication\Auth;
use Lib\FlashMessage;
use App\Models\Post;

class AuthenticateUserEditDelete implements Middleware
{
  public function handle(Request $request): void
  {
    $params = $request->getParams();
    $post = Post::findById($params['id']);

    if (!Auth::checkEditPriveleges($post)) {
      FlashMessage::danger('Você não tem permissão para acessar essa página');
      $this->redirectTo(route('posts.index'));
    }
  }

  private function redirectTo(string $location): void
  {
    header('Location: ' . $location);
    exit;
  }
}
