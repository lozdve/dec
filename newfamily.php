<?php
include("include/session.php");
global $database;
$config = $database->getConfigs();
if(!$session->logged_in){
	header("Location: ".$config['WEB_ROOT'].$config['home_page']);
} else {	
    include("include/header.php");
?>


<body>
<div class="mainwrapper">    
    <div class="header">
        <div class="logo">
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
    
    <?php include("include/menu.php"); ?>
    
    <div class="rightpanel">
        
        <ul class="breadcrumbs">
            <li><a href="main"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
            <li>new family</li>
        </ul>
        
        <div class="pageheader">
            <div class="pageicon"><i class="fa fa-laptop" aria-hidden="true"></i></div>
            <div class="pagetitle">
                <h1>New Family</h1>
            </div>
        </div><!--pageheader-->
        
        <div class="maincontent">
            <div class="maincontentinner">
                <div class="row-fluid">
                    <div id="dashboard-left" class="span12">
                        <div class="widget">
                        <h4 class="widgettitle">New Family</h4>
                        <div class="widgetcontent">
                        <form id="editfam" method="post" action="process">
                            <div class="col-3 editfam-div">
                                <label>
                                    Code
                                    <input class="" name="fam-code" tabindex="1" value>
                                </label>
                            </div>
                            <div class="col-3 editfam-div">
                                <label>
                                    Last
                                    <input class=""  name="fam-last" tabindex="2" value>
                                </label>
                            </div>
                            <div class="col-3 editfam-div">
                                <label>
                                    Phone
                                    <input class=""  name="fam-phone" tabindex="3" value>
                                </label>
                            </div>
                            <div class="col-3 editfam-div">
                                <label>
                                    Street
                                    <input class=""  name="fam-phy1" tabindex="4" value>
                                </label>
                            </div>
                            <div class="col-3 editfam-div">
                                <label>
                                    Suburb
                                    <input class=""  name="fam-phy2" tabindex="5" value>
                                </label>
                            </div>
                            <div class="col-3 editfam-div">
                                <label>
                                    City
                                    <input class=""  name="fam-phy3" tabindex="6" value>
                                </label>
                            </div>
                            <div class="col-3 editfam-div">
                                <label>
                                    Postal Street
                                    <input class=""  name="fam-post1" tabindex="7" value>
                                </label>
                            </div>
                            <div class="col-3 editfam-div">
                                <label>
                                    Postal Suburb
                                    <input class=""  name="fam-post2" tabindex="8" valu>
                                </label>
                            </div>
                            <div class="col-3 editfam-div last-row">
                                <label>
                                    Postal City
                                    <input class=""  name="fam-post3" tabindex="9" value>
                                </label>
                            </div>

                            <hr />

                            <div class="col-submit">
                                <input type="hidden" name="newfamily">
                                <button class="submitbtn" value="Submit">Submit</button>
                            </div>
                        </form>
                        </div>
                        </div><!--widget-->
                    </div><!--span10-->
                </div><!--row-fluid-->
                
                <div class="footer">
                    <div class="footer-left">
                        <span>&copy; 2016. Dance Education Center. All Rights Reserved.</span>
                    </div>
                    <div class="footer-right">
                        <span>Powered by: <a href="http://www.microsolution.co.nz/" target="_blank">Micro Solution</a></span>
                    </div>
                </div><!--footer-->
                
            </div><!--maincontentinner-->
        </div><!--maincontent-->
        
    </div><!--rightpanel-->
    
</div><!--mainwrapper-->
<script type="text/javascript">
    
</script>
</body>
</html>
<?php } ?>