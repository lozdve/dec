<?php
include("include/session.php");
global $database;
$config = $database->getConfigs();
$userregion = $database->getUserInfo($session->userinfo['email']); 

if(!$session->logged_in){
	header("Location: ".$config['WEB_ROOT'].$config['home_page']);
} else {	
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Plantinum Homes</title>
<link rel="stylesheet" href="css/style.default.css" type="text/css" />

<link rel="stylesheet" href="css/responsive-tables.css">
<script src="https://code.jquery.com/jquery-3.1.0.min.js" integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js" integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-migrate-1.4.1.min.js" integrity="sha256-SOuLUArmo4YXtXONKz+uxIGSKneCJG4x0nVcA0pFzV0=" crossorigin="anonymous"></script>
<script type="text/javascript" src="js/modernizr.min.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/jquery.uniform.min.js"></script>
<script type="text/javascript" src="js/flot/jquery.flot.min.js"></script>
<script type="text/javascript" src="js/flot/jquery.flot.resize.min.js"></script>
<script type="text/javascript" src="js/responsive-tables.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="js/excanvas.min.js"></script><![endif]-->
</head>

<body>
<div class="mainwrapper">
    
    <div class="header">
        <div class="logo">
            <a href="main"><img src="images/plantinum-logo.png" alt="Eclipse Electrical" width="200"/></a>
        </div>
        <div class="headerinner">
            <ul class="headmenu">
                <li class="right">
                    <div class="userloggedinfo">
                        <div class="userinfo">
                            <h5><?php echo $session->userinfo['firstname']." ".$session->userinfo['lastname']; ?> <small>- <?php echo $session->userinfo['email']; ?></small></h5>
                            <ul>
                                <li><a href="useredit">Edit Profile</a></li>
                                <li><a href="process">Sign Out</a></li>
                            </ul>
                        </div>
                    </div>
                </li>
            </ul><!--headmenu-->
        </div>
    </div>
    
    <div class="leftpanel">
        
        <div class="leftmenu">        
            <ul class="nav nav-tabs nav-stacked">
                <li class="nav-header">Navigation</li>
                <li class="active"><a href="main"><span class="iconfa-laptop"></span> Dashboard</a></li>
                <li><a href="import.php"><span class="iconfa-hand-up"></span> Import</a></li>
            </ul>
        </div><!--leftmenu-->
        
    </div><!-- leftpanel -->
    
    <div class="rightpanel">
        
        <ul class="breadcrumbs">
            <li><a href="main"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
            <li>Edit Profile</li>
        </ul>
        
        <div class="pageheader">
            <div class="pageicon"><span class="iconfa-laptop"></span></div>
            <div class="pagetitle">
                <h5>User Profile</h5>
                <h1>Edit Profile</h1>
            </div>
        </div><!--pageheader-->
        
        <div class="maincontent">
            <div class="maincontentinner">
                <div class="row-fluid">
                    <div id="dashboard-left" class="span8">
                    
                    <div class="widget">
            		<h4 class="widgettitle">User Profile</h4>
            		<div class="widgetcontent">
					<?php 
                    if(isset($_SESSION['useredit'])){
                       unset($_SESSION['useredit']);
                       
                       echo "<h3>User Account Edit Success!</h3>";
                       echo "<p><b>".$session->userinfo['firstname']." ".$session->userinfo['lastname']."</b>, your account has been successfully updated. "
                           ."<a href='".$config['WEB_ROOT'].$config['home_page']."'>Return to Home</a>.</p>";
                    }
                    else{
                    ?>
					<form class="stdform" action="process" method="post">
                    
						<p>
                            <label>Current Password</label>
                            <span class="field"><input type="password" name="curpass" class="input-large" placeholder="Current Password" value="<?php echo $form->value("curpass"); ?>" /> <?php echo $form->error("curpass"); ?></span>
                        </p> 
                        
                        <p>
                            <label>New Password</label>
                            <span class="field"><input type="password" name="newpass" class="input-large" placeholder="New Password" value="<?php echo $form->value("newpass"); ?>" /> <?php echo $form->error("newpass"); ?></span>
                        </p>  
                        
                        <p>
                            <label>Confirm New Password</label>
                            <span class="field"><input type="password" name="conf_newpass" class="input-large" placeholder="Confirm New Password" value="<?php echo $form->value("newpass"); ?>" /> <?php echo $form->error("newpass"); ?></span>
                        </p>   
                        
                        <p>
                            <label>Email</label>
                            <span class="field"><input type="text" name="email" class="input-large" placeholder="Email" value="<?php
							if($form->value("email") == ""){
							   echo $session->userinfo['email'];
							}else{
							   echo $form->value("email");
							}
							?>" /> <?php echo $form->error("email"); ?></span>
                        </p> 

                        <p>
                            <label>Region</label>
                            <span class="field">
                                <select name="userregion">
                                    <option value="kkbs" <?php if($userregion['region']=='kkbs') echo "selected"; ?>>KKBS</option>
                                    <option value="chch" <?php if($userregion['region']=='chch') echo "selected"; ?>>ChristChurch Canty</option>
                                    <option value="hawkesbay" <?php if($userregion['region']=='hawkesbay') echo "selected"; ?>>Hawkesbay</option>
                                    <option value="lowernorth" <?php if($userregion['region']=='lowernorth') echo "selected"; ?>>Lower North Island</option>
                                    <option value="north" <?php if($userregion['region']=='north') echo "selected"; ?>>Northland</option>
                                    <option value="otago" <?php if($userregion['region']=='otago') echo "selected"; ?>>Otage_Southland</option>
                                    <option value="uppersouth" <?php if($userregion['region']=='uppersouth') echo "selected"; ?>>Upper South Island</option>
                                </select>
                            </span>
                        </p>

						<p class="stdformbutton">
                        	 <input type="hidden" name="subedit" value="1"><br />
                             <button class="btn btn-primary">Save</button>
                        </p>
                    </form>
			<?php
            } 
            }
            ?>
            </div><!--widgetcontent-->
            </div>
                        
                    </div><!--span7-->
                    
                </div><!--row-fluid-->
                
                <div class="footer">
                    <div class="footer-left">
                        <span>&copy; 2016. Plantinum Homes. All Rights Reserved.</span>
                    </div>
                    <div class="footer-right">
                        <span>Powered by: <a href="http://www.microsolution.co.nz/" target="_blank">Micro Solution</a></span>
                    </div>
                </div><!--footer-->
                
            </div><!--maincontentinner-->
        </div><!--maincontent-->
        
    </div><!--rightpanel-->
    
</div><!--mainwrapper-->
</body>
</html>
