<?php
include("include/session.php");
global $database;
$config = $database->getConfigs();
$fam_id = $_GET['fam_id'];
$fam_info = $database->getFamilyDetailbyID($fam_id);
$people_info = $database->getPeopleDetailbyID($fam_id);
$term_info = $database->getTermInfo($fam_id);
$pid_array = array();
for($i=0; $i<sizeof($people_info); $i++) {
    if($people_info[$i]['Student'] == 1)
        array_push($pid_array, $people_info[$i]['PersonID']);
}
$pid_str = implode (",", $pid_array);
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
                </ul>
                <!--headmenu-->
            </div>
        </div>
        <?php include("include/menu.php"); ?>
        <div class="rightpanel">
            <ul class="breadcrumbs">
                <li><a href="main"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
                <li>Edit Family</li>
            </ul>
            <div class="pageheader">
                <div class="pageicon"><i class="fa fa-laptop" aria-hidden="true"></i></div>
                <div class="pagetitle">
                    <h1>Edit</h1>
                </div>
            </div>
            <!--pageheader-->
            <div class="maincontent">
                <div class="maincontentinner">
                    <div class="row-fluid">
                        <div id="dashboard-left" class="span12">
                            <div class="widget">
                                <h4 class="widgettitle"><?php echo $fam_info[0]['Code']; ?></h4>
                                <form id="editfam" data-fam-id='<?php echo $fam_info[0]['FamilyID']; ?>'>
                                    <div class="col-3 editfam-div">
                                        <label data-fam-id='<?php echo $fam_info[0]['FamilyID']; ?>'>
                                            Code
                                            <input class="ajaxfam" name="fam-code" tabindex="1" value='<?php echo $fam_info[0]['Code']; ?>'>
                                        </label>
                                    </div>
                                    <div class="col-3 editfam-div">
                                        <label>
                                            Last
                                            <input class="ajaxfam"  name="fam-last" tabindex="2" value='<?php echo $fam_info[0]['Last']; ?>'>
                                        </label>
                                    </div>
                                    <div class="col-3 editfam-div">
                                        <label>
                                            Phone
                                            <input class="ajaxfam"  name="fam-phone" tabindex="3" value='<?php echo $fam_info[0]['Phone']; ?>'>
                                        </label>
                                    </div>
                                    <div class="col-3 editfam-div">
                                        <label>
                                            Street
                                            <input class="ajaxfam"  name="fam-phy1" tabindex="4" value='<?php echo $fam_info[0]['Physical1']; ?>'>
                                        </label>
                                    </div>
                                    <div class="col-3 editfam-div">
                                        <label>
                                            Suburb
                                            <input class="ajaxfam"  name="fam-phy2" tabindex="5" value='<?php echo $fam_info[0]['Physical2']; ?>'>
                                        </label>
                                    </div>
                                    <div class="col-3 editfam-div">
                                        <label>
                                            City
                                            <input class="ajaxfam"  name="fam-phy3" tabindex="6" value='<?php echo $fam_info[0]['Physical3']; ?>'>
                                        </label>
                                    </div>
                                    <div class="col-3 editfam-div">
                                        <label>
                                            Postal Street
                                            <input class="ajaxfam"  name="fam-post1" tabindex="7" value='<?php echo $fam_info[0]['Post1']; ?>'>
                                        </label>
                                    </div>
                                    <div class="col-3 editfam-div">
                                        <label>
                                            Postal Suburb
                                            <input class="ajaxfam"  name="fam-post2" tabindex="8" value='<?php echo $fam_info[0]['Post2']; ?>'>
                                        </label>
                                    </div>
                                    <div class="col-3 editfam-div last-row">
                                        <label>
                                            Postal City
                                            <input class="ajaxfam"  name="fam-post3" tabindex="9" value='<?php echo $fam_info[0]['Post3']; ?>'>
                                        </label>
                                    </div>

                                    <hr />

                                    <ul class="nav nav-pills">
                                        <li class="active"><a data-toggle="pill" href="#tab1">Students</a></li>
                                        <li><a data-toggle="pill" href="#tab2">Guardians</a></li>
                                    </ul>

                                    <div class="tab-content">
                                        <div id="tab1" class="tab-pane fade in active">
                                                <table id="dyntable-stu" class="display">
                                                    <thead>
                                                        <tr>
                                                            <th></th>
                                                            <th>First</th>
                                                            <th>Middle</th>
                                                            <th>Last</th>
                                                            <th>Mobile</th>
                                                            <th>Medical</th>
                                                            <th>DOB</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                        </div>

                                        <div id="tab2" class="tab-pane fade">
                                            <table id="dyntable-guard" class="display dyntb" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th></th>
                                                        <th>First</th>
                                                        <th>Middle</th>
                                                        <th>Last</th>
                                                        <th>Mobile</th>
                                                        <th>Medical</th>
                                                        <th>DOB</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>

                                    <hr />
                                    <ul class="nav nav-pills">
                                        <li class="active"><a data-toggle="pill" href="#class">Classes</a></li>
                                        <li><a data-toggle="pill" href="#transaction">Transcations</a></li>
                                        
                                    </ul>

                                    <div class="tab-content">
                                        <div id="class" class="tab-pane fade in active">
                                            <div class="termonchange"><select class="termselect">
                                                <?php 
                                                for($i=0;$i<sizeof($term_info);$i++) {
                                                    echo "<option value=\"".$term_info[$i][0]."\">".$term_info[$i][2]." Term ". $term_info[$i][1]."</option>";
                                                }
                                                ?>
                                            </select></div>
                                                <table id="dyntable-class" class="display">
                                                    <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Class</th>
                                                            <th>Day</th>
                                                            <th>Time</th>
                                                            <th>PaymentDefault</th>
                                                            <th>PayAmount</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                        </div>
                                        <div id="transaction" class="tab-pane fade">
                                            <table id="dyntable-trans" class="display dyntb" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>InvNo</th>
                                                        <th>Reference</th>
                                                        <th>Description</th>
                                                        <th>Amount</th>
                                                        <th>TermID</th>
                                                        <th>TermName</th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!--span10-->
                    </div>
                    <!--row-fluid-->
                    <div class="footer">
                        <div class="footer-left">
                            <span>&copy; 2016. Dance Education Center. All Rights Reserved.</span>
                        </div>
                        <div class="footer-right">
                            <span>Powered by: <a href="http://www.microsolution.co.nz/" target="_blank">Micro Solution</a></span>
                        </div>
                    </div>
                    <!--footer-->
                </div>
                <!--maincontentinner-->
            </div>
            <!--maincontent-->
        </div>
        <!--rightpanel-->
    </div>
    <!--mainwrapper-->
    <script type="text/javascript">
    var editor;
    var editor_guard;
    jQuery(document).ready(function($) {
    editor = new $.fn.dataTable.Editor( {
        ajax: {
            "url": "include/editabletable.php",
            "type": "post",
            "data": {
                "boo_stu": 1,
                "famID": <?php echo $fam_id; ?>
            }
        },
        table: "#dyntable-stu",
        fields: [ {
                label: "First:",
                name: "First"
            }, {
                label: "Middle:",
                name: "Middle"
            }, {
                label: "Last:",
                name: "Last"
            }, {
                label: "Mobile #:",
                name: "Mobile"
            }, {
                label: "Medical:",
                name: "Medical"
            }, {
                label: "DOB:",
                type: "date",
                name: "DOB"
            }, {
                type: "hidden",
                name: "FamilyID",
                def: <?php echo $fam_id; ?>
            }, {
                type: "hidden",
                name: "Student",
                def: 1
            }
        ]
    } );

    editor_guard = new $.fn.dataTable.Editor( {
        ajax: {
            "url": "include/editabletable.php",
            "type": "post",
            "data": {
                "boo_stu": 0,
                "famID": <?php echo $fam_id; ?>
            }
        },
        table: "#dyntable-guard",
        fields: [ {
                label: "First:",
                name: "First"
            }, {
                label: "Middle:",
                name: "Middle"
            }, {
                label: "Last:",
                name: "Last"
            }, {
                label: "Mobile #:",
                name: "Mobile"
            }, {
                label: "Medical:",
                name: "Medical"
            }, {
                label: "DOB:",
                type: "date",
                name: "DOB"
            }, {
                type: "hidden",
                name: "FamilyID",
                def: <?php echo $fam_id; ?>
            }, {
                type: "hidden",
                name: "Student",
                def: 0
            }
        ]
    } );

    /*** Student Datatable Display ***/
    jQuery('#dyntable-stu').DataTable( {
    dom: "Bfrtip",
    ajax: {
        "url": "include/editabletable.php",
        "type": "post",
        "data": {
            "boo_stu": 1,
            "famID": <?php echo $fam_id; ?>
        }
    },
    "searching": false,
    "paging": false,
    "info": false,
    columns: [
        {
            data: null,
            defaultContent: '',
            className: 'select-checkbox',
            orderable: false,
            width: "5%" 
        },
        { data: "First", width: "15%" },
        { data: "Middle", width: "15%" },
        { data: "Last", width: "15%" },
        { data: "Mobile", width: "20%" },
        { data: "Medical", width: "15%" },
        { data: "DOB", width: "15%", render: function (data,type,row) {
                                            if(data != null)
                                                return moment(data).format( 'DD/MM/YYYY' );
                                            else
                                                return null;
                                        }
        }
    ],
    select: {
            style:    'os',
            selector: 'td:first-child'
        },
    buttons: [
        { extend: "create", editor: editor },
        { extend: "remove", editor: editor }
    ]
    } );

    $('#dyntable-stu').on( 'click', 'tbody td:not(:first-child)', function (e) {
        editor.inline( this, {
            onBlur: 'submit',
        } );
    } );

    /*** Guradian Datatable Display ***/
    jQuery('#dyntable-guard').DataTable( {
        dom: "Bfrtip",
        ajax: {
            "url": "include/editabletable.php",
            "type": "post",
            "data": {
                "boo_stu": 0,
                "famID": <?php echo $fam_id; ?>
            }
        },
        "searching": false,
        "paging": false,
        "info": false,
        columns: [
            {
                data: null,
                defaultContent: '',
                className: 'select-checkbox',
                orderable: false,
                width: "5%" 
            },
            { data: "First", width: "15%" },
            { data: "Middle", width: "15%" },
            { data: "Last", width: "15%" },
            { data: "Mobile", width: "20%" },
            { data: "Medical", width: "15%" },
            { data: "DOB", width: "15%", render: function (data,type,row) {
                                                if(data != null)
                                                    return moment(data).format( 'DD/MM/YYYY' );
                                                else
                                                    return null;
                                            }
            }
        ],
        select: {
                style:    'os',
                selector: 'td:first-child'
            },
        buttons: [
            { extend: "create", editor: editor_guard },
            { extend: "remove", editor: editor_guard }
        ]
    } );

    $('#dyntable-guard').on( 'click', 'tbody td:not(:first-child)', function (e) {
        editor_guard.inline( this, {
            onBlur: 'submit',
        } );
    } );

    /*** Class Datatable ***/
    if($('.termselect').val() == null) {
        $('#dyntable-class').DataTable( {
            "language": {
                "emptyTable":     "No Data Available in table"
            },
            "searching": false,
            "paging": false,
            "info": false,
        });
    } else {
        var class_table = jQuery('#dyntable-class').DataTable( {
        ajax: {
            "url": "include/editabletable.php",
            "type": "post",
            "data": function (d){
                d.personid = '<?php echo $pid_str; ?>',
                d.termid = $('.termselect').val();
            }
        },
        "searching": false,
        "paging": false,
        "info": false,
        columns: [
            { data: null, render: function(data, type, row) {
                    return data.people.First + ' ' + data.people.Last;
                }, width: "10%" },
            { data: "class.Class", width: "20%" },
            { data: "times.Day", width: "10%" },
            { data: "times.Time", width: "10%" },
            { data: "attendance.PayDefault", width: "15%" },
            { data: "attendance.PayAmount", width: "15%", render: $.fn.dataTable.render.number( ',', '.', 2, '$' ) },
        ]
        } );
    }

    $('.termselect').on('change',function() {
        class_table.ajax.reload();
    });

    /*** transactions table ***/
    $('#dyntable-trans').DataTable( {
        ajax: {
            "url": "include/editabletable.php",
            "type": "post",
            "data": {
                "trans": 1,
                "famID": <?php echo $fam_id; ?>
            }
        },
        "searching": false,
        "paging": false,
        "info": false,
        "columnDefs": [{ "targets": [5], "visible": false},{ "targets": [6], "visible": false}],
        columns: [
            { data: "invidx.Date", width: "15%", render: function (data,type,row) {
                                                if(data != null)
                                                    return moment(data).format( 'DD/MM/YYYY' );
                                                else
                                                    return null; 
                                            }
            },
            { data: "invidx", width: "15%", 
                render:function(data,type,row) {
                    if(data.InvNo!= null)
                        return "<a href=\"single_inv_pdf?inv_no="+data.InvNo+  "&term_id="+data.TermID+"\" target=\"_blank\">"+data.InvNo+"</a>";
                    else
                        return null
                    } 
            },
            { data: "invidx.Reference", width: "15%" },
            { data: "invidx.Description", width: "20%" },
            { data: "invidx.Amount", width: "15%",render: $.fn.dataTable.render.number( ',', '.', 2, '$' ) },
            { data: "invidx.TermID"},
            { data: null, render: function(data,type,row) {
                return data.terms.Year + ' Term ' + data.terms.Term;
            }}
        ],
        order: [[ 5, "desc" ], [6, "desc"]],
        "drawCallback": function( settings ) {
            var api = this.api();
            var rows = api.rows( {page:'current'} ).nodes();
            var last=null;
            api.column(6).cache('order').each( function ( group, i ) {
                if ( last !== group ) {
                    $(rows).eq( i ).before(
                        '<tr class="group"><td colspan="5">'+group+'</td></tr>'
                    );
 
                    last = group;
                }
            } );
        }
    } );

});
    </script>
</body>

</html>
<?php } ?>
