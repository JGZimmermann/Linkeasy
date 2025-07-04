<?php

namespace App\Controllers;

use App\Models\Post;
use App\Models\TagInPost;
use App\Models\Tag;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\FlashMessage;

class PostController extends Controller
{
    public function index(Request $request): void
    {
        $paginator = Post::paginate(page: $request->getParam('page', 1));
        //$paginator = $this->current_user->post()->paginate(page: $request->getParam('page', 1));
        $posts = $paginator->registers();


        $title = 'Posts Registrados';

        if ($request->acceptJson()) {
            $this->renderJson('posts/index', compact('paginator', 'posts', 'title'));
        } else {
            $this->render('posts/index', compact('paginator', 'posts', 'title'));
        }
    }

    public function show(Request $request): void
    {
        $params = $request->getParams();

        $post = Post::findById($params['id']);
        //$post = $this->current_user->post()->findById($params['id']);

        $title = "Visualização do Post #{$post->id}";
        $this->render('posts/show', compact('post', 'title'));
    }

    public function new(): void
    {
        $post = $this->current_user->post()->new();
        $tags = Tag::all();
        $title = 'Novo Post';
        $this->render('posts/new', compact('post', 'title', 'tags'));
    }

  public function create(Request $request): void
  {
    $params = $request->getParam('post');
    $post = new Post($params);

    $post->user_id = $this->current_user->id;

    if ($post->save()) {
      $tags = $request->getParam('tags');

      if (is_array($tags)) {
        foreach ($tags as $tagId) {
          $tagInPost = new TagInPost([
            'post_id' => $post->id,
            'tag_id' => $tagId
          ]);
          $tagInPost->save();
        }
      }

      FlashMessage::success('Post registrado com sucesso!');
      $this->redirectTo(route('posts.index'));
    } else {
      FlashMessage::danger('Existem dados incorretos! Por favor, verifique!');
      $title = 'Novo Post';
      $this->render('posts/new', compact('post', 'title'));
    }
  }


  public function edit(Request $request): void
    {
        $params = $request->getParams();
        $post = Post::findById($params['id']);
        //$post = $this->current_user->post()->findById($params['id']);
        $tags = Tag::all();
        $title = "Editar Post #{$post->id}";
        $this->render('posts/edit', compact('post', 'title', 'tags'));
    }

    public function update(Request $request): void
    {
      $id = $request->getParam('id');
      $params = $request->getParam('post');
      $tags = $request->getParam('tags');

      if (!$params) {
        FlashMessage::danger('Erro ao atualizar: dados não recebidos corretamente.');
        $this->redirectTo(route('posts.edit', ['id' => $id]));
        return;
      }

      $post = Post::findById($id);
      //$post = $this->current_user->post()->findById($id);

      $post->title = $params['title'] ?? '';
      $post->body = $params['body'] ?? '';
      $post->date = $params['date'] ?? null;

      if ($post->save()) {
        if (is_array($tags)) {
          $usedTags = TagInPost::where(['post_id' => $id]);
          foreach ($usedTags as $tag) {
            $tag->destroy();
          }
          //$usedTags->destroy();
          foreach ($tags as $tagId) {
            $tagInPost = new TagInPost([
              'post_id' => $post->id,
              'tag_id' => $tagId
            ]);
            $tagInPost->save();
          }
        }
      FlashMessage::success('Post atualizado com sucesso!');
      $this->redirectTo(route('posts.index'));

    } else {
          FlashMessage::danger('Existem dados incorretos. Por favor, revise o formulário.');
          $title = "Editar Post #{$post->id}";
          $this->render('posts/edit', compact('post', 'title'));
        }
    }


    public function destroy(Request $request): void
    {
        $params = $request->getParams();

        $post= $this->current_user->post()->findById($params['id']);
        $post->destroy();

        FlashMessage::success('Post removido com sucesso!');
        $this->redirectTo(route('posts.index'));
    }
}
