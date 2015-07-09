<span class="magnetic right member_tab closed hidden-sm hidden-xs"> 
	Hello 
	<?php echo $username ?><br>
	<?php
		if($username == "Guest"){
			$mem_mess = anchor(site_url(), 'Home', 'title="home" id="home"');
			$mem_mess .= '<br>';
			$mem_mess .= anchor(site_url().'register/reg_form', 'Register', 'title="register" id="register"');
			$mem_mess .= '<br>';
			$mem_mess .= anchor("https://".$_SERVER['SERVER_NAME']."/foodport/logins/", 'Login', 'title="login" id="login"');
		
			echo $mem_mess;
		}else
		{
			$this->load->view('account/accountDash');
		}
	?> 
</span>
