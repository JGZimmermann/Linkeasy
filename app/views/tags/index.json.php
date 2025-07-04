<?php


$tagsToJson = [];

foreach ($tags as $post) {
  $tagsToJson[] = ['id' => $post->getId(), 'title' => $post->getTitle()];
}

$json['tags'] = $tagsToJson;
$json['pagination'] = [
  'page' => $paginator->getPage(),
  'per_page' => $paginator->perPage(),
  'total_of_pages' => $paginator->totalOfPages(),
  'total_of_registers' => $paginator->totalOfRegisters(),
  'total_of_registers_of_page' => $paginator->totalOfRegistersOfPage(),
];
