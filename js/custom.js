jQuery.noConflict();

jQuery(document).ready(function($){
	
	// dropdown in leftmenu
	jQuery('.leftmenu .dropdown > a').click(function(){
		if(!jQuery(this).next().is(':visible'))
			jQuery(this).next().slideDown('fast');
		else
			jQuery(this).next().slideUp('fast');	
		return false;
	});
	
	if(jQuery.uniform) 
	   jQuery('input:checkbox, input:radio, .uniform-file').uniform();
		
	if(jQuery('.widgettitle .close').length > 0) {
		  jQuery('.widgettitle .close').click(function(){
					 jQuery(this).parents('.widgetbox').fadeOut(function(){
								jQuery(this).remove();
					 });
		  });
	}
	
	
   // add menu bar for phones and tablet
   jQuery('<div class="topbar"><a class="barmenu">'+
		    '</a></div>').insertBefore('.mainwrapper');
	
	jQuery('.topbar .barmenu').click(function() {
		  
		  var lwidth = '260px';
		  if(jQuery(window).width() < 340) {
					 lwidth = '240px';
		  }
		  
		  if(!jQuery(this).hasClass('open')) {
					 jQuery('.rightpanel, .headerinner, .topbar').css({marginLeft: lwidth},'fast');
					 jQuery('.logo, .leftpanel').css({marginLeft: 0},'fast');
					 jQuery(this).addClass('open');
		  } else {
					 jQuery('.rightpanel, .headerinner, .topbar').css({marginLeft: 0},'fast');
					 jQuery('.logo, .leftpanel').css({marginLeft: '-'+lwidth},'fast');
					 jQuery(this).removeClass('open');
		  }
	});
	
	// show/hide left menu
	jQuery(window).resize(function(){
		  if(!jQuery('.topbar').is(':visible')) {
		         jQuery('.rightpanel, .headerinner').css({marginLeft: '260px'});
					jQuery('.logo, .leftpanel').css({marginLeft: 0});
		  } else {
		         jQuery('.rightpanel, .headerinner').css({marginLeft: 0});
					jQuery('.logo, .leftpanel').css({marginLeft: '-260px'});
		  }
   });
	
	// dropdown menu for profile image
	jQuery('.userloggedinfo img').click(function(){
		  if(jQuery(window).width() < 480) {
					 var dm = jQuery('.userloggedinfo .userinfo');
					 if(dm.is(':visible')) {
								dm.hide();
					 } else {
								dm.show();
					 }
		  }
   });
	
	// change skin color
	jQuery('.skin-color a').click(function(){ return false; });
	jQuery('.skin-color a').hover(function(){
		var s = jQuery(this).attr('href');
		if(jQuery('#skinstyle').length > 0) {
			if(s!='default') {
				jQuery('#skinstyle').attr('href','css/style.'+s+'.css');	
				jQuery.cookie('skin-color', s, { path: '/' });
			} else {
				jQuery('#skinstyle').remove();
				jQuery.cookie("skin-color", '', { path: '/' });
			}
		} else {
			if(s!='default') {
				jQuery('head').append('<link id="skinstyle" rel="stylesheet" href="css/style.'+s+'.css" type="text/css" />');
				jQuery.cookie("skin-color", s, { path: '/' });
			}
		}
		return false;
	});
	
	// load selected skin color from cookie
	if(jQuery.cookie('skin-color')) {
		var c = jQuery.cookie('skin-color');
		if(c) {
			jQuery('head').append('<link id="skinstyle" rel="stylesheet" href="css/style.'+c+'.css" type="text/css" />');
			jQuery.cookie("skin-color", c, { path: '/' });
		}
	}
	
	/*** report page ***/
	$('.journal_sum').click(function () {
		var f = $('#from_j_sum').val();
		var t = $('#to_j_sum').val();
		window.open('journal_sum_pdf.php?from='+f+'&to='+t);
	});

	$('.journal_detail').click(function () {
		var f = $('#from_j_de').val();
		var t = $('#to_j_de').val();
		window.open('journal_detail_pdf.php?from='+f+'&to='+t);
	});

	$('.debtors_report').click(function () {
		var f = $('#debtors').val();
		window.open('debtors_pdf.php?&to='+f);
	});

	$('.attendance_sheets').click(function () {
		var f = $('select[name=attend-sheet]').val();
	});

	// expand/collapse boxes
	if(jQuery('.minimize').length > 0) {
		  
		  jQuery('.minimize').click(function(){
					 if(!jQuery(this).hasClass('collapsed')) {
								jQuery(this).addClass('collapsed');
								jQuery(this).html("&#43;");
								jQuery(this).parents('.widgetbox')
										      .css({marginBottom: '20px'})
												.find('.widgetcontent')
												.hide();
					 } else {
								jQuery(this).removeClass('collapsed');
								jQuery(this).html("&#8211;");
								jQuery(this).parents('.widgetbox')
										      .css({marginBottom: '0'})
												.find('.widgetcontent')
												.show();
					 }
					 return false;
		  });
			  
	}
	
	//edit family info ajax
	$('.ajaxfam').focusout(function(e) {
		e.preventDefault();
		var value = $(this).val();
		var fid = $('#editfam').data('fam-id');
		var nameid = $(this).attr('name');
		if(nameid == 'fam-code') {
			$.ajax({
				type: 'POST',
				url: 'ajaxform.php',
				data: {fam_id:fid, key:'code', fam_value:value},
				success: function(output) {
				}
			});
		}
		if(nameid == 'fam-last') {
			$.ajax({
				type: 'POST',
				url: 'ajaxform.php',
				data: {fam_id:fid, key:'last', fam_value:value},
				success: function(output) {
				}
			});
		}
		if(nameid == 'fam-phone') {
			$.ajax({
				type: 'POST',
				url: 'ajaxform.php',
				data: {fam_id:fid, key:'phone', fam_value:value},
				success: function(output) {
				}
			});
		}
		if(nameid == 'fam-phy1') {
			$.ajax({
				type: 'POST',
				url: 'ajaxform.php',
				data: {fam_id:fid, key:'phy1', fam_value:value},
				success: function(output) {
				}
			});
		}
		if(nameid == 'fam-phy2') {
			$.ajax({
				type: 'POST',
				url: 'ajaxform.php',
				data: {fam_id:fid, key:'phy2', fam_value:value},
				success: function(output) {
				}
			});
		}
		if(nameid == 'fam-phy3') {
			$.ajax({
				type: 'POST',
				url: 'ajaxform.php',
				data: {fam_id:fid, key:'phy3', fam_value:value},
				success: function(output) {
				}
			});
		}
		if(nameid == 'fam-post1') {
			$.ajax({
				type: 'POST',
				url: 'ajaxform.php',
				data: {fam_id:fid, key:'post1', fam_value:value},
				success: function(output) {
				}
			});
		}
		if(nameid == 'fam-post2') {
			$.ajax({
				type: 'POST',
				url: 'ajaxform.php',
				data: {fam_id:fid, key:'post2', fam_value:value},
				success: function(output) {
				}
			});
		}
		if(nameid == 'fam-post3') {
			$.ajax({
				type: 'POST',
				url: 'ajaxform.php',
				data: {fam_id:fid, key:'post3', fam_value:value},
				success: function(output) {
				}
			});
		}
	});

	/*** edit class ajax handler ***/
	$('.ajaxclass').focusout(function(e) {
		e.preventDefault();
		var value = $(this).val();
		var cid = $('#editfam').data('class-id');
		var nameid = $(this).attr('name');
		if(nameid == 'class-code') {
			$.ajax({
				type: 'POST',
				url: 'ajaxform.php',
				data: {class_id:cid, key:'code', cls_value:value},
				success: function(output) {
				}
			});
		}
		if(nameid == 'class-name') {
			$.ajax({
				type: 'POST',
				url: 'ajaxform.php',
				data: {class_id:cid, key:'name', cls_value:value},
				success: function(output) {
				}
			});
		}
		if(nameid == 'class-session') {
			value = value.substring(1);
			$.ajax({
				type: 'POST',
				url: 'ajaxform.php',
				data: {class_id:cid, key:'session', cls_value:value},
				success: function(output) {
				}
			});
		}
		if(nameid == 'class-term') {
			value = value.substring(1);
			$.ajax({
				type: 'POST',
				url: 'ajaxform.php',
				data: {class_id:cid, key:'term', cls_value:value},
				success: function(output) {
				}
			});
		}
		if(nameid == 'class-exam') {
			value = value.substring(1);
			$.ajax({
				type: 'POST',
				url: 'ajaxform.php',
				data: {class_id:cid, key:'exam', cls_value:value},
				success: function(output) {
				}
			});
		}
		if(nameid == 'class-examass') {
			value = value.substring(1);
			$.ajax({
				type: 'POST',
				url: 'ajaxform.php',
				data: {class_id:cid, key:'examass', cls_value:value},
				success: function(output) {
				}
			});
		}

	})

	$('.ajaxselectclass').change(function(e) {
		e.preventDefault();
		var value = $(this).val();
		var cid = $('#editfam').data('class-id');
		var nameid = $(this).attr('name');
		if(nameid == 'class-cat') {
			$.ajax({
				type: 'POST',
				url: 'ajaxform.php',
				data: {class_id:cid, key:'classcat', cls_value:value},
				success: function(output) {
				}
			});
		}
		if(nameid == 'class-grade') {
			$.ajax({
				type: 'POST',
				url: 'ajaxform.php',
				data: {class_id:cid, key:'classgrade', cls_value:value},
				success: function(output) {
				}
			});
		}
	})

	$('#select-fam').selectize({
	    create: true,
	    sortField: 'text'
	});

	$('.collapsetr').click(function() {
		var subid = $(this).data('subhead-id');
		var tmp = "collapse" + subid;
		$('.'+tmp).fadeToggle( "slow" );
	})

	var offset = 300,
		//browser window scroll (in pixels) after which the "back to top" link opacity is reduced
		offset_opacity = 1200,
		//duration of the top scrolling animation (in ms)
		scroll_top_duration = 700,
		//grab the "back to top" link
		$back_to_top = $('.cd-top');

	//hide or show the "back to top" link
	$(window).scroll(function(){
		( $(this).scrollTop() > offset ) ? $back_to_top.addClass('cd-is-visible') : $back_to_top.removeClass('cd-is-visible cd-fade-out');
		if( $(this).scrollTop() > offset_opacity ) { 
			$back_to_top.addClass('cd-fade-out');
		}
	});

	//smooth scroll to top
	$back_to_top.on('click', function(event){
		event.preventDefault();
		$('body,html').animate({
			scrollTop: 0 ,
		 	}, scroll_top_duration
		);
	});
	
});