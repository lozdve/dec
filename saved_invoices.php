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
        <div class="" id="saved_popup_suc" title="Message">
            <div class="modal-content">
                <p>New Invoices have been processed.</p>
            </div>
        </div>

        <div id="post_inv" class="invModal" title="Message">
            <div class="invModal-content">
                <div class="modal-header">
                    <h3>Message</h3>
                </div>
                <div class="modal-body">
                    <p>Do you want to post this invoice?</p>
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
            <li>Saved Invoices</li>
        </ul>
        
        <div class="pageheader">
            <div class="pageicon"><i class="fa fa-laptop" aria-hidden="true"></i></div>
            <div class="pagetitle">
            <h1>Saved Invoices</h1>
            </div>
        </div><!--pageheader-->
        
        <div class="maincontent">
            <div class="maincontentinner">
                <div class="row-fluid">
                    <div id="dashboard-left" class="span12">
                    <div class="widget">                       
                        <h4 class="widgettitle">Saved Invoices</h4>
                        <div class="widgetcontent">
                        <div id="saved_form">
                        <form action="" method="post" name="savedinv_form">
                            <label>Term: </label>
                            <select class="input-medium" name="saved_term" id="saved_term">
                                <?php  
                                    $allterm = $database->getAllTerms();
                                    for($i=0; $i<sizeof($allterm); $i++) {
                                        if($allterm[$i]['InvoicedTerm'] != '1' || $allterm[$i]['InvoicedClass'] != '1'){
                                            echo "<option value=\"".$allterm[$i]['TermID']."\" data-inv=\"".$allterm[$i]['InvoicedTerm'].$allterm[$i]['InvoicedClass']."\">".$allterm[$i]['Year'].' Term '.$allterm[$i]['Term'] ."</option>";
                                        }
                                    }
                                ?>
                            </select>
                            <label>Invoice Date:</label>
                            <input type="date" class="input-medium" name="saved_date" id="saved_date" value="<?php echo date('Y-m-d'); ?>">
                            <label>Invoiced Type:</label>
                            <select class="input-medium" name="saved_type" id="saved_type">
                                <!-- <option value="1">Class</option> -->
                                <!-- <option value="2">Term</option> -->
                            </select>
                            <input type="hidden" name="invterm">
                            <button class="btn btn-default invterm">GET</button>
                        </form>
                        </div>
                        
                        <div class="" id="saved_popup_err" title="Message">
                            <p>No data available</p>
                        </div>

                        

                        <div>
                            <table id="savedinv_table" class="display" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Name</th>
                                        <th>Address</th>
                                        <th>InvNo</th>
                                        <th></th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    </div><!--span12-->
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
    </div>
    </div><!--rightpanel-->
    
</div><!--mainwrapper-->
<script type="text/javascript">
    var editor;
    jQuery(document).ready(function($){
        

        // dynamic table
        jQuery('#dyntable').dataTable({
            "paging": false,
            "aaSortingFixed": [[0,'desc']],   
            "fnDrawCallback": function(oSettings) {
                jQuery.uniform.update();
            }
        });

        $('#saved_term').change(function(){
            // if($(this).val() == 67)
            // alert($(this).find('option:selected').data("inv"));
            // alert($(this).val());
            // $('#saved_type').append($('<option>', {value:1, text:"Test"}));
            var tmp = $(this).find('option:selected').data("inv");
            if(tmp == '00') {
                $('#saved_type').empty();
                $('#saved_type').append($('<option>', {value:'C', text:"Class"}));
                $('#saved_type').append($('<option>', {value:'T', text:"Term"}));
            } else if(tmp == '01') {     
                $('#saved_type').empty();            
                $('#saved_type').append($('<option>', {value:'T', text:"Term"}));
            } else if(tmp == '10') {    
                $('#saved_type').empty();           
                $('#saved_type').append($('<option>', {value:'C', text:"Class"}));
            }
        }).change();

        // $('.invterm').click(function() {
            var saved_table = $('#savedinv_table').dataTable({
                ajax: {
                    url: "include/csmanage.php",
                    type: "post",
                    data: {
                        "get_inv": 1
                    }
                },
                paging: false,
                info: false,
                "columns": [
                    { data: "family.Code", width: "10%"},
                    { data: "family.Last", width: "20%"},
                    { data: null, "orderable": false, width: "25%", render: function(data, type, row) {
                        var d1 = data.family.Physical1;
                        var d2 = data.family.Physical2;
                        var d3 = data.family.Physical3;
                        if(d1 == null) {
                            return null;
                        } else if(d2 ==null) {
                            return d1;
                        } else if(d3 ==null) {
                            return d1+', '+d2;
                        } else {
                            return d1+ ', '+ d2;
                        }
                    } },
                    { data: "invidx.InvNo", width: "15%"},
                    { data: null, width: "10%"}
                ],
                "columnDefs":[{
                    "targets": 4,
                    "data": null,
                    render: function(data, type,full,meta) {
                        return "<a href=\"edit_saved_invoices?inv_no=" + data.invidx.InvNo + "&termid=" + data.invidx.TermID + "\"><i class=\"fa fa-lg fa-pencil-square-o\" aria-hidden=\"true\"></i></a>" + 
                                "<a class=\"invpdf\" href=\"single_inv_pdf?inv_no="+data.invidx.InvNo+"&term_id=" + data.invidx.TermID + "\" data-inv=\"" + data.invidx.InvNo + "\"  data-term=\"" + data.invidx.TermID + "\" target=\"_blank\"><i class=\"fa fa-file-pdf-o\" aria-hidden=\"true\"></i></a>"
                    }
                }]
            })
        // })

        $('.invterm').click(function() {
            setTimeout(function() {                
                $('#savedinv_table').DataTable().ajax.reload();
            }, 1000);
        })

        var tmp1, tmp2;

        $('#savedinv_table tbody').on('click', 'tr td a.invpdf', function() {
            $('#post_inv').css('display', 'block');
            tmp1 = ($(this).data('inv'));
            tmp2 = $(this).data('term');
        })

        $('#post_no').click(function(e) {
            e.stopPropagation();
            $('#post_inv').fadeOut('slow', function() {
                $('#post_inv').css('display', 'none');          
            })
        })

        $('#post_yes').click(function(e) {
            $.ajax({
                type: 'POST',
                url: 'process.php',
                data: {saved_invno: tmp1},
                success: function() {
                    $('#post_inv').fadeOut('slow', function() {
                        $('#post_inv').css('display', 'none');          
                    })
                    $('#savedinv_table').DataTable().ajax.reload();
                }
            })
        })
        
    });
</script>
</body>
</html>
<?php } ?>