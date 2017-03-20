<?php
function active($link) {
	$url_array =  explode('/', $_SERVER['REQUEST_URI']) ;
  	$url = end($url_array);  
  	if($link == $url){
     	echo 'active'; //class name in css 
  	}
}
?>

<div class="leftpanel">
    
    <div class="leftmenu">        
        <ul class="nav nav-tabs nav-stacked">
        	<li class="nav-header">Navigation</li>
            <li class="<?php active('main');?>"><a href="main"><i class="fa fa-tachometer" aria-hidden="true"></i> Dashboard</a></li>
            <li class="<?php active('students');?>"><a href="students"><i class="fa fa-bars" aria-hidden="true"></i> Students</a></li>   
            <li class="sub-menu-a <?php active('newfamily');?>"><a href="newfamily"><span class="sub-menu"></span> - New Family</a></li>
            <li class="sub-menu-a <?php active('saved_invoices');?>"><a href="saved_invoices"><span class="sub-menu"></span> - Saved Invoices</a></li>
            <li class="sub-menu-a <?php active('receive_payment');?>"><a href="receive_payment"><span class="sub-menu"></span> - Receive Payment</a></li>
            <li class="sub-menu-a <?php active('banking');?>"><a href="banking"><span class="sub-menu"></span> - Banking</a></li>
            <li class="sub-menu-a <?php active('receive_journal');?>"><a href="receive_journal"><span class="sub-menu"></span> - Journal</a></li>
            <li class="<?php active('class');?>"><a href="class"><i class="fa fa-bars" aria-hidden="true"></i> Classes</a></li>
            <li class="sub-menu-a <?php active('newclass');?>"><a href="newclass"><span class="sub-menu"></span> - New Class</a></li>
            <li class="sub-menu-a <?php active('page1.php');?>"><a href="#"><span class="sub-menu"></span> - Studios</a></li>
            <li class="sub-menu-a <?php active('page1.php');?>"><a href="#"><span class="sub-menu"></span> - Copy classes to New Term</a></li>
            <li class="<?php active('reports');?>"><a href="reports"><i class="fa fa-bars" aria-hidden="true"></i> Reports</a></li>
        </ul>
    </div><!--leftmenu-->
    
</div><!-- leftpanel -->
