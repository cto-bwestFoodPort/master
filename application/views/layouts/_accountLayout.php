<!doctype html>
<html>
<head>
<meta charset="utf-8">
<?php $this->load->view('common/styles'); ?>
<title>Food Port | Fast Food | Restaurants | Grocery | Delivered</title>
<?php $this->load->view('common/scripts'); ?>
<script type="text/javascript">
    $(document).ready(function()
    {
        site_url = <?php echo '"' .site_url().'";'; ?>
        fp.ui.loadUI({ui: 'accounts'});
    });
</script>
</head>
<body>
    <div class="accounts_container">
        <div class="layer2 member_cont">
            <div class="member_cont">
                <span class="right member_tab closed">
                    Hello <?php echo $username ?><br>
                    <?php
                        if($username == "Guest"){
                            $mem_mess = anchor(site_url().'/register/reg_form', 'Register', 'title="register" id="register"');
                            $mem_mess .= '<div class="spacer_10px"></div>';
                            $mem_mess .= anchor(site_url().'/logins/login', 'Login', 'title="login" id="login"');

                            echo $mem_mess;
                        }else
                        {
                            $this->load->view('account/accountDash');
                        }
                    ?>
                </span>
                <div class="clear"></div>
            </div>
        </div>
        <div class="layer1 section_bg">
            <section class="layer1 accntsCont">
                <?php $this->load->view('account/acct_bulletin');?>
            </section>
        </div>
    </div>
</body>
</html>