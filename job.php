<?php 
include("include/session.php");
global $database;
$getJob_id = $_GET['id'];
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Plantinum Homes</title>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<link rel="stylesheet" href="css/style.default.css" type="text/css" />
<link rel="stylesheet" href="css/bootstrap-fileupload.min.css" type="text/css" />
<link rel="stylesheet" href="css/bootstrap-timepicker.min.css" type="text/css" />
<link rel="stylesheet" href="css/job-detail-table.css" type="text/css" />
<script src="https://code.jquery.com/jquery-3.1.0.min.js" integrity="sha256-cCueBR6CsyA4/9szpPfrX3s49M9vUU5BgtiJj06wt/s=" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.12.0/jquery-ui.min.js" integrity="sha256-eGE6blurk5sHj+rmkfsGYeKyZx3M4bG+ZlFyA7Kns7E=" crossorigin="anonymous"></script>
<!-- slickgrid css-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Latest compiled and minified CSS -->


<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

<script src="https://code.jquery.com/jquery-migrate-1.4.1.min.js" integrity="sha256-SOuLUArmo4YXtXONKz+uxIGSKneCJG4x0nVcA0pFzV0=" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Uniform.js/3.0.0/js/jquery.uniform.bundled.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/3.51/jquery.form.min.js"></script>
<script type="text/javascript" src="js/jquery.validate.min.js"></script>
<script type="text/javascript" src="js/jquery.tagsinput.min.js"></script>
<script type="text/javascript" src="js/jquery.autogrow-textarea.js"></script>
<script type="text/javascript" src="js/charCount.js"></script>
<script type="text/javascript" src="js/colorpicker.js"></script>
<script type="text/javascript" src="js/chosen.jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/modernizr.min.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
<script type="text/javascript" src="js/ui.spinner.min.js"></script>
<!--
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/bootstrap-fileupload.min.js"></script>
<script type="text/javascript" src="js/bootstrap-timepicker.min.js"></script> 
-->
</head>

<body>
<div class="mainwrapper">	
	<div class="header">
        <div class="logo">
            <a href="main"><img src="images/plantinum-logo.png" alt="Eclipse Electrical"  width="200"/></a>
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
            <li><?php echo $getJob_id; ?></li>
        </ul>

        <div class="maincontent">
        	<div class="maincontentinner">
        		<div class="row-fluid">
        			<div id="dashboard-left" class="span10">
	        			<div class="widget">
		        			<h4 class="widgettitle">
		        			<?php echo $getJob_id;	?>
		        			</h4>
		        			<div class="widgetcontent">
		        				<?php $database->displayJobDetail($getJob_id, $session->userinfo['userlevel']); ?>
		        			</div><!-- widgetcontent -->
	        			</div><!-- widget -->
        			</div><!-- dashboard-left -->
        		</div><!-- row-fluid -->

        		<div class="footer">
                    <div class="footer-left">
                        <span>&copy; 2016. Plantinum Homes. All Rights Reserved.</span>
                    </div>
                    <div class="footer-right">
                        <span>Powered by: <a href="http://www.microsolution.co.nz/" target="_blank">Micro Solution</a></span>
                    </div>
                </div><!--footer-->
        	</div>
        	<a href="#0" class="cd-top">Top</a>
        </div>
    </div><!-- rightpanel -->


</div><!-- mainwrapper -->
</body>
</html>