<?php

namespace App\Controllers;

use App\Models\Post;
use App\Models\Tag;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\FlashMessage;

class TagController extends Controller
{
  public function index(Request $request): void
  {
    $tags = Tag::all();
    $paginator = Tag::paginate(page: $request->getParam('page', 1));
    $title = 'Todas as Tags';
    $this->render('tags/index', compact('tags', 'title', 'paginator'));
  }

  public function search(): void
  {
    $tags = Tag::all();
    $tagsArray = [];
    foreach ($tags as $tag) {
      $tagsArray[] = [
        'id' => $tag->id,
        'name' => $tag->name,
      ];
    }
    echo json_encode(['tags' => $tagsArray]);
  }

  public function postsFilteredByTags(Request $request): void
  {
    $params = $request->getParams();

    $tags = Tag::all();
    $tagId = $request->getParam('id');

    $tag = Tag::findById($tagId);
    if (!$tag) {
      FlashMessage::danger('Tag não existe!');
      $this->render('posts/index', [
        'posts' => Post::all(),
        'title' => 'Posts Registrados',
        'tags' => Tag::all(),
        'paginator' => Post::paginate(page:  $request->getParam('page', 1)),
      ]);
      return;
    }

    $tagName = Tag::findById($params['id'])->name;

    $posts = Tag::findById($params['id'])->posts()->get();

    $title = "Posts filtrados por {$tagName}";

    $this->render('posts/filter', compact( 'posts', 'title', 'tags'));
  }

  public function new(): void
  {
    $tag = new Tag();
    $title = 'Nova Tag';
    $this->render('tags/new', compact('tag', 'title'));
  }

  public function create(Request $request): void
  {
    $params = $request->getParam('tag');
    $tag = new Tag($params);

    if ($tag->save()) {
      FlashMessage::success('Tag criada com sucesso!');
      $this->redirectTo(route('tags.index'));
    } else {
      FlashMessage::danger('Erro ao criar a tag.');
      $title = 'Nova Tag';
      $this->render('tags/new', compact('tag', 'title'));
    }
  }

  public function edit(Request $request): void
  {
    $tag = Tag::findById($request->getParam('id'));
    $title = "Editar Tag #{$tag->id}";
    $this->render('tags/edit', compact('tag', 'title'));
  }

  public function update(Request $request): void
  {
    $tag = Tag::findById($request->getParam('id'));
    $params = $request->getParam('tag');
    $tag->name = $params['name'];

    if ($tag->save()) {
      FlashMessage::success('Tag atualizada com sucesso!');
      $this->redirectTo(route('tags.index'));
    } else {
      FlashMessage::danger('Erro ao atualizar a tag.');
      $title = "Editar Tag #{$tag->id}";
      $this->render('tags/edit', compact('tag', 'title'));
    }
  }

  public function destroy(Request $request): void
  {
    $tag = Tag::findById($request->getParam('id'));
    $tag->destroy();

    FlashMessage::success('Tag excluída com sucesso!');
    $this->redirectTo(route('tags.index'));
  }
}
