<?php  if (count($errors) > 0) : ?>
  <div class="error" style="">
	  <?php $value = reset($errors);?>
  	  <?php echo $value ?>
  </div>
<?php  endif ?>