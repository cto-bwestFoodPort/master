<?php $this->load->view("common/header"); ?>
<body>
    <div class="cp_container">
        <div class="layer2 member_cont">
            <div class="member_cont">
                <?php $this->load->view('home/member_tab.php'); ?>
                <div class="clear"></div>
            </div>
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
                        $mem_mess .= "<li>" . anchor(site_url().'logins', 'Login', 'title="login" id="login"') . "</li></ul>";
                    
                        echo $mem_mess;
                    }else
                    {
                        $this->load->view('account/accountDash');
                    }
                ?>
            </div>
        </nav>
        <div class="layer1">
            <section class="layer1 well">
            	<?php if(isset($main_content)) { $this->load->view($main_content); } ?>
            </section>
        </div>
    </div>
</body>
<?php $this->load->view('common/scripts'); ?>
<script type="text/javascript">
    site_url = <?php echo '"' .site_url().'";'; ?>
    initialize();
    <?php 
        if(isset($ui_script)){echo $ui_script;} 
    ?>
</script>
</html>