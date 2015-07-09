<?php $this->load->view("common/header"); ?>
<body>
    <div class="main_container">
        <section class="layer2 topCont">
            <img src="<?php echo base_url("/assets/images/foodport_logo_proto.png"); ?>" class="img-responsive main_logo" />
            <div class="layer3 topPanel">
                <div class="member_cont">
                    <div class="search_bar"><?php $this->load->view('search/food_search'); ?></div>
                    <?php $this->load->view('home/member_tab.php')?>
                    <div class="clear"></div>
                </div>
            </div>
        </section>
        <div id="extruderRight" class="{title:'Shopping Cart', url: 'assets/js/parts/extruderRight.php'}">
            
        </div>
        <nav class="navbar navbar-inverse hidden-lg hidden-md">
			<div class="nav-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#mysite-navbar-collapse">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<span id="greeting" >Hello <?php echo $username ?></span>
			</div>
           	<div class="collapse navbar-collapse" id="mysite-navbar-collapse">
           		<?php
	           		if($username == "Guest"){
                        $mem_mess = "<ul class=\"nav navbar-nav\"><li>" .anchor(site_url(), 'Home', 'title="home"')."</li>";
	           			$mem_mess .= "<li>" . anchor(site_url("register/reg_form"), 'Register', 'title="register"') . "</li>";
	           			$mem_mess .= "<li>" . anchor(site_url('logins', 'https'), 'Login', 'title="login" id="login"') . "</li></ul>";
	           		
	           			echo $mem_mess;
	           		}else
	           		{
	           			$this->load->view('account/accountDash');
	           		}
                ?>
           	</div>
    	</nav>
        <div class="layer1 mainCont" id="wrap">
       		<?php $this->load->view($content); ?>
        </div>
    </div>
    <?php $this->load->view('common/scripts'); ?>
    <script type="text/javascript">
    $(document).ready(function()
    {
        site_url = <?php echo '"' .site_url().'";'; ?>
        initialize();
        fp.ui.loadUI();
        login_error = '';
        <?php
            if(isset($error))
            { 
                switch($error)
                {
                    case INVALID_LOGIN_ERR:
                    {
                        echo '$("#login").trigger("click");';
                        
                        //Global javascript variable login_error.
                        echo 'login_error ="'.INVALID_LOGIN_MSG.'";';
                        break;
                    }
                }
            }
        ?>
    });
</script>
</body>
</html>