<?php
  if (isset($results)) {
    if (!is_array($results)) {
      echo $results;
    } else {
      $success = True;
      foreach ($results as $result) {
        $success = $success && (bool) $result;
      }
      echo intval($success);
    }
  } else {
?>
<div class='form-group'>
  <?php
    echo $this->Form->input('tags',[
                              'class' => 'form-control',
                              'placeholder' => 'tags',
                              'div' => [
                                'class' => 'col-lg-10'
                              ],
                              'label' => False
                            ]);
  ?>
</div>
<?php
  }
?>