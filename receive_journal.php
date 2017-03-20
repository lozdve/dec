<?php
include("include/session.php");
global $database;
$config = $database->getConfigs();
$curr_term = $database->getCurrTerm()[0]['DefTerm'];
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
            <li>New Journal</li>
        </ul>
        
        <div class="pageheader">
            <div class="pageicon"><i class="fa fa-laptop" aria-hidden="true"></i></div>
            <div class="pagetitle">
                <h1>New Journal</h1>
            </div>
        </div><!--pageheader-->
        
        <div class="maincontent">
            <div class="maincontentinner">
                <div class="row-fluid">
                    <div id="dashboard-left" class="span12">
                        <div class="widget">
                            <h4 class="widgettitle">New Journal</h4>
                            <div class="widgetcontent">
                                <form class="stdform" action="process" method="post" enctype="multipart/form-data">
                                    <p>
                                        <label>Term</label>
                                        <span class="field"><select name="report-term" id="report-term" class="input-large uniformselect">
                                            <?php  
                                                $allterm = $database->getAllTerms();
                                                for($i=0; $i<sizeof($allterm); $i++) {
                                                    echo "<option value=\"".$allterm[$i]['TermID']."\"" .(($allterm[$i]['TermID']==$curr_term)?'selected=true':''). ">".$allterm[$i]['Year'].' Term '.$allterm[$i]['Term'] ."</option>";
                                                }
                                            ?>
                                        </select></span> 
                                    </p>

                                    <p>
                                        <label>Family</label>
                                        <span class="field"><select name="select-fam" id="select-fam" class="input-xlarge uniformselect">
                                        <option value="">Please select the family...</option>
                                        <?php  
                                            $allfam = $database->getAllFamily();
                                            for($i=0; $i<sizeof($allfam); $i++) {
                                                echo "<option value=\"".$allfam[$i]['FamilyID']."\" data-last=\"".$allfam[$i]['Last']."\">".$allfam[$i]['Code'].' | '.$allfam[$i]['Last'] ."</option>";
                                            }
                                        ?>
                                        </select></span>
                                    </p>

                                    <p>
                                        <label>Date</label>
                                        <span class="field"><input type="date" name="report-date" value="<?php echo date('Y-m-d'); ?>"></span>
                                    </p>

                                    <p>
                                        <label>Description</label>
                                        <span class="field"><input type="text" name="report-desc"></span>
                                    </p>

                                    <p>
                                        <label>Amount</label>
                                        <span class="field"><input type="text" name="report-amount"></span>
                                    </p>

                                    <p>
                                        <label>Accounting</label>
                                        <span class="field"><select name="report-acct" class="input-large">
                                            <?php 
                                                $gl = $database->getAllGL();
                                                for($i=0;$i<sizeof($gl);$i++) {
                                                    echo "<option value=\"".$gl[$i]['GL']."\">".$gl[$i]['Description']."</option>";
                                                }
                                            ?>
                                        </select></span>
                                    </p>

                                    <p class="stdformbutton">
                                        <input type="hidden" name="new_journal" value="1">
                                        <button class="btn btn-primary">Process</button>
                                    </p>
                                </form>
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