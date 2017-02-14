<?php
include("include/session.php");
global $database;
$config = $database->getConfigs();
if($session->logged_in){
   header("Location: main");
}
else{
include("include/header.php");
?>
<body class="loginpage">
<div class="loginpanel">
    <div class="loginpanelinner">
        <div class="logo animate0 bounceIn"><img src="images/dec-logo-tran.png" alt=""  width="200"/></div>
        <form id="login" action="process.php" method="post">
            <div class="inputwrapper login-alert">
            	<?php  
				if($form->num_errors > 0){
                    $errmsg = $form->getErrorArray();
				    echo "<div class=\"alert alert-error\">" .$errmsg['user']. "</div>";
				}
				?>              
            </div>
            <div class="inputwrapper animate1 bounceIn">
                <input type="text" name="user" id="user" placeholder="Your email" />
            </div>
            <div class="inputwrapper animate2 bounceIn">
                <input type="password" name="pass" id="pass" placeholder="Your password" />
            </div>
            <div class="inputwrapper animate3 bounceIn">
            	<input type="hidden" name="sublogin" value="1">
                <button name="submit">Sign In</button>
            </div>
            <div class="inputwrapper animate4 bounceIn">
                <div class="pull-right">Not a member? <a href="registration">Sign Up</a></div>
                <label><input type="checkbox" class="signin" name="remember" /> Keep me sign in</label>
            </div>
            
        </form>
    </div><!--loginpanelinner-->
</div><!--loginpanel-->

<div class="loginfooter">
    <p>&copy; 2016. Dance Education Center. All Rights Reserved. Powered by: Micro Solution</p>
</div>
<?php
}
?>
</body>
</html>
