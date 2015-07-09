	<div id="messages"><?php $message ?></div>
	<div id="cp_buttons" class="col-lg-12">
		<?php echo anchor(site_url().'news/create', 'News', 'class="btn btn-primary"'); ?>
		<?php echo anchor(site_url().'users/', 'Users', 'class="btn btn-primary"'); ?>
		<?php echo anchor(site_url().'restaurants', 'Restaurants', 'class="btn btn-primary"'); ?>
		<?php echo anchor(site_url().'employees', 'Employees', 'class="btn btn-primary"'); ?>
	</div>