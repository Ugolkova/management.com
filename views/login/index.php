<link rel="stylesheet" type="text/css" href="<?php echo URL; ?>public/css/login/default.css" />

<form method="POST" action="<?php echo URL; ?>login/run/" id="login">
    <input type="hidden" name="token" value="<?php echo $this->token; ?>" />
    
    <label>Username:</label>
    <input name="login" type="text" value="" />
    <label>Password:</label>
    <input name="password" type="password" value="" />
    
    <input name="submit" type="submit" value="Login" />
    <a href="#" title="Forgot your password?">Forgot your password?</a>
</form>