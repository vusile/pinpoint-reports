<form method ="post" action = "campaigns/save_payment">
  <fieldset>
    <legend>Paid Campaigns</legend>
	<?php foreach($campaigns as $key=>$value): ?>
    <label><?php echo $value; ?> <input type="checkbox" name = "<?php echo $key ?>"> </label> 

	<?php endforeach; ?>
    <button type="submit" class="btn">Submit</button>
  </fieldset>
</form>