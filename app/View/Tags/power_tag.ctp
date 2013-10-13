<?php
  if (isset($result)) {
    echo $result;
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