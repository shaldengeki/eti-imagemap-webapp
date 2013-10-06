<?php
  if (isset($authScrapeRequest)) {
    $processing = ($authScrapeRequest['ScrapeRequest']['position'] === 1 || $authScrapeRequest['ScrapeRequest']['progress'] !== 0) ? True : False;
?>
<div class="alert alert-<?php echo $processing ? "success" : "info"; ?> alert-dismissable">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
<?php
    if ($processing) {
?>
  Your imagemap is currently being processed!
<?php
    } else {
?>
  Your imagemap is currently queued for processing, at position <?php echo $authScrapeRequest['ScrapeRequest']['position']; ?>.
<?php
    }
?>
</div>    
<?php
  }
?>