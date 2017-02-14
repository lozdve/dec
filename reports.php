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
            <li>Reports</li>
        </ul>
        
        <div class="pageheader">
            <div class="pageicon"><i class="fa fa-laptop" aria-hidden="true"></i></div>
            <div class="pagetitle">
                <h1>Reports</h1>
            </div>
        </div><!--pageheader-->
        
        <div class="maincontent">
            <div class="maincontentinner">
                <div class="row-fluid">
                    <div id="dashboard-left" class="span12">
                        <div class="widget">
                            <h4 class="widgettitle">Reports</h4>
                            <div class="widgetcontent">
                                <div class="report-div">
                                    <div class="report-debtors">
                                        <input type="date" name="debtors" id="debtors" value="<?php echo date('Y-m-d'); ?>">
                                        <span class="subreport-right">
                                        <button type="button" class="btn btn-default debtors_report">Debtors Outstanding</button>
                                        </span>
                                        <hr>
                                    </div>
                                    <div class="journal-detail">
                                        From: <input type="date" name="from_j_detail" id="from_j_de" class="input-small">
                                        To: <input type="date" name="to_j_detail" id="to_j_de" class="input-small">
                                        <span class="subreport-right">
                                        <button type="button" class="btn btn-default journal_detail">Journal Detail</button>   
                                        </span>   
                                        <hr>      
                                    </div>
                                    <div class="journal-sum">
                                        <form action="process">
                                        From: <input type="date" name="from_j_sum" id="from_j_sum" class="input-small">
                                        To: <input type="date" name="to_j_sum" id="to_j_sum" class="input-small">
                                        <span class="subreport-right">
                                        <button type="button" class="btn btn-default journal_sum">Journal Summary</button>   
                                        </span>     
                                        <hr>    
                                        </form>
                                    </div>
                                </div>
                            </div><!--widgetcontent-->
                        </div><!--dashboard-left-->
                    </div><!--row-fluid-->
                
                    <div class="footer">
                        <div class="footer-left">
                            <span>&copy; 2016. Dance Education Center. All Rights Reserved.</span>
                        </div>
                        <div class="footer-right">
                            <span>Powered by: <a href="http://www.microsolution.co.nz/" target="_blank">Micro Solution</a></span>
                        </div>
                    </div><!--footer-->                
                </div><!--row-fluid-->
            </div><!--maincontentinner-->
        </div><!--maincontent-->
    </div><!--rightpanel-->
    
</div><!--mainwrapper-->
</body>
</html>
<?php } ?>