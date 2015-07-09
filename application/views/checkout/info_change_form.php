<div>
	<!--Localize later if possible-->
	<p>Make changes to your contact information here: </p>
</div>
<form id="guest_form" action=<?php echo base_url()."index.php/customers/add_cust/"?> method="post" name="guest_form">
	<div class="labels">
    	<span class="err"></span>
    	<label for="f_name" class="label" >First Name:</label><br>
        <input type="text" class="input" name="f_name" required value=<?php echo $first_name; ?>>
    </div>
    <div class="spacer_50px"></div>
    <div class="labels">
    	<span class="err"></span>
    	<label for="l_name" class="label">Last Name:</label><br>
        <input type="text" class="input" name="l_name" required value=<?php echo $last_name; ?>>
    </div><br>
    <div class="labels">
    	<span class="err"></span>
        <label class="label" for="addr1">Address 1:</label><br>
        <input type="text" class="input addr1" name="addr1" required>
    </div>
    <div class="spacer_50px"></div>
    <div class="labels">
    	<span class="err"></span>
    	<label class="label" for="apt">Apt #:</label><br>
        <input type="text" class="input apt" name="apt">
    </div><br>
    <div class="labels">
    	<span class="err"></span>
        <label class="label" for="addr2">Address 2:</label><br>
        <input type="text" class="input addr2" name="addr2">
    </div>
    <div class="spacer_50px"></div>
    <div class="labels">
    	<span class="err"></span>
    	<label class="label" for="city">City:</label><br>
        <input type="text" class="input city" name="city" required>
    </div><br>
    <div class="labels">
        <span class="err"></span>
        <label class="label" for="state">State:</label><br>
        <input type="text" class="input state" name="state" required>
    </div><br>
    <div class="labels">
        <span class="err"></span>
        <label class="label" for="zip">Zip:</label><br>
        <input type="text" pattern="\d{5,5}(-\d{4,4})?" class="input zip" name="zip" required>
    </div><br>
    <div class="labels">
    	<span class="err"></span>
    	<label class="label" for="phone">Phone:</label><br>
        (<input type="text" class="input phone" name="phone1" maxlength="3" data-phone="<?php echo $phone; ?>" required>)-<input type="text" class="input phone" name="phone2" maxlength="3" required>-<input type="text" class="input phone" name="phone3" maxlength="4" required>
    </div><br>
    <input class="static_submit" type="submit" name="submit" value="Continue as guest">
    <button class="static_reset" type="button" name="reset" value="reset">Reset</button>
</form>
<script type="text/javascript">
    //load the info form UI
    fp.ui.loadUI({ui: 'reg_form'});
</script>