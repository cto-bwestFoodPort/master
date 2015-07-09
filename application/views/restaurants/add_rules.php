<form method="post" class="rules_form">
	<input type="hidden" class="rest_id" name="rest_id" />
	<div class="input-group">
		<label for="rule_name">Rule Name:</label>
		<input class="form-control" type="text" name="rule_name" placeholder="Name of rule" required />
	</div>
	<div class="input-group">
		<label for="rule_type">Type:</label>
		<input class="form-control" type="text" name="rule_type" placeholder="Rule Type" required />
	</div>
	<?php
		foreach($food_items as $cat=>$items){
			echo "<h3 class=\"food_cat\">".$cat."</h3>";
			foreach($items as $item){
				$list = "<ul style=\"list-style-type:none;\" data-foodid=\"".$item['food_id']."\"><h4 class=\"food_name\">".$item['food_name']."</h4>";
				$list .= "<input type=\"hidden\" name=\"food_ids[".$item['food_id']."]\" />";
				for($i = 0; $i < count($item['price']['topic']); $i++){
					$list .= "<li style=\"padding-left:25px; width:200px;\">";
					$list .= "<input class=\"topic\" type=\"checkbox\" name=\"food_ids[".$item['food_id']."][type][]\" value=\"".$item['price']['topic'][$i]."\" />";
					$list .= $item['price']['topic'][$i];
					$list .= "<br><label>Price</label><div>".$item['price']['price'][$i]."</div>";
					$list .= "<label>Discount</label><input class=\"form-control discount\" name=\"food_ids[".$item['food_id']."][discount][]\" type=\"text\" readonly />";
					$list .= "</li>";
				}
				$list .= "</ul>";
				echo $list;
			}
		}
	?>
	<br>
	<input type="submit" class="btn btn-primary" value="Submit" />
</form>