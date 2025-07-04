<?php

$postsToJson = [];
$tagsToJson = [];

foreach ($posts as $post) {
  $postsToJson[] = ['id' => $post->getId(), 'title' => $post->getTitle()];
}

foreach ($tags as $tag) {
  $tagsToJson[] = ['id' => $tag->getId(), 'name' => $tag->getName()];
}

$json['posts'] = $postsToJson;
$json['tags'] = $tagsToJson;
$json['pagination'] = [
    'page'                       => $paginator->getPage(),
    'per_page'                   => $paginator->perPage(),
    'total_of_pages'             => $paginator->totalOfPages(),
    'total_of_registers'         => $paginator->totalOfRegisters(),
    'total_of_registers_of_page' => $paginator->totalOfRegistersOfPage(),
];
