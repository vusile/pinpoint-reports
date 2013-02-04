<?php if(isset($report)): ?>
<form method="post" action ="campaigns/gen_report">
<?php else: ?>
<form method="post" action ="campaigns/get_campaigns">
<?php endif; ?>

  <fieldset>
     <label>Website</label>
     <select name = "website" id ="website">
     <option value ="">Select One</option>
	<?php foreach($websites->result() as $website): ?>
    	<option value = "<?php echo $website->id ?>"><?php echo $website->website_name ?></option>

	<?php endforeach; ?>
	</select>
     <label>Month</label>
     <select name = "month" id ="month">
     <option value ="">Select One</option>	
	<?php foreach($months as $key=>$value): ?>
		<option value = "<?php echo $key ?>"><?php echo $value ?></option>
	<?php endforeach; ?>
</select>
    <button type="submit" class="btn">Submit</button>
  </fieldset>
</form>