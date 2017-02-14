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
                            <h4 class="widgettitle">Family</h4>
                            <div class="widgetcontent">
                                <table id="allstudents" class="display" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Code</th>
                                            <th>Family Name</th>
                                            <th>Address</th>
                                            <th>Phone</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                </table>
                                <!-- tree table for job display -->
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
                    "family": 1
                }
            },
            table: "#allstudents"
        });
        // dynamic table
        jQuery('#allstudents').dataTable({
            ajax: {
                url: "include/csmanage.php",
                type: "post",
                data: {
                    "family": 1
                }
            },
            paging: false,
            info: false,
            deferRender: true,
            lengthChange: false,
            "columns": [
                { data: "Code", width: "10%"},
                { data: "Last", "orderable": false, width: "15%" },
                { data: null, "orderable": false, width: "20%", render: function(data, type, row) {
                        var d1 = data.Physical1;
                        var d2 = data.Physical2;
                        var d3 = data.Physical3;
                        if(d1 == null) {
                            return null;
                        } else if(d2 ==null) {
                            return d1;
                        } else if(d3 ==null) {
                            return d1+' '+d2;
                        } else {
                            return d1+ ' '+ d2 +' '+ d3;
                        }
                    } },
                { data: "Phone", "orderable": false, width: "10%" },
                { data: null, "orderable": false, width: "10%" }
                ],
            "columnDefs":[{"targets":4,
                        "data": "FamilyID",
                        render: function(data,type,full,meta) {
                            return "<a href=\"edit_family?fam_id=" + data.FamilyID + "\"><i class=\"fa fa-lg fa-pencil-square-o\" aria-hidden=\"true\"></i></a>" + 
                                "<a href=\"family_pdf?fam_id="+data.FamilyID+"\" target=\"_blank\"><i class=\"fa fa-file-pdf-o\" aria-hidden=\"true\"></i></a>" +
                                "<a href=\"\" class=\"editor_remove\"><i class=\"fa fa-lg fa-trash\" aria-hidden=\"true\"></i></a>";
                        }
            }]
        });
        $('#allstudents').on('click', 'a.editor_remove', function (e) {
            e.preventDefault();
     
            editor.remove( $(this).closest('tr'), {
                title: 'Delete record',
                message: 'Are you sure you wish to remove this record?',
                buttons: 'Delete'
            });
        } );
    });
</script>
</body>
</html>
<?php } ?>