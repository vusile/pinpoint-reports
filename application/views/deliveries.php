<h2>Website: <?php echo $website_name; ?></h2>
<form method="post" action ="campaigns/save_deliveries">
  <fieldset>
    <?php foreach($websites_campaigns->result() as $camp): ?>
     <label><strong><?php echo $campaign_names[$camp->campaign] ?></strong></label>
     Deliveries: <input type = "text" name = "deliveries-<?php echo $camp->campaign ?>" id ="deliveries-<?php echo $camp->campaign ?>" ><br>
     Percentage: <input type = "text" name = "percentage-<?php echo $camp->campaign ?>" id ="percentage-<?php echo $camp->campaign ?>" value = "50">

	<?php endforeach; ?>
    <input type = "hidden" name = "website" id = "website" value="<?php echo $website_id; ?>" />
    <input type = "hidden" name = "string" id = "string" value="<?php echo $campaigns_string; ?>" />
    <br><button type="submit" class="btn">Submit</button>
  </fieldset>
</form>