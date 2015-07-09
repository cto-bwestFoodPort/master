<div class="panel panel-default col-lg-6 col-md-6 col-sm-12 col-xs-12 no-pad">
	<div class="panel-heading">
		<h2 class="panel-title">Add a Restaurant</h2>
	</div>
	<div class="panel-body">
		<!-- Form Start -->
		<?php 
			echo "<div class='validation_errors'>";
				echo validation_errors();
			echo "</div>";
			if(isset($form_submit)){
				echo $form_submit;
			}
			echo form_open('restaurants');
		?>
			<div class="input-group">
				<label class="input-group-addon" for="rest_name">Restaurant Name: </label>
				<input class="form-control" type="text" name="rest_name" /><br>
			</div>
			<div class="input-group">
				<label class="input-group-addon" for="logo_loc">Logo: </label>
				<input class="form-control" type="text" name="logo_loc" /><br>
			</div>	
			<div class="input-group">
				<?php foreach($cat_ids as $cat_id){
					echo "<input type='hidden' value='".$cat_id."' name='cat_id' />";
				}?>
				<label class="input-group-addon" for="rest_cat">Category: </label>
				<?php
					echo form_dropdown('rest_cat', $categories, 'Fast Food', 'class="form-control rest_cat"') . "<br>";
				?>
			</div>
			<div class="input-group">
				<label class="input-group-addon" for="addr1">Address 1: </label>
				<input class="form-control" type="text" name="addr1" /><br>
			</div>
			<div class="input-group">
				<label class="input-group-addon" for="addr2">Address 2: </label>
				<input class="form-control" type="text" name="addr2" /><br>
			</div>
			<div class="input-group">
				<label class="input-group-addon" for="city">City: </label>
				<input class="form-control" type="text" name="city" /><br>
			</div>
			<div class="input-group">
				<label class="input-group-addon" for="state">State: </label>
				<input class="form-control" type="text" name="state" /><br>
			</div>
			<div class="input-group">
				<label class="input-group-addon" for="zip">Zip Code: </label>
				<input class="form-control" type="text" name="zip" /><br>
			</div><br>
			<input class="btn btn-primary" type="submit" name="submit" value="Add Restaurant" />
		</form>
	</div>
</div>
<div class="panel panel-default col-lg-6 col-md-6 col-sm-12 col-xs-12 no-pad">
	<div class="panel-heading">
		<h2 class="panel-title">Restaurants</h2>
	</div>
	<div class="panel-body restaurants">
		
	</div>
</div>
	