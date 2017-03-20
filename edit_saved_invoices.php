<?php
include("include/session.php");
global $database;
$config = $database->getConfigs();
$inv_no = $_GET['inv_no'];
$termyear = substr($inv_no, 0, 4);
$termt = substr($inv_no, 4, 1);
$paydefault = substr($inv_no, -1);
$famid = substr($inv_no, 5, -1);
$termid = $_GET['termid'];

$stu_id = $database->getPeopleDetailbyID($famid);
$stu_query = null;

for($i=0;$i<sizeof($stu_id);$i++) {
	if($i != sizeof($stu_id)-1 ) {
		$stu_query .=$stu_id[$i][0].', ';
	}
	else
		$stu_query .= $stu_id[$i][0];
}

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
            <li>Edit Invoice</li>
        </ul>
        
        <div class="pageheader">
            <div class="pageicon"><i class="fa fa-laptop" aria-hidden="true"></i></div>
            <div class="pagetitle">
                <h1>Edit Invoice</h1>
            </div>
        </div><!--pageheader-->
        
        <div class="maincontent">
            <div class="maincontentinner">
                <div class="row-fluid">
                    <div id="dashboard-left" class="span12">
                        <div class="widget">
                            <h4 class="widgettitle">Invoice No: <?php echo $inv_no; ?></h4>
                            <div class="widgetcontent">
                                <table id="edit_invoices" class="display" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Student</th>
                                            <th>Class</th>
                                            <th>Attended</th>
                                            <th>$Class</th>
                                            <th>$Term</th>
                                            <th>Discount</th>
                                            <th>$Exam</th>
                                            <th>Total</th>
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
	jQuery(document).ready(function($) {
		editor = new $.fn.dataTable.Editor({ 
			ajax: {
				"url": "include/editabletable.php",
				"type": "post",
				"data": {
					inv_no: <?php echo "'".$inv_no."'"; ?>,
					famid: <?php echo $famid; ?>,
					termid: <?php echo $termid; ?>
				}
			},
			table: "#edit_invoices",
			fields: [ 
			{
				label: "Student",
				name: "invtrans.StudentID",
				type: 'select'
			}, {
				label: "Class",
				name: "invtrans.Description"
			}, {
				label: "Attended",
				name: "invtrans.QtyAttend"
			}, {
				label: "Session",
				name: "invtrans.Session"
			}, {
				label: "Term",
				name: "invtrans.Term"
			}, {
				label: "Exam",
				name: "invtrans.Exam"
			}, {
				label: "Discount",
				name: "invtrans.discount",
				type: "checkbox",
				separator: "|",
				options: [
					{label:'', value:1}
				]
			}, {
				name: "invtrans.InvNo",
				type: "hidden",
				def: <?php echo "'".$inv_no."'"; ?>
			}
			]
		});

		$('#edit_invoices').dataTable({
			dom: "Bfrtip",
			ajax: {
				url: "include/editabletable.php",
				type: "post",
				data: {
					inv_no: <?php echo "'".$inv_no."'"; ?>,
					famid: <?php echo $famid; ?>,
					termid: <?php echo $termid; ?>
				}
			},
			paging: false,
            info: false,
            searching: false,
            "columns": [
            	{ data: null, width: "9%", render:	function(data,type,row) {
            		return data.people.First + ' ' + data.people.Last;
            	}},
            	{ data: "invtrans.Description", width: "10%"},
            	{ data: "invtrans.QtyAttend", width: "3%"},
            	{ data: "invtrans.Session", width:"5%"},
            	{ data: null, width: "5%", editField: "invtrans.Term", render: function(data,type,row) {
            		var payment = data.invtrans.InvNo.slice(-1);
            		if(payment == 'T') {
            			return parseFloat(data.invtrans.Term).toFixed(2);
            		} else {
            			if(data.invtrans.Term != 0) {
            				return parseFloat(data.invtrans.Term).toFixed(2);
            			} else
            				return parseFloat(data.invtrans.QtyAttend*data.invtrans.Session);
            		}
            	}},
            	{
            	  data:"invtrans.discount", width: "3%", render:function(data,type,row) {
            	  		if(type==='display') {
            	  			return '<input type="checkbox" class="invtrans-discount">'
            	  		}
            	  		return data;
            	    }
            	},
            	{ data: "invtrans.Exam", width: "5%", render: function(data,type,row){
            		return parseFloat(data).toFixed(2);
            	}},
            	{ data: null, width: "5%", render: function(data,type,row) {
            		var payment = data.invtrans.InvNo.slice(-1);
            		// console.log(typeof(data.invtrans.Session));
            		if(payment == 'T')
            			return parseFloat(parseFloat(data.invtrans.Term) + parseFloat(data.invtrans.Exam)).toFixed(2);
            		else
            			return parseFloat(parseFloat(data.invtrans.Term) + parseFloat(data.invtrans.Exam)).toFixed(2);
            	}}
            ],
            select: {
                style:    'os',
                selector: 'td:first-child'
            },
            buttons: [
            	{ extend: "create", editor: editor},
            	{ extend: "remove", editor: editor}
            ],
            rowCallback: function(row,data) {
            	$('input.invtrans-discount', row).prop('checked', data.invtrans.discount==1);
            }
		});

		$('#edit_invoices').on('click', 'tbody td:not(:first-child):not(:nth-last-child(3))',
			function(e) {
				editor.inline( this, {
					submit: 'all'
				});
			});

		$('#edit_invoices').on('change', 'input.invtrans-discount', function() {
			editor.edit( $(this).closest('tr'), false)
					.set("invtrans.discount", $(this).prop('checked') ? 1 : 0)
					.submit();
		})


		editor.on('preSubmit', function(e,d,a) {
			if(a=== 'edit') {
				$.each( d.data, function ( key, row ) {
					if(row.invtrans.QtyAttend != 0){
			      		row.invtrans.Term = row.invtrans.QtyAttend * row.invtrans.Session;
			      		if(row.invtrans.discount != 0 ) {
			      			row.invtrans.Term *= 0.9;
			      		} 
					} else {
						if(row.invtrans.discount != 0 ) {
							row.invtrans.Term *= 0.9;
						}
					}
			    } );
			}
		})
	})
</script>

<?php } ?>