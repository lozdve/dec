<?php
include("include/session.php");
$config = $database->getConfigs();
include("include/header.php");
?>

<body class="loginpage">

<div class="regpanel">
    <div class="regpanelinner">
        <div class="pageheader">
            <div class="pageicon"><span class="iconfa-hand-down"></span></div>
            <div class="pagetitle">
                <h5>Your Information</h5>
                <h1>Sign Up</h1>
            </div>
        </div>
        <div class="regcontent">
        	<?php
			/**
			 * The user is already logged in, not allowed to register.
			 */
			if($session->logged_in){
			   echo "<h1>Registered</h1>";
			   echo "<p>We're sorry <b>$session->username</b>, but you've already registered. "
				   ."<a href=\"index\">Home</a>.</p>";
			}
			else if(isset($_SESSION['regsuccess'])){
	
				if ($_SESSION['regsuccess']==6){
				  echo "<h1>Registration is currently disabled!</h1>";
				  echo "<p>We're sorry <b>".$_SESSION['reguname']."</b> but registration to this site is currently disabled."
					  ."<br>Please try again at a later time or contact the website owner.</p>";
				}
				/* Registration was successful */
				else if($_SESSION['regsuccess']==0 || $_SESSION['regsuccess']==5){
				  echo "<h1>Registered!</h1>";
				  echo "<p>Thank you <b>".$_SESSION['reguname']."</b>, your information has been added to the database, "
					  ."you may now <a href=\"/dec/index\">log in</a>.</p>";
				}
				else if($_SESSION['regsuccess']==3){
				  echo "<h1>Registered!</h1>";
				  echo "<p>Thank you <b>".$_SESSION['reguname']."</b>, your account has been created. "
					  ."However, this board requires account activation, an activation key has been sent to the e-mail address you provided. "
					  ."Please check your e-mail for further information.</p>";
				}
				else if($_SESSION['regsuccess']==4){
				  echo "<h1>Registered!</h1>";
				  echo "<p>Thank you <b>".$_SESSION['reguname']."</b>, your account has been created. "
					  ."<br />However, this board requires account activation by an Admin. An e-mail has been sent to them and you will be informed "
					  ."when your account has been activated.</p>";
					  echo "Click <a href=\"index\">here</a> to go back";
			   }
			   /* Registration failed */
			   else if ($_SESSION['regsuccess']==2){
				  echo "<h1>Registration Failed</h1>";
				  echo "<p>We're sorry, but an error has occurred and your registration for the username <b>".$_SESSION['reguname']."</b>, "
					  ."could not be completed.<br>Please try again at a later time.</p>";
			   }
			   unset($_SESSION['regsuccess']);
			   unset($_SESSION['reguname']);
			   } 
				else if ((isset($_GET['mode'])) && ($_GET['mode'] == 'activate')) {
				$user = $_GET['user'];
				$actkey = $_GET['activatecode'];
				
				$sql = $database->connection->prepare("UPDATE ".TBL_USERS." SET acted = '1' WHERE username=:user AND actkey=:actkey");
				$sql->bindParam(":user",$user);
				$sql->bindParam(":actkey",$actkey);
				$sql->execute();


        		$count = $sql->rowCount();
        		if(!$sql || $count < 1) {
        			echo "<p>This link is not valid.</p>";
        			echo "<p>Click <a href=\"index\">here</a> to go back.</p>";
        		} else {
					echo "<p>Account: <b>$user</b> is now activated.</p>";
					echo "<p>Click <a href=\"index\">here</a> to go back.</p>";
				}
				// some warning if not successful
			}
						/**
			 * The user has not filled out the registration form yet.
			 * Below is the page with the sign-up form, the names
			 * of the input fields are important and should not
			 * be changed.
			 */
			else{
			?>
            
            <form action="process" method="post" class="stdform">
                
                <h3 class="subtitle">Login Information</h3>
                <p><input type="text" name="email" class="input-block-level" placeholder="Email" value="<?php echo $form->value("email"); ?>" /></p>
                <?php echo $form->error("email"); ?>
                <p><input type="password" name="pass" class="input-block-level" placeholder="Password" value="<?php echo $form->value("pass"); ?>" /></p>
                <?php echo $form->error("pass"); ?>
                <p><input type="password" name="conf_pass" class="input-block-level" placeholder="Confirm Password" value="<?php echo $form->value("conf_pass"); ?>" /></p>
                <?php echo $form->error("pass"); ?>
                
                <h3 class="subtitle">User Information</h3>
                <p><input type="text" name="firstname" class="input-block-level" placeholder="Firstname" value="<?php echo $form->value("firstname"); ?>"  /></p>
                <p><input type="text" name="lastname" class="input-block-level" placeholder="Lastname" value="<?php echo $form->value("lastname"); ?>" /></p>
                <p><input type="text" name="phone" class="input-block-level" placeholder="Phone (Optional)" value="<?php echo $form->value("phone"); ?>" /></p>

                <input type="hidden" name="subjoin" value="1" />
                <p><button class="btn btn-primary">Register</button></p>
                
            </form>
        
        </div><!--regcontent-->
    </div><!--regpanelinner-->
</div><!--regpanel-->

<div class="regfooter">
    <p>&copy; 2016. Dance Education Center. All Rights Reserved.</p>
</div>
<?php 
if ($config['ENABLE_CAPTCHA']){
?>
<!-- QapTcha and jQuery files -->
<script type="text/javascript" src="captcha/jquery/jquery.js"></script>
<script type="text/javascript" src="captcha/jquery/jquery-ui.js"></script>
<script type="text/javascript" src="captcha/jquery/jquery.ui.touch.js"></script>
<script type="text/javascript" src="captcha/jquery/QapTcha.jquery.js"></script>
<script type="text/javascript">
		$('.QapTcha').QapTcha({});
	</script>
<?php
}
}
?>
</body>
</html>
