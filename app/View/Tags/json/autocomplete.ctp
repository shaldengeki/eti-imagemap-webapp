<?php
  if (isset($tags)) {
    $formattedTags = [];
    foreach ($tags as $tag) {
      $formattedTags[] = ['id' => intval($tag['Tag']['id']), 'name' => $tag['Tag']['name']];
    }
    // ksort($formattedTags);
    echo json_encode($formattedTags);
  }
?>