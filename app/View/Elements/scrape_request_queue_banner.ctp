<?php
  if (isset($authScrapeRequest)) {
    $processing = ($authScrapeRequest['ScrapeRequest']['position'] === 1 || $authScrapeRequest['ScrapeRequest']['progress'] !== 0) ? True : False;
    $error = $authScrapeRequest['ScrapeRequest']['progress'] < 0 ? ScrapeRequest::$ERRORS[$authScrapeRequest['ScrapeRequest']['progress']] : Null;
    $alertType = $error ? "danger" : ($processing ? "success" : "info");
?>
<div class="alert alert-<?php echo $alertType; ?> alert-dismissable">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
<?php
    if ($processing && !$error) {
?>
  Your imagemap is currently being processed!
<?php
    } elseif ($error) {
?>
  There was an error processing your imagemap: <?php echo $error; ?>.
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