<script type="text/javascript" src=<?php echo base_url("assets/js/jquery.js");?>></script>
<script type="text/javascript" src=<?php echo base_url("assets/js/jquery-ui.min.js");?>></script>
<script type="text/javascript" src=<?php echo base_url("assets/js/inc/jquery.mb.flipText.js");?>></script>
<script type="text/javascript" src=<?php echo base_url("assets/js/inc/jquery.hoverIntent.min.js");?>></script>
<script type="text/javascript" src=<?php echo base_url("assets/js/inc/mbExtruder.js");?>></script>
<script type="text/javascript" src=<?php echo base_url("assets/js/fp.magnetic.js");?>></script>
<script type="text/javascript" src=<?php echo base_url("assets/js/foodobjects.js");?>></script>
<script type="text/javascript" src=<?php echo base_url("assets/js/fp.ui.js");?>></script>
<script type="text/javascript" src=<?php echo base_url("assets/js/bootstrap.min.js"); ?>></script>
<script type="text/javascript">
	fp.defaultMapAddress = "<?php if(isset($addr1)){echo $addr1;}else{echo"Monroe, WA";} ?>"
	var logged_in = <?php if($username == "Guest") { echo json_encode(false);}else{echo json_encode(true);} ?>
</script>
<script type="text/javascript" src="https://maps.google.com/maps/api/js?key=AIzaSyC7L1rknBFfEsrUW0nQdEsU23Id68sWBsE"></script>
<script type="text/javascript" src=<?php echo base_url("assets/js/fp.maps.js");?>></script>
