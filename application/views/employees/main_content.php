<div class="panel panel-default col-lg-6 col-md-6 col-sm-12 col-xs-12 no-pad">
	<div class="panel-heading">
		<h2 class="panel-title">Add an Employee</h2>
	</div>
	<div class="panel-body">
		<!-- Form Start -->
		<?php 
			echo "<div class='validation_errors err'>";
				echo validation_errors();
				if(isset($error)){echo $error;}
			echo "</div>";
			if(isset($messages)){
					foreach($messages as $message){
						echo $message . "<br>";
					}
				}
			echo form_open('employees/create');
		?>
			</div>
			<div class="input-group">
				<label class="input-group-addon" for="emp_type">Employee Type: </label>
				<?php
					$options = array(
							'CAR_DRIVER' => "Car Driver",
							'BIKE_COURIER' => "Biker",
							'MANAGER' => "Manager"
						);
					echo form_dropdown("emp_type", $options, "CAR_DRIVER", 'class="form-control"');
				?>
			</div>
			<div class="input-group">
				<label class="input-group-addon" for="first_name">First Name: </label>
				<input class="form-control" type="text" name="first_name" required /><br>
			</div>
			<div class="input-group">
				<label class="input-group-addon" for="last_name">Last Name: </label>
				<input class="form-control" type="text" name="last_name" required /><br>
			</div>	
			<div class="input-group">
				<label class="input-group-addon" for="phone">Phone Number: </label>
				<input class="form-control" type="tel" pattern="[0-9]{10}" name="phone" required /><br>
			</div>
			<div class="input-group">
				<label class="input-group-addon" for="carrier">Employee Type: </label>
				<?php
					$options = array();
					echo form_dropdown("carrier", $options, "CAR_DRIVER", 'class="form-control carriers", required');
				?>
			</div>
			<div class="input-group">
				<label class="input-group-addon" for="addr1">Address 1: </label>
				<input class="form-control" type="text" name="addr1" required /><br>
			</div>
			<div class="input-group">
				<label class="input-group-addon" for="addr2">Address 2: </label>
				<input class="form-control" type="text" name="addr2" /><br>
			</div>
			<div class="input-group">
				<label class="input-group-addon" for="city">City: </label>
				<input class="form-control" type="text" name="city" required /><br>
			</div>
			<div class="input-group">
				<label class="input-group-addon" for="state">State: </label>
				<input class="form-control" type="text" pattern="[A-Z]{2}" name="state" required /><br>
			</div>
			<div class="input-group">
				<label class="input-group-addon" for="zip">Zip Code: </label>
				<input class="form-control" type="text" pattern="[0-9]{5}" name="zip" required /><br>
			</div><br>
			<input class="btn btn-primary" type="submit" name="submit" value="Add Employee" />
		</form>
	</div>
</div>
<div class="panel panel-default col-lg-6 col-md-6 col-sm-12 col-xs-12 no-pad">
	<div class="panel-heading">
		<h2 class="panel-title">Employees</h2>
	</div>
	<div class="panel-body employees">
		
	</div>
</div>
	