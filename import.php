<?php 
include("include/session.php");
global $database;
$config = $database->getConfigs();
if(!$session->logged_in){
    header("Location: ".$config['WEB_ROOT'].$config['home_page']);
} else {    
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Eclipse Electrical</title>

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
                <li><a href="main"><span class="iconfa-laptop"></span> Dashboard</a></li>
                <li class="active"><a href="import.php"><span class="iconfa-hand-up"></span> Import</a></li>
                <?php if($session->userinfo['userlevel'] == 9) { ?>
                <li><a href="access.php"><span class="iconfa-hand-up"></span> Access Control</a></li>
                <?php } ?>
            </ul>
        </div><!--leftmenu-->        
    </div><!-- leftpanel -->


    
    <div class="rightpanel importprice">
    	<ul class="breadcrumbs">
            <li><a href="main"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
            <li>Import New Job</li>
        </ul>

        <div class="maincontent">
        	<div class="maincontentinner">
        		<div class="row-fluid">
        			<div id="dashboard-left" class="span10">
	        			<div class="widget">
		        			<h4 class="widgettitle">
		        			Import New Job
		        			</h4>
		        			<div class="widgetcontent">
                                <form action="process.php" method="post" enctype="multipart/form-data">
                                    Select CSV files to upload:
                                    <input type="file" name="fileToUpload" id="fileToUpload">
                                    <input type="submit" value="Upload CSV" name="newjobfile">
                                </form>
		        			</div><!-- widgetcontent -->
	        			</div><!-- widget -->

                        <?php 
                            $useraccess = $database->checkUserAccess($session->userinfo['email']);
                            if($session->userinfo['userlevel'] == 9 || $useraccess[0]['importable'] == 1) {
                        ?>
                        <div class="widget">
                            <h4 class="widgettitle">
                            Update Price Book
                            </h4>
                            <div class="widgetcontent">
                                <form action="process.php" method="post" enctype="multipart/form-data">
                                    Select CSV files to upload:
                                    <input type="file" name="fileToUpload" id="fileToUpload">
                                    <input type="submit" value="Upload CSV" name="pricebookfile">
                                </form>
                            </div><!-- widgetcontent -->
                        </div><!-- widget -->

                        <?php 
                            } /*else {
                                echo "<pre>";
                                echo print_r($useraccess[0]['importable']);
                                echo "</pre>";} */
                        ?>
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
        </div>
    </div><!-- rightpanel -->
</div><!-- mainwrapper -->
</body>
</html>
<?php } ?>