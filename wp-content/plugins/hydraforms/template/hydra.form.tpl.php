<?php

?>

<div id="<?php print $id; ?>" class="<?php print $classes; ?>">
  <?php if($title_enable): ?>
    <div class="title">
      <h2><?php print $formRecord->getLabel(); ?></h2>
    </div>
  <?php endif;?>

  <div class="hydra-form-content">
    <?php $form->render(); ?>
  </div>
</div>
