<form method="post" id="add_food_form">
	<div class="messages">

	</div>
	<div class="input-group">
		<label for="food_cat">Food Category: </label>
		<select class="cat_select form-control" name="food_cat">

		</select>
	</div>
	<div class="input-group">
		<label for="food_name">Food Name: </label>
		<input class="form-control" type="text" pattern="[A-Za-z .0-9\-]+" name="food_name" />
	</div>
	<div class="input-group">
		<label for="description">Description: </label>
		<textarea class="form-control" rows="5" name="description" />
	</div><br><br>
	<fieldset class="cloneable">
		<legend><span class="add glyphicon glyphicon-plus"></span>Price Point</legend>
		<div class="input-group">
			<label for="topic">Price Topic: (ie: Combo... Sandwich Only)</label>
			<input class="form-control" type="text" pattern="[A-Za-z \-\.]+" name="price[topic][]" />
		</div>
		<div class="input-group">
			<label for="price">Price (Format: $0.00):</label>
			<input class="form-control dollar" type="text" pattern="^[+-]?[$]+[0-9]{1,3}(?:,?[0-9]{3})*\.[0-9]{2}$" name="price[price][]" />
		</div>
		<div class="input-group promos">
			<label>Promos: </label>
			<input type="hidden" name="price[promos][][]" value="NONE" />
		</div><br><br>
	</fieldset>
	<div class="input-group">
		<label for="keywords">Keywords: (Separate by ",")</label>
		<input class="keywords form-control" type="text" name="keywords" />
	</div>
	<div class="btn-group">
		<button class="btn btn-primary">Add</button>
	</div>
</form>