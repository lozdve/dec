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
            <li>new class</li>
        </ul>
        
        <div class="pageheader">
            <div class="pageicon"><i class="fa fa-laptop" aria-hidden="true"></i></div>
            <div class="pagetitle">
                <h1>New Class</h1>
            </div>
        </div><!--pageheader-->
        
        <div class="maincontent">
            <div class="maincontentinner">
                <div class="row-fluid">
                    <div id="dashboard-left" class="span12">
                        <div class="widget">
                        <h4 class="widgettitle">New Class</h4>
                        <div class="widgetcontent">
                        <form id="editfam" method="post" action="process">
                            <div class="col-2 editfam-div">
                                <label>
                                    Code
                                    <input class="ajaxnewclass" name="class-code" tabindex="1" value>
                                </label>
                            </div>
                            <div class="col-2 editfam-div">
                                <label>
                                    Class
                                    <input class="ajaxnewclass"  name="class-name" tabindex="2" value>
                                </label>
                            </div>
                            <div class="col-3 editfam-div">
                                <label>
                                    $Session
                                    <input class="ajaxnewclass"  name="class-session" tabindex="3" value>
                                </label>
                            </div>
                            <div class="col-3 editfam-div">
                                <label>
                                    $Term
                                    <input class="ajaxnewclass"  name="class-term" tabindex="4" value>
                                </label>
                            </div>
                            <div class="col-3 editfam-div">
                                <label>
                                    $Exam
                                    <input class="ajaxnewclass"  name="class-exam" tabindex="5" value>
                                </label>
                            </div>
                            <div class="col-3 editfam-div">
                                <label>
                                    $Exam Assessment
                                    <input class="ajaxnewclass"  name="class-examass" tabindex="6" value>
                                </label>
                            </div>
                            <div class="col-3 editfam-div">
                                <label>
                                    Category
                                    <select class="ajaxnewclass" name="class-cat">
                                        <?php 
                                        $clscat = $database->getClassCat();
                                        for($i=0; $i<sizeof($clscat); $i++) {
                                            echo "<option value=\"".$clscat[$i][0]."\">".$clscat[$i][1]."</option>";
                                        }
                                        ?>
                                    </select>
                                </label>
                            </div>
                            <div class="col-3 editfam-div last-row">
                                <label>
                                    Next Grade
                                    <select class="ajaxnewclass" name="class-grade">
                                        <?php 
                                        $allcls = $database->getAllClass();
                                        for($i=0; $i<sizeof($allcls); $i++) {
                                            echo "<option value=\"".$allcls[$i][0]."\">".$allcls[$i][2]."</option>";   
                                        }
                                        ?>
                                    </select>
                                </label>
                            </div>

                            <hr />

                            <div class="col-submit">
                                <input type="hidden" name="newclass">
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