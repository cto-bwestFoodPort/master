
<form action="<?php echo site_url('logins/login'); ?>" method="post" name="login_form" id="login_form">
    <div class="err"><?php if(isset($error)){echo $error;} ?></div>
    <div class="labels">
        <span class="err"></span>
        <label class="label" for="username">Username: </label>
        <br>
        <input class="input" type="text" required name="username">
    </div>
    <div class="labels">
        <span class="err"></span>
        <label class="label" for="password">Password: </label>
        <br>
        <input class="input" type="password" required name="pass">
    </div>
    <br>
    <input type="submit" value="Login" name="submit">
</form>
