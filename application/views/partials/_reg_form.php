<div class="well col-sm-12 col-lg-4 center">
    <div>
    	<!--Localize later if possible-->
    	<p>Let's get some basic information from you...</p>
    </div>
    <form id="guest_form" action=<?php echo base_url("customers/add_cust");?> method="post" name="guest_form">
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
    <hr />
    <!--Localize later if possible-->

    <form id="register_form" action=<?php echo site_url("register/user_submit"); ?> method="post" name="register_form">
        <p class="msg">
            Registering makes it so you don't have to enter your address, phone number, and name every time you order from us. It also allows us to send you promotional offers if you choose to receive them.
        </p>
        <div class="labels">
        	<span class="err"></span>
        	<label class="label" for="username" required>Username:</label><br>
            <input type="text" class="input username" name="username">
        </div>
        <div class="spacer_50px"></div>
        <div class="labels">
        	<span class="err"></span>
        	<label class="label" for="pass">Password:</label><br>
            <input type="password" class="input pword" name="pass" required>
        </div><br />
        <div class="labels">
        	<label class="label" for="promos">Receive promotional offers?</label>
            <input type="checkbox" name="promos" value="true" checked="checked" />
        </div><br />
        <div class="labels">
        	<span class="err"></span>
        	<label class="label" for="email">Email:</label><br>
            <input type="email" class="input email" name="email">
        </div>
        <div class="spacer_50px"></div>
        <input class="static_submit" type="submit" name="submit" value="Register">
        <div class="soc_media">

        </div>
    </form>
</div>