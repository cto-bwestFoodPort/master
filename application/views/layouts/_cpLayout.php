<?php $this->load->view("common/header"); ?>
<body>
    <div class="cp_container">
        <div class="layer2 member_cont">
            <div class="member_cont">
                <?php $this->load->view('home/member_tab'); ?>
                <div class="clear"></div>
            </div>
        </div>
        <div class="layer1">
            <section class="layer1">
            	<?php $this->load->view($main_content);?>
            </section>
        </div>
    </div>
    <?php $this->load->view('common/scripts'); ?>
    <script type="text/javascript">
        $(document).ready(function()
        {
            site_url = <?php echo '"' .site_url().'";'; ?>
            fp.ui.loadUI({ui: 'accounts'});
        });
    </script>
</body>
</html>