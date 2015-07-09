<div id="user_control" class="hidden-sm hidden-xs">
    <ul>
		<li><a href="<?php echo site_url(); ?>" title="home" alt="home">Home</a></li>
        <li><a href="<?php echo site_url('/accounts/'); ?>" title="my account" alt="My Account">My Account</a></li>
		<?php if($this->session->userdata('elevation') == "ADMIN") { ?>
			<li><a href="<?php echo site_url('/cp/'); ?>" title="controlPanel" alt="Control Panel"> Control Panel</a></li>
		<?php } ?>
        <li><a href="<?php echo site_url('/logins/logout/');?>" title="logout" alt="logout">Logout</a></li>
    </ul>
    <?php
        if(is_array($this->session->userdata('notifications'))){
            foreach($this->session->userdata('notifications') as $notification)
            {
    ?>
    <div class="notification">
            <?php echo $notification; ?>
    </div>
    <?php   
            }
        }
    ?>
</div>
<nav class="navbar navbar-default hidden-lg hidden-md">
	<ul class="nav">
		<li class="active"><a href="<?php echo site_url(); ?>" title="home" alt="home">Home</a></li>
        <li><a href="<?php echo site_url('/accounts/'); ?>" title="my account" alt="My Account">My Account</a></li>
		<?php if($this->session->userdata('elevation') == "ADMIN") { ?>
			<li><a href="<?php echo site_url('/cp/'); ?>" title="controlPanel" alt="Control Panel"> Control Panel</a></li>
		<?php } ?>
        <li><a href="<?php echo site_url('/logins/logout/');?>" title="logout" alt="logout">Logout</a></li>
	</ul>
</nav>