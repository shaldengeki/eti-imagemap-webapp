<?php
  if (isset($tags)) {
    $formattedTags = [];
    foreach ($tags as $tag) {
      $formattedTags[$tag['Tag']['name']] = intval($tag['Tag']['id']);
    }
    ksort($formattedTags);
    echo json_encode($formattedTags);
  }
?>