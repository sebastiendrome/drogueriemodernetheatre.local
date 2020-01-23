/**
 * 1. Behaviors (assigned to html elements on event)
 * 2. functions
 */
/***** manage site structure behaviors *****************************************************/


// change input NAME
$('#structureContainer, #contentContainer').on('change', 'input.nameInput', function() {
	var newName = $(this).val();
	var oldName = $(this).closest('li').attr("data-name"); // use attr and not data! because its val may have been dynamically changed
	if(!newName.length){
		alert('name cannot be empty!');
		$(this).val(oldName);
		return false;
	}else if(newName == oldName){
		return false;
	}
	var url = window.location.href;
	//alert(url);
	if( url.match(/manage_contents/) ){
		var adminPage = 'manage_contents';
	}else if( url.match(/manage_structure/) ){
		var adminPage = 'manage_structure';
	}
	var parents = $(this).closest('ul').data("parents"); // get parents name in case this is a sub-section
	// add underscore to newName if necessary
	if(oldName.substr(0, 1) == '_'){
		newName = '_'+newName;
	}
	updateName(oldName, newName, parents, adminPage);
});


// change input POSITION 
$('#structureContainer, #contentContainer').on('change', 'input.position', function() {
	var url = window.location.href;
	//alert(url);
	if( url.match(/manage_contents/) ){
		var adminPage = 'manage_contents';
	}else if( url.match(/manage_structure/) ){
		var adminPage = 'manage_structure';
	}
	//alert(adminPage);
	var parents = $(this).closest('ul').attr("data-parents");
	var item = $(this).closest('li').attr("data-name");
	var oldPosition = $(this).closest('li').data("oldposition");
	//alert(parents+' > '+item+' -> '+oldPosition);
	var newPosition = $(this).val();
	updatePosition(item, oldPosition, newPosition, parents, adminPage);
});


// change POSITION move up or down
$('#structureContainer, #contentContainer').on('click', 'a.up, a.down', function(e) {
	var url = window.location.href;
	//alert(url);
	if( url.match(/manage_contents/) ){
		var adminPage = 'manage_contents';
	}else if( url.match(/manage_structure/) ){
		var adminPage = 'manage_structure';
	}
	//alert(adminPage);
	var parents = $(this).closest('ul').attr("data-parents");
	var item = $(this).closest('li').attr("data-name"); // use attr not data, because its value may have been changed dynamically
	var oldPosition = $(this).closest('li').data("oldposition");
	//alert(parents+' -> '+item+' -> '+oldPosition);
	
	if($(this).hasClass("up")){
		var newPosition = oldPosition-1;
	}else{
		var newPosition = oldPosition+1;
	}
	updatePosition(item, oldPosition, newPosition, parents, adminPage);
	
	e.preventDefault();
});


// SHOW or HIDE section
$('#adminContainer').on('click', 'a.show, a.hide', function(e) {
	//alert('clicked');
	var item = $(this).closest('li').attr("data-name"); // use attr instead of data! because its value may have been changed dynamically
	var parents = $(this).closest('ul').attr("data-parents"); // get parents name in case this is a sub-section
	//alert(parents+' > '+item);
	showHide(item, parents);
	e.preventDefault();
});


// PUBLISH section
$('div#contentContainer').on('click', 'a#publish', function(e) {
	var item = $(this).attr('data-item'); // use attr instead of data! because its value may have been changed dynamically
	//alert(item);
	var path = decodeURIComponent(item);
	if(path.indexOf('/') !== -1){
		var oldName = basename(path);
		var newName = oldName.substr(1, oldName.length);
		var parents = path.replace("/"+oldName, '');
	}else{
		var oldName = path;
		var newName = oldName.substr(1, oldName.length);
		var parents = '';
	}
	var adminPage = 'manage_contents';
	alert(oldName+' '+newName+' '+parents);
	updateName(oldName, newName, parents, adminPage);
	e.preventDefault();
});



// DELETE section
$('#structureContainer, #contentContainer').on('click', 'a.deleteSection', function(e){
	var item = $(this).closest('li').attr("data-name"); // use attr instead of data! because its value may have been changed dynamically
	var parents = $(this).closest('ul').attr("data-parents"); // get parents name in case this is a sub-section
	//alert(parents+' > '+item);
	showModal('deleteSection?deleteSection='+encodeURIComponent(item)+'&parents='+encodeURIComponent(parents));
	e.preventDefault();
});

// ROTATE image
$('body').on('click', '.imgContainer a.rotate, .galImgRow a.rotate', function(e) {
	e.preventDefault();
	// get the image container ($elm)
	var $elm = $(this).closest('div').find('a.aImg');
	// update the data-rotate value
	var rotate = $(this).attr('data-rotate');
	var newRot = parseInt(rotate)+90;
	if(newRot < 271){
		newRot = parseInt(rotate)+90;
	}else{
		newRot = 0;
	}
	$(this).attr("data-rotate", newRot);
	// back to normal rotation, hide save and cancel buttons
	if(newRot == 90){
		$(this).closest('div').find('a.save').hide();
		$(this).closest('div').find('a.cancel').hide();
	// else, show save and cancel buttons
	}else{
		$(this).closest('div').find('a.save').show().css('display','inline-block');
		$(this).closest('div').find('a.cancel').show().css('display','inline-block');
	}
	// call js function to rotate image in html DOM
	rotateImage($elm, rotate);
});

// save image rotation on click a.save
$('body').on('click', '.imgContainer a.save, .galImgRow a.save', function(e) {
	e.preventDefault();
	// find image container ($elm)
	var $elm = $(this).closest('div').find('a.aImg');
	var $rotBut = $(this).closest('div').find('a.rotate');
	var $cancel = $(this).closest('div').find('a.cancel');
	var img_url = $elm.attr('data-bgimg');
	
	// hide this and cancel buttons
	$(this).hide();
	$cancel.hide();
	
	// get rotation value
	var rotate = parseInt($rotBut.attr('data-rotate')-90);
	if(rotate == -90){
		rotate = 270;
	}
	//alert(rotate);
	// convert rotate to orientation: orientation will be used within php fix_image_orientation() function
	if(rotate == 90){
		orientation = 8;
	}else if(rotate == 180){
		orientation = 3;
	}else if(rotate == 270){
		orientation = 6;
	}
	// call php via ajax js function
	rotate_image($elm, img_url, orientation);
});

// cancel rotation on click a.cancel
$('body').on('click', '.imgContainer a.cancel, .galImgRow a.cancel', function(e) {
	e.preventDefault();
	var $rotBut = $(this).closest('div').find('a.rotate');
	var $elm = $(this).closest('div').find('a.aImg');
	var $save = $(this).closest('div').find('a.save');
	// reset all: data-rotate value, img Container class (i.e. "rot90"), hide this (cancel) & save button
	$rotBut.attr('data-rotate', '90');
	$elm.removeAttr('class').addClass('aImg');
	$(this).hide();
	$save.hide();
});



/***** manage site content behaviors *****************************************************/

// save text DESCRIPTION
$('#contentContainer').on('click', 'a.saveText', function() {
	var file = $(this).parent('div.actions').find('input.file').val();
	var enText = $(this).parent('div.actions').find('textarea.en').val();
	//alert(file+' entxt:'+enText);
	var deText = $(this).parent('div.actions').find('textarea.de').val();
	saveTextDescription(file, enText, deText);
});

// show / hide TIPS
$('body').on('click', 'div.tip a.tipTitle', function(e){
	var olDisplay = $(this).closest('div.tip').children('ol').css('display');
	//alert(olDisplay);
	if(olDisplay == 'none'){
		$(this).addClass("open");
		$(this).closest('div.tip').children('ol').css('display', 'block');
	}else{
		$(this).removeClass("open");
		$(this).closest('div.tip').children('ol').css('display', 'none');
	}
	
	e.preventDefault();
});

// select text input
$('body').on('click', 'input.position', function(){
	$(this).select();
});

// hide all modalContainer(s) and overlay
$('body').on('click', 'div.overlay', function(){
	$(this).fadeOut();
	$('div.modalContainer').hide();
	return false;
});

// assign behavior to .showModal
$('body').on('click', '.showModal', function(e){
	var modal = $(this).attr("rel");
	var nextpage = $(this).attr("href");
	if(nextpage !== 'javascript:;' && nextpage !== '#'){
		if(modal.indexOf('?') !== -1){
			modal = modal+'&redirect='+encodeURIComponent(nextpage);
		}else{
			modal = modal+'?redirect='+encodeURIComponent(nextpage);
		}
	}
	showModal(modal);
	e.preventDefault();
});

// assign behavior to .closeBut et .hideModal (close parent div on click)
$('body').on('click', '.closeBut, .hideModal', function(e){
    hideModal($(this));
    e.preventDefault();
});

// assign behavior to .closeMessage (close parent on click)
$('body').on('click', '.closeMessage', function(e){
	var parent = $(this).parent();
	parent.hide();
	//window.location.search = '';
    e.preventDefault();
});

// display 'working' div while processing ajax requests
$(document).ajaxStart(function(){
	$('#working').show();
}).ajaxStop(function(){
	$('#working').hide();
});

// if the value of textarea (for description texts) is changed, highlight "save changes" button
$('#adminContainer').on('input propertychange', 'textarea.en, textarea.de', function(){
	//alert('change');
	// check which one (en or de) was changed
	if($(this).hasClass("en")){
		var oldValue = $(this).parent().find('input.enMemory').val();
	}else if($(this).hasClass("de")){
		var oldValue = $(this).parent().find('input.deMemory').val();
	}
	// compare old and new value
	if($(this).val() != oldValue){ // it has changed
		$(this).parent().find('a.button.saveText').removeClass("disabled");
		if ($("#localMessage").length){
			$("#localMessage").remove();
		}
	}else{
		$(this).parent().find('a.button.saveText').addClass("disabled");
	}
});

// trigger click on edit button when clicking on file preview (for txt, html, gal and embed files)
$('#ajaxTarget').on('click', "div.txt.admin, div.html.admin", function(){
	var editBut = $(this).parent().find('a.button.edit')[0];
	if(editBut){
		editBut.click();
	}
});

// show allowed tags tip
$('.tags').on("mouseenter", function(){
	$(this).children("span.tagTip").show();
}).on("mouseleave", function(){
	$(this).children("span.tagTip").hide();
});


// show tooltip below the span.question element on click (native title tooltip will show on moue hover)
// reposition it if it is below the bottom window edge
var ti;
$('body').on('mouseenter', 'span.tip', function(){
	var $this = $(this);
	ti = setTimeout(function(){
		showToolTip($this);
	}, 200);
}).on('mouseleave', 'span.tip', function(){
	clearTimeout(ti);
	$(this).children('span.tooltip').remove();
}).on('click', 'span.tip', function(){
	clearTimeout(ti);
	$(this).children('span.tooltip').remove();
});


// add .closeMessage to messages, so they can be closed (hidden)
$('<a class="closeMessage">&times;</a>').appendTo('p.error, p.note, p.success');


// Remove img from gallery
$('body').on('click', 'div.galImgRow .button.remove', function(e){
	e.preventDefault();
	var file = $(this).parent().data('file');
	var path = $(this).parent().data('gallery');
	var action = 'remove';
	//alert(file);
	saveGalleryChange(path, file, action);
});
//  Change img position in gallery
$('body').on('blur', 'div.galImgRow input.position', function(e){
	var oldPosition = $(this).attr('data-oldPosition');
	var pos = $(this).val();
	if(oldPosition !== pos && pos > 0){
		var file = $(this).parents('div.galImgRow').data('file');
		var path = $(this).parents('div.galImgRow').data('gallery');
		var action = 'position';
		saveGalleryChange(path, file, action, pos);
	}else if(oldPosition !== pos){
		alert('invalid position value!');
		e.preventDefault();
		$(this).val(oldPosition);
	}
});


// listen to admin nav height and adjust body top padding accordingly
$(window).resize(function(){
	var adminNavH = $('.adminHeader').outerHeight();
	$('body').css('padding-top', adminNavH+'px');
}).resize();

/* UPLOAD BEHAVIORS */

// #chooseFileLink onclick triggers #fileUpload click
$('body').on('click', '#chooseFileLink', function(){
	$('input#fileUpload').trigger('click');
	return false;
});

// #fileUpload click validates file size and extension, then triggers #uploadFileSubmit click
$("body").on("change", '#fileUpload', function(){
	var upVal = this.value;
	if(upVal != ''){

		var error = false;
		var file = this.files[0];
		var fileSize = file.size;
		//var fileType = file.type;
		var fileName = file.name;

		var $allowed_types = $(this).parents('form').find('input[name=allowed_types]');
		var allowed_types = $allowed_types.val();
		//alert(types[allowed_types]);
		
		// validate file extension
		var ext = fileName.split('.').pop().toLowerCase();
		var dotExt = '.'+ext;
		var extMatch = dotExt.match(types[allowed_types]);
		if(extMatch == null){
			error = true;
        	alert('Sorry, this file type is not supported: .'+ext+'\n\nThe file has not been uploaded.');
		}
		
		// validate file size
		if(fileSize > max_upload_bytes) {
			var readableSize = bytesToReadbale(fileSize);
			error = true;
        	alert('The file is too large: '+readableSize+'\n\nThe maximum upload size is '+max_upload_size);
		}
		
		if(!error){
			$('.hideUp').hide();
			$('#uploadFileSubmit').trigger('click');
		}
	}
});

// #uploadFileSubmit onchange sets #chooseFileLink innerHTML to #fileUpload value (fileName)
// AND initiates ajax call to upload via /~code/admin/admin_ajax.php -> upload_file()
$('body').on('click', '#uploadFileSubmit', function(e){
	e.preventDefault();
	var path = $('#fileUpload').val();
	//alert(path); return false;
	var fileName = basename(path);
	var myForm = document.forms.namedItem("uploadFileForm");
	var context = $('input[name="context"]').val();
	var uploadText = $('a#chooseFileLink').html();
	
	//alert(context);
	//return false;
	
	$('a#chooseFileLink').html('Uploading: '+fileName+'...').removeClass('submit');
	// show upload progress bar
	$('div.progress').css('display','block');
	
	
	$.ajax({
		// Your server script to process the upload
		url: '/'+demo+'~code/admin/admin_ajax.php',
		type: 'POST',

		// Form data
		data: new FormData(myForm),

		// Tell jQuery not to process data or worry about content-type
		// You *must* include these options!
		cache: false,
		contentType: false,
		processData: false,

		// on success, reload page with upload_result message
		success : function(msg) {
			var url = window.location.protocol+'//'+window.location.hostname+window.location.pathname;
			
			// for inserting uploaded image in edit_text.php, call insertImg function and hide Modal
			if(context == 'edit_text'){
				var error = msg.match(/^0\|/);
				if(error == null){
					insertImg(msg);
					hideModal($('#uploadFileInsertContainer'));
				}else{
					msg = msg.replace("0|", 'Error: ');
					$('#result').html('<p class="error">'+msg+'</p>');
				}
				$('button#uploadFileSubmit').css({'opacity':1,'cursor':'pointer'}); 
				$('div.progress').hide();

				return true;

			// for uploading file (both in manage_contents and preferences-bg-image), reload page with message
			}else if(context == 'gallery'){
				var error = msg.match(/^0\|/);
				if(error == null){
					$('a#chooseFileLink').html(uploadText).addClass('submit');
					$('div#adminGalContainer').html(msg);
					$('div#adminGalContainer').prepend('<span id="localMessage" class="hideUp right">File uploaded</span>');
					$('.hideUp').show();
					$('.bar').css({width: '0%'});
				}else{
					msg = msg.replace("0|", 'Error: ');
					$('div#adminGalContainer').prepend('<p class="error">'+msg+'</p>');
				}
				//$('button#uploadFileSubmit').css({'opacity':1,'cursor':'pointer'}); 
				$('div.progress').hide();

				return true;
			
			// for uploading file (both in manage_contents and preferences-bg-image), reload page with message
			}else if(context == 'newFile' || context == 'home_bg_img'){
				/** !!!!!!!!
				 * instead: re-generate page content accordingly via ajax, using a 'context' var. And insert msg in fixed positioned 'message div'.
				 */
				window.location = url+'?upload_result='+encodeURIComponent(msg);
			}
		},

		// Custom XMLHttpRequest with progress listener (progress bar)
		xhr: function() {
			var myXhr = $.ajaxSettings.xhr();
			if (myXhr.upload) {
				// For handling the progress of the upload
				myXhr.upload.addEventListener('progress', function(e) {
					if (e.lengthComputable) {
						var t = e.total;
						var l = e.loaded;
						var percent = (100.0 / t * l).toFixed(2);
						if(percent > 95){
							$('a#chooseFileLink').html('Processing (almost done) ...');
						}
						$('.bar').stop().animate({width: percent+'%'}, 1500);
					}
				} , false );
			}
			return myXhr;
		}
	});
});

// back to top
$('div#backToTop').on('click', 'a', function(){
	var theText = $(this).text();
	if(theText == 'oui' || theText == 'non'){
		//alert(theText);
		set_it(theText);
	}
})


/************************** FUNCTIONS ****************************/


/* show tooltip */
function showToolTip($this){
	var msg = $this.attr("data-tip");
	if($this.children('span.tooltip').length == 0){
		$this.append('<span class="tooltip">'+msg+'</span>');
	}
	var $tooltip = $this.find('span.tooltip');
	// calculate verticaly
	var offsetTop = $tooltip.offset().top;
	var sTop = $(window).scrollTop();
	var tH = $tooltip.outerHeight();
	// calculate orizontaly
	var offsetLeft = $tooltip.offset().left;
	var sLeft = $(window).scrollLeft();
	var tW = $tooltip.outerWidth();
	
	// reposition verticaly
	if(offsetTop+tH-sTop > wH){
		$tooltip.css('top', (-tH-10)+'px');
		$tooltip.addClass('tDown');
	}else{
		$tooltip.addClass('tUp');
	}
	// reposition orizontaly
	if(offsetLeft+tW-sLeft > wW){
		$tooltip.css('left', (-tW)+'px');
	}
	$tooltip.animate({'opacity': 1}, 200);
}
/* backup custom styles */
function backup_styles(name){
	$.ajax({
		method: "GET",
		url: '/'+demo+'~code/admin/admin_ajax.php?backup_styles='+name
	})
	.done(function(msg){ // msg is either img src, or error message
		if(msg.substr(0,2) == '1|'){
			var backupName = msg.substr(2);
			hideModal($('#saveBackup'));
			var optionText = backupName.replace(/\.php$/, '');
			var optionValue = encodeURIComponent(backupName);
			//<option value="'.urlencode($v).'">'.substr( filename($v, 'decode'), 0, -4).'</option>
			$('select[name=chooseBackups]').append($('<option>', {
				value: optionValue,
				text: optionText
			}));
			$('select[name=chooseBackups] option[value="'+optionValue+'"]').prop('selected', true);
			alert('backup "'+optionText+'" completed');
		}else{
			alert(msg);
		}
	});
}

/* choose a previous backup */
function choose_backup(choosen){
	$.ajax({
		method: "GET",
		url: '/'+demo+'~code/admin/admin_ajax.php?choose_backup='+choosen
	})
	.done(function(msg){ // msg is either img src, or error message
		if(msg.substr(0,2) == '1|'){
			window.location.href = window.location.href;
		}else{
			alert(msg);
		}
	});
}

/* rotate image in html and adapt html containers and layout */
function rotateImage($elm, deg) {
	$elm.removeAttr('class');
	$elm.addClass('aImg');
	$elm.addClass('rot'+deg);
}

// rotate image in php via ajax and save new images versions
function rotate_image($elm, image_url, orientation){

	$elm.addClass('imgProcessing');

	$.ajax({
		method: "GET",
		url: '/'+demo+'~code/admin/admin_ajax.php?rotateImage&image='+image_url+'&orientation='+orientation
	})
	.done(function(msg){ // msg is either '1|[image_url]', or error message '0|...'
		if(msg.substr(0,2) == '1|'){
			var rand = Math.random(0,999);
			// reset image container rotation (and reload the bg image with rand query), hide save & cancel buttons, reset a.rotate data-rotate value
			$elm.removeAttr('class').addClass('aImg').css( 'background-image', 'url('+image_url+'?v='+rand+')' );
			$elm.closest('div').find('a.save').hide();
			$elm.closest('div').find('a.cancel').hide();
			$elm.closest('div').find('a.rotate').attr('data-rotate', '90');
		}else{
			$('#ajaxTarget').html(msg);
		}
	});
}

/** (upload sub-functions) */
// get upload fileName without 'fake' path
function basename(path){
    return path.replace(/\\/g,'/').replace( /.*\//, '' );
}

// return file size in bytes
function getFileSize(){
    if(window.ActiveXObject){	// old IE
        var fso = new ActiveXObject("Scripting.FileSystemObject");
        var filepath = document.getElementById('fileUpload').value;
        var thefile = fso.getFile(filepath);
        var sizeinbytes = thefile.size;
    }else{						// modern browsers
        var sizeinbytes = document.getElementById('fileUpload').files[0].size;
    }
	return sizeinbytes;
}

// return bytes size to human readable size
function bytesToReadbale(sizeInBytes){
	var fSExt = new Array('Bytes', 'KB', 'MB', 'GB');
	fSize = sizeInBytes; i=0;
	while(fSize>900){
		fSize/=1024;
		i++;
	}
	var humanSize = (Math.round(fSize*100)/100)+' '+fSExt[i];
	return humanSize;
}
/** (end upload sub-functions) */


// update item name
function updateName(oldName, newName, parents, adminPage){
	//alert(oldName+', '+newName+', '+parents);
	var oldName = encodeURIComponent(oldName);
	var newName = encodeURIComponent(newName);
	var parents = encodeURIComponent(parents);
	$.ajax({
		method: "GET",
		url: '/'+demo+'~code/admin/admin_ajax.php',
		data: 'updateName&oldName='+oldName+'&newName='+newName+'&parents='+parents+'&adminPage='+adminPage
	})
	.done(function(msg){
		$('#ajaxTarget').html(msg);
		/*
		if(!msg.match("^<p class=\"error")){
			var parentLi = $('li[data-name="'+decodeURIComponent(newName)+'"]');
			var inputSaved = parentLi.find('input[name="'+decodeURIComponent(newName)+'"]');
			inputSaved.addClass('saved');
			setTimeout(function(){ inputSaved.removeClass('saved') }, 2000);
		}
		*/
	});
}

// show / hide item
function showHide(item, parents){
	var url = window.location.href;
	//alert(url);
	if( url.match(/manage_contents/) ){
		var adminPage = 'manage_contents';
	}else if( url.match(/manage_structure/) ){
		var adminPage = 'manage_structure';
	}
	// show or hide it?
	var first = item.substring(0,1);
	if(first == '_'){				// show it = remove starting underscore from name
		var newName = item.substr(1);
	}else{ 							// hide it= add starting underscore to name
		var newName = '_'+item;
	}
	//alert('item: '+item+', newName: '+newName+', parents: '+parents);
	updateName(item, newName, parents, adminPage);
}

// change position
function updatePosition(item, oldPosition, newPosition, parents, adminPage){
	//alert(item+': from '+oldPosition+' to '+newPosition+"\n"+'parents: '+parents);
	var item = encodeURIComponent(item);
	var parents = encodeURIComponent(parents);
	
	//alert(item+' > '+parents);
	
	$.ajax({
		method: "GET",
		url: '/'+demo+'~code/admin/admin_ajax.php',
		data: 'updatePosition&item='+item+'&oldPosition='+oldPosition+'&newPosition='+newPosition+'&parents='+parents+'&adminPage='+adminPage
	})
	.done(function(msg){
		//alert(item);
		$('#ajaxTarget').html(msg);
		var parentLi = $('li[data-name="'+decodeURIComponent(item)+'"]');
		//var inputSaved = parentLi.find('input[name="'+decodeURIComponent(item)+'"]');
		parentLi.addClass('saved');
		setTimeout(function(){ parentLi.removeClass('saved') }, 2000);
	});
}

// save text from textarea into file.txt
function saveTextDescription(file, enText, deText){
	var file = encodeURIComponent(file);
	var enText = encodeURIComponent(enText);
	var deText = encodeURIComponent(deText);
	$.ajax({
		method: "GET",
		url: '/'+demo+'~code/admin/admin_ajax.php',
		data: 'saveTextDescription&file='+file+'&enText='+enText+'&deText='+deText
	})
	.done(function(msg){
		$("#ajaxTarget").find('a.button.saveText').each( function(){
			if(!$(this).hasClass("disabled")){
				$(this).addClass("disabled");
				if(msg.match("^<p class=\"success")){
					$(this).before('<span id="localMessage">changes saved</span> ');
				}else{
					$('#message').html(msg);
				}
				return false;
			}
		});
	});
}


// save gallery file i.e. /section/_XL/gal-12345.gal
// and display updated gallery output in div.adminGal
// action can be: 'add', 'remove' or 'position'
function saveGalleryChange(path, file, action, position){
	var path = encodeURIComponent(path);
	var file = encodeURIComponent(file);
	$.ajax({
		method: "GET",
		url: '/'+demo+'~code/admin/admin_ajax.php',
		data: 'saveGalleryChange&path='+path+'&file='+file+'&action='+action+'&position='+position
	})
	.done(function(msg){
		var target = $("div#adminGalContainer");
		if(msg.match("^<p class=\"error")){
			target.prepend(msg);
		}else{
			target.html(msg);
		}
		return false;
	});
}

// add image from uploads
function addFileFromUploads(url, path, replace, end, context){
	
	if(context == 'edit_text'){
		insertImg( decodeURIComponent(url) );
		hideModal($('#chooseFromUploadsModal'));
		hideModal($('#uploadFileInsertContainer'));
		return false;
	}
	var $target = $("body p#chooseFromUploadsResult");
	$.ajax({
		method: "GET",
		url: '/'+demo+'~code/admin/admin_ajax.php',
		data: 'addFileFromUploads&url='+url+'&path='+path+'&replace='+replace+'&end='+end+'&context='+context
	})
	.done(function(msg){
		if(msg == '|done|success'){
			if(context == 'gallery'){
				var gal_added = '?gal_added';
			}else{
				var gal_added = '';
			}
			if( context == 'home_bg_img' ){
				window.location.href = '/'+demo+'~code/admin/preferences.php?upload_result='+encodeURIComponent('1|New background image set');
			}else{
				var p = path.substr(path.lastIndexOf('/') + 1);
				var hash = p.replace(/[^a-zA-Z0-9]/g, '');
				window.location.href = '/'+demo+'~code/admin/manage_contents.php'+gal_added+'#'+hash;
			}
		}else{
			$target.append( msg+': '+decodeURIComponent(url)+' ' );
		}
		return false;
	});
}

// set to yes or no
function set_it(v){
	$.ajax({
		method: "GET",
		url: '/'+demo+'~code/admin/admin_ajax.php',
		data: 'yesOrNo='+v
	})
}


// show Modal window
function showModal(modal, callback){
	var $newDiv,
	    $overlayDiv,
		query = '';
		
	// create overlay if it does not exist
	if($('div.overlay').length == 0){
	    $overlayDiv = $('<div class="overlay"/>');
		$('body').append($overlayDiv);
	}else{
	    $overlayDiv = $('div.overlay');
	}
	$overlayDiv.fadeIn();
	// parse and check for query string (rel="zoomModal?img=/path/to/image.jpg") will append query string to loading modal.
	if(modal.indexOf('?') !== -1){
		var splitRel = modal.split("?");
		modal = splitRel[0];
		query =  '?'+splitRel[1];
		//alert(query);
	}
	// create modalContainer if it does not exist
	if($('div#'+modal).length == 0){
		$newdiv = $('<div class="modalContainer" id="'+modal+'"/>');
		$('body').append($newdiv);
	}else{
		$newdiv = $('div#'+modal);
	}
	$newdiv.load('/'+demo+'~code/admin/modals/'+modal+'.php'+query);
	$newdiv.show();
	checkModalHeight('#'+modal);
	if(callback !== undefined && typeof callback === 'function') {
        callback();
    }
}


function hideModal($elem){
    var n = $('div.modalContainer:visible').length;
	if(n > 0){
	    $elem.closest('div.modalContainer').hide();
	    n = n-1;
	}else{
	    $elem.closest('div').hide();
	}
	//alert(n);
    if(n < 1){
        $('div.overlay').fadeOut();
    }
}


// change positioning of modals to account for scrolling down window!
function checkModalHeight(elem){
	//alert(elem);
	var speed = 100;
	var scroltop = parseInt($(window).scrollTop());
	var newtop = scroltop+15;
    if(newtop<15){
	    newtop =  15;
    }
    //alert(newtop);
	$(elem).animate({top: newtop}, speed, function() {
		// focus on first txt input but exclude newFile modal
		if($(elem).attr("id") !== 'newFile'){
			$(elem).find('input[type=text]').eq(0).focus();
		}
	});
}

