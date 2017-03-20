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

        <div id="post_inv" class="invModal" title="Message">
            <div class="invModal-content">
                <div class="modal-header">
                    <h3>Message</h3>
                </div>
                <div class="modal-body">
                    <p>Do you want to close off this banking?</p>
                </div>
                <div class="modal-footer">   
                    <span>
                    <button id="post_yes">Yes</button>
                    <button id="post_no">No</button>
                    </span>
                </div>
            </div>
        </div>
        
        <ul class="breadcrumbs">
            <li><a href="main"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
            <li>Banking</li>
        </ul>
        
        <div class="pageheader">
            <div class="pageicon"><i class="fa fa-laptop" aria-hidden="true"></i></div>
            <div class="pagetitle">
                <h1>Banking</h1>
            </div>
        </div><!--pageheader-->
        
        <div class="maincontent">
            <div class="maincontentinner">
                <div class="row-fluid">
                    <div id="dashboard-left" class="span12">
                        <div class="widget">
                            <h4 class="widgettitle">Banking</h4>
                            <div class="widgetcontent">
                                <table id="allbanking" class="display" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Description</th>
                                            <th>Reference</th>
                                            <th>Bank</th>
                                            <th>Branch</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                </table>
                                <br >
                                <div style="text-align: center;">
                                    <a href="banking_pdf" target="_blank"><button class="btn btn-primary btn-banking">Print</button></a>
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

<script type="text/javascript">
    var editor;
    jQuery(document).ready(function($){
        editor = new $.fn.dataTable.Editor( {
            ajax: {
                "url": "include/csmanage.php",
                type: "post",
                data: {
                    "banking": 1
                }
            },
            table: "#allbanking",
            fields: [
            {   label: '',
                name: "Reference"
            }, {
                label: '',
                name: "Bank"
            }, {
                label: '',
                name: 'Branch'
            }
            ]
        });

        $('#allbanking').DataTable({
            ajax: {
                url: "include/csmanage.php",
                type: "post",
                data: {
                    "banking" : 1
                }
            },
            paging: false,
            info: false,
            lengthChange: false,
            "columns": [
            {   data: "Description"},
            {   data: "Reference"},
            {   data: "Bank"},
            {   data: "Branch"},
            {   data: "Amount"}
            ]
        })

        $('#allbanking').on( 'click', 'tbody td:not(:first-child)', function (e) {
            editor.inline( this, {
                onBlur: 'submit',
            } );
        } );

        $('.btn-banking').click(function() {
            $('#post_inv').css('display', 'block');
        })

        $('#post_no').click(function() {
            $('#post_inv').fadeOut('slow');
        })

        $('#post_yes').click(function() {
            $.ajax({
                type: 'POST',
                url: 'process.php',
                data: {del_banking: 1},
                success: function() {
                    $('#post_inv').fadeOut('slow');
                    $('#allbanking').DataTable().ajax.reload();
                }
            })
        })

    });

</script>
</body>
</html>
<?php } ?>