<?php
include("include/session.php");
global $database;
$config = $database->getConfigs();
$gl = $database->getAllGL();
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
            <li>Dashboard</li>
        </ul>
        
        <div class="pageheader">
            <div class="pageicon"><i class="fa fa-laptop" aria-hidden="true"></i></div>
            <div class="pagetitle">
                <h5>All Features Summary</h5>
                <h1>Dashboard</h1>
            </div>
        </div><!--pageheader-->
        
        <div class="maincontent">
            <div class="maincontentinner">
                <div class="row-fluid">
                    <div id="dashboard-left" class="span12">
                    <div class="widget">
                        <h4 class="widgettitle">Dashboard</h4>
                        <div class="widgetcontent">
                            <div class="default-option">
                            <span>Update Invoices Date</span>
                                <div id="update-inv">
                                    <select class="input-medium update-inv-term">
                                        <?php
                                        $allterm = $database->getAllTerms();
                                        $curr_term = $database->getCurrTerm()[0]['DefTerm'];
                                        for($i=0;$i<sizeof($allterm);$i++) {
                                            if($allterm[$i]['TermID']==$curr_term)
                                                echo "<option data-term=\"". $allterm[$i][0] ."\" value=\"".$allterm[$i]['Year'].$allterm[$i]['Term']."\" selected>".$allterm[$i]['Year']. ' Term ' .$allterm[$i]['Term']."</option>";
                                            else
                                                echo "<option data-term=\"". $allterm[$i][0] . "\"value=\"".$allterm[$i]['Year'].$allterm[$i]['Term']."\">".$allterm[$i]['Year']. ' Term ' .$allterm[$i]['Term']."</option>";
                                        }
                                        ?>
                                    </select>

                                    <select name="select-fam" id="select-fam" class="input-xlarge uniformselect update-inv-fam">
                                        <option value="">Please select the family...</option>
                                        <?php  
                                            $allfam = $database->getAllFamily();
                                            for($i=0; $i<sizeof($allfam); $i++) {
                                                echo "<option value=\"".$allfam[$i]['FamilyID']."\" data-last=\"".$allfam[$i]['Last']."\">".$allfam[$i]['Code'].' | '.$allfam[$i]['Last'] ."</option>";
                                            }
                                        ?>
                                    </select>

                                    <select class="input-medium update-inv-type">
                                        <option value="T">Term</option>
                                        <option value="C">Class</option>
                                    </select>
                                    <button class="btn btn-primary update-inv-bt" id="update-by-select">GET</button>

                                    <br />

                                    <input type="text" class="input-medium update-invno">

                                    <button class="btn btn-primary update-inv-bt" id="update-by-no">GET</button>

                                    <div class="update-inv-res">
                                        <div>
                                            <div class="inv-res-3"><span></span></div>
                                            <div class="inv-res-3"><input class="input-medium" type="date"></div>
                                            <div class="inv-res-3"><button id="update-inv-date" class="btn btn-primary">Update</button></div>
                                        </div>

                                        <div class="inv-res-newpdf">
                                            <a href="" target="_black"></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr />

                            <div class="default-option">
                            <span>Current Term</span>
                                <div id="curr-term">
                                    <select id="curr_term">
                                        <?php
                                        $allterm = $database->getAllTerms();
                                        $curr_term = $database->getCurrTerm()[0]['DefTerm'];
                                        for($i=0;$i<sizeof($allterm);$i++) {
                                            if($allterm[$i]['TermID']==$curr_term)
                                                echo "<option value=\"".$allterm[$i]['TermID']."\" selected>".$allterm[$i]['Year']. ' Term ' .$allterm[$i]['Term']."</option>";
                                            else
                                                echo "<option value=\"".$allterm[$i]['TermID']."\">".$allterm[$i]['Year']. ' Term ' .$allterm[$i]['Term']."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <hr />
                            <div class="default-option">
                            <span>Accounting Code</span>
                                <div id="acc-code">
                                    <div id="acc-content">
                                    <p>Classes</p>
                                    <input type="checkbox" class="acc-check" name="acc-class" id="acc-class" value="1" <?php if($gl[0]['Print']==1) echo "checked"; ?>>
                                    <hr />
                                    <p>Exams</p>
                                    <input type="checkbox" class="acc-check" name="acc-exam" id="acc-exam" value="2" <?php if($gl[1]['Print']==1) echo "checked"; ?>>                       
                                    <hr />
                                    <p>Exam Assessment</p>
                                    <input type="checkbox" class="acc-check" name="acc-examass" id="acc-examass" value="3" <?php if($gl[2]['Print']==1) echo "checked"; ?>>
                                    <hr />
                                    <p>Constumes</p>
                                    <input type="checkbox" class="acc-check" name="acc-cons" id="acc-cons" value="4" <?php if($gl[3]['Print']==1) echo "checked"; ?>>
                                    <hr />
                                    <p>Invoice Adjustment</p>
                                    <input type="checkbox" class="acc-check" name="acc-ia" id="acc-ia" value="5" <?php if($gl[4]['Print']==1) echo "checked"; ?>>
                                    <hr />
                                    <p>Music/Syllabus</p>
                                    <input type="checkbox" class="acc-check" name="acc-music" id="acc-music" value="6" <?php if($gl[5]['Print']==1) echo "checked"; ?>>
                                    <hr />
                                    <p>Dance Awards</p>
                                    <input type="checkbox" class="acc-check" name="acc-award" id="acc-award" value="7" <?php if($gl[6]['Print']==1) echo "checked"; ?>>
                                    <hr />
                                    <p>Videos</p>
                                    <input type="checkbox" class="acc-check" name="acc-video" id="acc-video" value="8" <?php if($gl[7]['Print']==1) echo "checked"; ?>>
                                    <hr />
                                    <p>Studio Hire</p>
                                    <input type="checkbox" class="acc-check" name="acc-studio" id="acc-studio" value="9" <?php if($gl[8]['Print']==1) echo "checked"; ?>>
                                    <hr />
                                    <p>Show Tickets</p>
                                    <input type="checkbox" class="acc-check" name="acc-ticket" id="acc-ticket" value="10" <?php if($gl[9]['Print']==1) echo "checked"; ?>>
                                    <hr />
                                    <p>Internet Payment</p>
                                    <input type="checkbox" class="acc-check" name="acc-internet" id="acc-internet" value="11" <?php if($gl[10]['Print']==1) echo "checked"; ?>>
                                    <hr />
                                    <p>Eft-Pos Payment</p>
                                    <input type="checkbox" class="acc-check" name="acc-eftpos" id="acc-eftpos" value="12" <?php if($gl[11]['Print']==1) echo "checked"; ?>>
                                    <hr />
                                    <p>Competitions</p>
                                    <input type="checkbox" class="acc-check" name="acc-compet" id="acc-compet" value="13" <?php if($gl[12]['Print']==1) echo "checked"; ?>>
                                    <hr />
                                    <p>Dancewear</p>
                                    <input type="checkbox" class="acc-check" name="acc-wear" id="acc-wear" value="14" <?php if($gl[13]['Print']==1) echo "checked"; ?>>
                                    <hr />
                                    <p>Cash Payment</p>
                                    <input type="checkbox" class="acc-check" name="acc-cash" id="acc-cash" value="15" <?php if($gl[14]['Print']==1) echo "checked"; ?>>
                                    <hr />
                                    <p>Sewing Room Items</p>
                                    <input type="checkbox" class="acc-check" name="acc-sewing" id="acc-sewing" value="16" <?php if($gl[15]['Print']==1) echo "checked"; ?>>
                                    <hr />
                                    </div>
                                </div>
                            </div>
                        </div><!--widgetcontent-->
                    </div><!--widget-->
                    </div>
                </div>
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
    jQuery(document).ready(function($){
        var curr_term = <?php echo $curr_term; ?>;
        // dynamic table
        jQuery('#dyntable').dataTable({
            "paging": false,
            "aaSortingFixed": [[0,'desc']],   
            "fnDrawCallback": function(oSettings) {
                jQuery.uniform.update();
            }
        });

        $('#update-by-select').click(function() {
            var invno = $('.update-inv-term').val() + $('#select-fam').val() + $('.update-inv-type').val();
            console.log(invno);
            $.ajax({
                dataType: "JSON",
                type: 'post',
                url: 'process.php',
                data: {get_update_inv: 1, invno: invno},
                success: function(output) {
                    if(output == null) {
                        window.confirm('No Invoice Found');
                    }
                    $('.inv-res-3:first-child span').text(output[0]['InvNo']);
                    $('.inv-res-3:nth-child(2) input').val(output[0]['Date']); 
                    $('.update-inv-res').slideDown('slow')
                }
            })
        })

        $('#update-by-no').click(function() {
            $.ajax({
                dataType: "JSON",
                type: 'post',
                url: 'process',
                data: {get_update_inv: 1, invno: $('.update-invno').val()},
                success: function(output) {
                    if(output == null) {
                        window.confirm('No Invoice Found');
                    }
                    $('.inv-res-3:first-child span').text(output[0]['InvNo']);
                    $('.inv-res-3:nth-child(2) input').val(output[0]['Date']); 
                    $('.update-inv-res').slideDown('slow')
                }
            })
        })

        $('#update-inv-date').click(function() {
            $.ajax({
                dataType: "JSON",
                type: 'post',
                url: 'process',
                data: {update_inv:1, invno: $('.inv-res-3:first-child span').text(), date: $('.inv-res-3:nth-child(2) input').val()},
                success: function(output) {
                    var tmpterm = $('.update-inv-term option:selected').data('term');
                    var tmpurl = "single_inv_pdf?inv_no=" + $('.inv-res-3:first-child span').text() + "&term_id=" + tmpterm;
                    $('.inv-res-newpdf a').attr("href", tmpurl);
                    $('.inv-res-newpdf a').text($('.inv-res-3:first-child span').text());
                    $('.inv-res-newpdf').slideDown('slow', function() {
                    })
                }
            })
        })
    });
</script>
</body>
</html>
<?php } ?>