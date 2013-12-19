<?php
class ImageHelper extends AppHelper {
  public function etiUrl($image) {
    return 'http://i'.$image['server'].'.endoftheinter.net/i/n/'.$image['hash'].'/'.$image['filename'].'.'.$image['type'];
  }

  public function etiThumbUrl($image) {
    return 'http://i'.$image['server'].'.endoftheinter.net/i/t/'.$image['hash'].'/'.$image['filename'].'.jpg';
  }

  public function etiImageTag($image) {
    return '<img src="http://i'.$image['server'].'.endoftheinter.net/i/n/'.$image['hash'].'/'.$image['filename'].'.'.$image['type'].'" />';
  }
}
?>