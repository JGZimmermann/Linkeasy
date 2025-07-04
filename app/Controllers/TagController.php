<?php

namespace App\Controllers;

use App\Models\Tag;
use Core\Http\Controllers\Controller;
use Core\Http\Request;
use Lib\FlashMessage;

class TagController extends Controller
{
  public function index(): void
  {
    $tags = Tag::all();
    $title = 'Todas as Tags';
    $this->render('tags/index', compact('tags', 'title'));
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
