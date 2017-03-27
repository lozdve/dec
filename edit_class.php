<?php
include("include/session.php");
global $database;
$class_id = $_GET['class_id'];
$class_info = $database->getClassDetail($class_id);
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
            <li>eidt class</li>
        </ul>
        
        <div class="pageheader">
            <div class="pageicon"><i class="fa fa-laptop" aria-hidden="true"></i></div>
            <div class="pagetitle">
                <h1>Edit Class</h1>
            </div>
        </div><!--pageheader-->
        
        <div class="maincontent">
            <div class="maincontentinner">
                <div class="row-fluid">
                    <div id="dashboard-left" class="span12">
                        <div class="widget">
                            <h4 class="widgettitle">Attendance</h4>
                            <div class="widgetcontent">
                                <form id="editfam" data-class-id='<?php echo $class_id; ?>'>
                                    <div class="col-full editfam-div">
                                        <div class="col-term-select">TERM:
                                            <select class="attendselect">
                                                <?php 
                                                    $cls_term = $database->getTermByClass($class_id);
                                                    // var_dump($cls_term);
                                                    // die();
                                                    for($i=0;$i<sizeof($cls_term);$i++) {
                                                        echo "<option data-term-id=\"".$cls_term[$i][5]."\" data-studio=\"".$cls_term[$i][6]."\" value=\"".$cls_term[$i][4]."\"" .(($cls_term[$i]['TermID']==$curr_term)?'selected=true':''). ">".$cls_term[$i][0]. " Term " .$cls_term[$i][1]. " on " .$cls_term[$i][2]. " " .$cls_term[$i][3]. "</option>";           
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="col-term-studio"></div>
                                    </div>
                                    <div id="classattend" class="">
                                        <table id="dyntable-classattend" class="display">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Student</th>
                                                    <th>1</th>
                                                    <th>2</th>
                                                    <th>3</th>
                                                    <th>4</th>
                                                    <th>5</th>
                                                    <th>6</th>
                                                    <th>7</th>
                                                    <th>8</th>
                                                    <th>9</th>
                                                    <th>10</th>
                                                    <th>11</th>
                                                    <th>Exam</th>
                                                    <th>Grade</th>
                                                    <th>Pay Amount</th>
                                                    <th>Payment Default</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </form>
                            </div>

                            <h4 class="widgettitle"><?php echo $class_info[0]['Class'] ?></h4>
                            <div class="widgetcontent">
                                <form id="editfam" data-class-id='<?php echo $class_id; ?>'>
                                    <div class="col-2 editfam-div">
                                        <label data-class-id='<?php echo $class_id; ?>'>
                                            Code
                                            <input class="ajaxclass" name="class-code" tabindex="1" value='<?php echo $class_info[0]['Code']; ?>'>
                                        </label>
                                    </div>
                                    <div class="col-2 editfam-div">
                                        <label>
                                            Class
                                            <input class="ajaxclass"  name="class-name" tabindex="2" value='<?php echo $class_info[0]['Class']; ?>'>
                                        </label>
                                    </div>
                                    <div class="col-3 editfam-div">
                                        <label>
                                            $Session
                                            <input class="ajaxclass"  name="class-session" tabindex="3" value='$<?php echo $class_info[0]['Session']; ?>'>
                                        </label>
                                    </div>
                                    <div class="col-3 editfam-div">
                                        <label>
                                            $Term
                                            <input class="ajaxclass"  name="class-term" tabindex="4" value='$<?php echo $class_info[0]['Term']; ?>'>
                                        </label>
                                    </div>
                                    <div class="col-3 editfam-div">
                                        <label>
                                            $Exam
                                            <input class="ajaxclass"  name="class-exam" tabindex="5" value='$<?php echo $class_info[0]['Exam']; ?>'>
                                        </label>
                                    </div>
                                    <div class="col-3 editfam-div">
                                        <label>
                                            $Exam Assessment
                                            <input class="ajaxclass"  name="class-examass" tabindex="6" value='<?php echo $class_info[0]['ExamAss']; ?>'>
                                        </label>
                                    </div>
                                    <div class="col-3 editfam-div">
                                        <label>
                                            Category
                                            <select class="ajaxselectclass" name="class-cat">
                                                <?php 
                                                $clscat = $database->getClassCat();
                                                for($i=0; $i<sizeof($clscat); $i++) {
                                                    
                                                    if($class_info[0]['Cat'] == $clscat[$i][0]){
                                                        echo "<option value=\"".$clscat[$i][0]."\" selected>".$clscat[$i][1]."</option>";
                                                    } else {
                                                        echo "<option value=\"".$clscat[$i][0]."\">".$clscat[$i][1]."</option>";
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </label>
                                    </div>
                                    <div class="col-3 editfam-div last-row">
                                        <label>
                                            Next Grade
                                            <select class="ajaxselectclass" name="class-grade">
                                                <?php 
                                                $allcls = $database->getAllClass();
                                                for($i=0; $i<sizeof($allcls); $i++) {
                                                    if($allcls[$i][0]==$class_info[0][7])
                                                    echo "<option value=\"".$allcls[$i][0]."\" selected>".$allcls[$i][2]."</option>";
                                                    else 
                                                        echo "<option value=\"".$allcls[$i][0]."\">".$allcls[$i][2]."</option>";   
                                                }
                                                ?>
                                            </select>
                                        </label>
                                    </div>
                                </form>
                                <!-- tree table for job display -->
                            </div><!--widgetcontent-->

                            <h4 class="widgettitle">Class Times</h4>
                            <div class="widgetcontent">
                                <ul class="nav nav-pills">
                                    <li class="active"><a data-toggle="pill" href="#classtimes">Class Times</a></li>
                                </ul>

                                <div class="tab-content">
                                    <div id="classtimes" class="tab-pane fade in active">
                                        <table id="dyntable-clstimes" class="display">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Term</th>
                                                    <th>Studio</th>
                                                    <th>Day</th>
                                                    <th>Time</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>

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
    var attend_editor;
    var select_term_id;
    jQuery(document).ready(function($){
        editor = new $.fn.dataTable.Editor( {
            ajax: {
                "url": "include/csmanage.php",
                "type": "post",
                "data": {
                    "cls_id": <?php echo $class_id; ?>
                }
            },
            table: "#dyntable-clstimes",
            fields: [{
                    label: "Term: ",
                    name: "times.TermID",
                    type: "select"
                }, {
                    label: "Studio: ",
                    name: "times.StudioID",
                    "attr": {
                        "class": "input-small"
                    },
                    type: 'select',
                    options: [
                        { label: "Studio 1", value:"4"},
                        { label: "Studio 2", value:"5"},
                        { label: "Studio 3", value:"6"},
                        { label: "Studio 4", value:"7"},
                    ] 
                }, {
                    label: "Day: ",
                    name: "times.Day",
                    "attr": {
                        "class": "input-small"
                    },
                    type: 'select',
                    options: [
                        { label: "Monday", value:"Monday"},
                        { label: "Tuesday", value:"Tuesday"},
                        { label: "Wednesday", value:"Wednesday"},
                        { label: "Thursday", value:"Thursday"},
                        { label: "Friday", value:"Friday"},
                        { label: "Saturday", value:"Saturday"},
                        { label: "Sunday", value:"Sunday"}
                    ] 
                }, {
                    label: "Time:", 
                    name: "times.Time",
                    type: "datetime",
                    format: "HH:mm"
                }, {
                    type: "hidden",
                    name: "times.ClassID",
                    def: <?php echo $class_id; ?>
            }
            ]
        });

        jQuery('#dyntable-clstimes').DataTable( {
            dom: "Bfrtip",
            ajax: {
                "url": "include/csmanage.php",
                "type": "post",
                "data": {
                    "cls_id": <?php echo $class_id; ?>
                }
            },
            "searching": false,
            "paging": false,
            "info": false,
            "scroller": true,
            "scrollCollapse": true,
            "scrollY": '50vh',
            "order": [[1, 'DESC']],
            columns: [
                {
                    data: null,
                    defaultContent: '',
                    className: 'select-checkbox',
                    width: "5%" 
                },
                { data: "terms", width:"15%", editField: "times.TermID", render: function(data,type,row) {
                    if(data.Year==null || data.Term==null)
                        return null;
                    else
                        return data.Year + ' Term ' + data.Term;
                }},
                { data: "times.StudioID", width: "15%", className: "ttest",
                        render: function(data,type,row) {
                            var result;
                            switch(data) {
                                case '4':
                                    return "Studio 1";
                                case '5':
                                    return "Studio 2";
                                case '6':
                                    return "Studio 3";
                                case '7':
                                    return "Studio 4";
                                default:
                                    return "No Studio";
                            }
                        }
                },
                { data: "times.Day", width: "15%" },
                { data: "times.Time", width: "20%" }
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
        $('#dyntable-clstimes').on( 'click', 'tbody td:not(:first-child)', function (e) {
            editor.inline( this, {
                onBlur: "submit"
            } );
        } );


        attend_create_editor = new $.fn.dataTable.Editor( {
            ajax: {
                "url": "include/csmanage.php",
                "type": "post",
                "data": {
                    "cls_id": <?php echo $class_id; ?>,
                    "time_id": $('.attendselect').val(),
                    "term_id": $('.attendselect').find(':selected').attr('data-term-id')
                }
            },
            table: "#dyntable-classattend",
            fields: [{
                    label: "Student: ",
                    name: "attendance.StudentID",
                    type: 'select'
                }, {
                    label: 'Payment Default',
                    name: 'attendance.PayDefault',
                    type: 'select',
                    options: [
                        { label: "T", value:"T"},
                        { label: "C", value:"C"}
                    ] 
                }, {
                    label: 'Payment Amount',
                    name: 'attendance.PayAmount'                    
                }, {
                    name:'attendance.TimeID',
                    type: 'hidden',
                    def: $('.attendselect').val()
                }, {
                    name:'attendance.TermID',
                    type: 'hidden',
                    def: $('.attendselect').find(':selected').attr('data-term-id')
                }, {
                    name:'attendance.ClassID',
                    type: 'hidden',
                    def: <?php echo $class_id; ?>
                }
            ]
        });
        attend_create_editor.dependent('attendance.PayDefault', function(val) {
            if(val == 'T') {
                attend_create_editor.field('attendance.PayAmount').val(<?php echo $class_info[0][4]; ?>);
            }
            else if(val =='C') {
                attend_create_editor.field('attendance.PayAmount').val(<?php echo $class_info[0][3]; ?>);
            }
        })

        attend_editor = new $.fn.dataTable.Editor( {

            ajax: {
                "url": "include/csmanage.php",
                "type": "post",
                "data": {
                    "cls_id": <?php echo $class_id; ?>,
                    "time_id": $('.attendselect').val(),
                    "term_id": $('.attendselect').find(':selected').attr('data-term-id')
                }
            },
            table: "#dyntable-classattend",
            fields: [{
                    label: "Student: ",
                    name: "attendance.StudentID"
                }, {
                    name:'attendance.TimeID',
                    type: 'hidden',
                    def: $('.attendselect').val()
                }, {
                    name:'attendance.Week01',
                }, {
                    name:'attendance.Week02',
                }, {
                    name:'attendance.Week03',
                }, {
                    name:'attendance.Week04',
                }, {
                    name:'attendance.Week05',
                }, {
                    name:'attendance.Week06',
                }, {
                    name:'attendance.Week07',
                }, {
                    name:'attendance.Week08',
                }, {
                    name:'attendance.Week09',
                }, {
                    name:'attendance.Week10',
                }, {
                    name:'attendance.Week11',
                }, {
                    name:'attendance.Exam',
                }, {
                    name:'attendance.Grade',
                }, {
                    name:'attendance.PayAmount',
                }, {
                    name:'attendance.PayDefault',
                }, {
                    name:'attendance.TermID',
                    type: 'hidden',
                    def: $('.attendselect').find(':selected').attr('data-term-id')
                }, {
                    name:'attendance.ClassID',
                    type: 'hidden',
                    def: <?php echo $class_id; ?>
                }
            ],
            formOptions: {
                inline: {
                    onBlur: 'submit'
                }
            }
        });

        attend_editor.dependent('attendance.PayDefault', function(val) {
            // alert(attend_editor.field( 'attendance.StudentID' ).val());
            // alert(<?php echo $class_info[0][4] ?>);
            if(val == 'T')
                attend_editor.field('attendance.PayAmount').val(<?php echo $class_info[0][4]; ?>);
            else
                attend_editor.field('attendance.PayAmount').val(<?php echo $class_info[0][3]; ?>);
        })

        var attend_table = jQuery('#dyntable-classattend').DataTable( {
            dom: "Bfrtip",
            ajax: {
                "url": "include/csmanage.php",
                "type": "post",
                "data": function (d){
                    d.cls_id = '<?php echo $class_id; ?>',
                    d.time_id = $('.attendselect').val();
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
                    width: "1%" 
                },
                { 
                    data:"people", width: "10%", editField:"attendance.StudentID", render: function(data,type,row) {
                        return data.First + ' ' + data.Last;
                    }
                },
                { data: "attendance.Week01", width:"2%", orderable: false },
                { data: "attendance.Week02", width:"2%", orderable: false },
                { data: "attendance.Week03", width:"2%", orderable: false },
                { data: "attendance.Week04", width:"2%", orderable: false },
                { data: "attendance.Week05", width:"2%", orderable: false },
                { data: "attendance.Week06", width:"2%", orderable: false },
                { data: "attendance.Week07", width:"2%", orderable: false },
                { data: "attendance.Week08", width:"2%", orderable: false },
                { data: "attendance.Week09", width:"2%", orderable: false },
                { data: "attendance.Week10", width:"2%", orderable: false },
                { data: "attendance.Week11", width:"2%", orderable: false },
                { data: "attendance.Exam", width: "2%", orderable: false },
                { data: "attendance.Grade", width: "2%", orderable: false },
                { data: "attendance.PayAmount", width: "2%", orderable: false },
                { data: "attendance.PayDefault", width: "2%", orderable: false }
            ],
            select: {
                style:    'os',
                selector: 'td:first-child'
            },
            buttons: [
            { extend: "create", editor: attend_create_editor },
            { extend: "remove", editor: attend_editor }
            ],
            keys: {
                columns: ':not(:first-child)',
                keys: [ 9 ]
            }
        } );

        attend_table.on('key-focus', function( e, datatable,cell) {
            attend_editor.inline(cell.index() );
        });

        $('#dyntable-classattend').on( 'click', 'tbody td:not(:first-child)', function (e) {
            attend_editor.inline( this, {
                onBlur: "submit"
            } );
        } );

        $('.attendselect').change(function() {
            var str = "";
            attend_table.ajax.reload();
            $( ".attendselect option:selected" ).each(function() {
                str += $( ".attendselect option:selected" ).data('studio') + " ";
            });
            // console.log($('.attendselect').find(':selected').attr('data-term-id'));
            $('.col-term-studio').text(str);
        }).change();
    })
</script>
</body>
</html>
<?php } ?>