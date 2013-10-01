$(function() {

	/*   ************************************************************   */
	/*	Correct pathing between local and live servesr	*/
	/*   ************************************************************   */

	var pathname = window.location.pathname;
	if (strstr(pathname,'/watchsolution/www/')) { var host = "http://localhost/watchsolution/www"; }
	else{ var host = "http://www.watchsolution.com"; }

	/*   ******************************************************   */
	/*   http://flowplayer.org/tools/demos/overlay/trigger.html   */
	/*   ******************************************************   */

	$("#facebox").overlay({

		// custom top position
		top: 260,

		// some mask tweaks suitable for facebox-looking dialogs
		mask: {

			// you might also consider a "transparent" color for the mask
			color: '#fff',

			// load mask a little faster
			loadSpeed: 200,

			// very transparent
			opacity: 0.5
		},

		// disable this for modal dialog-type of overlays
		closeOnClick: false,

		// load it immediately after the construction
		load: true

	});


	/*   ****************************************************   */
	/*   http://www.wil-linssen.com/demo/jquery-sortable-ajax/   */
	/*   ****************************************************   */

    $("#slide_sort").sortable({
      handle : '.handle',
      update : function () {
		var order = $('#slide_sort').sortable('serialize');
		$("#info").load("process-sortable.php?"+order);
      }
    });


	/*   ****************************************************   */
	/*   image popup slide show   */
	/*   ****************************************************   */

	/*
	$("img[rel]").overlay({

		// some mask tweaks suitable for facebox-looking dialogs
		mask: {

			// you might also consider a "transparent" color for the mask
			color: '#fff',

			// load mask a little faster
			loadSpeed: 200,

			// very transparent
			opacity: 0.5
		}

	});
	*/

	/*   ****************************************************   */
	/*   http://flowplayer.org/tools/demos/validator/custom-validators.html   */
	/*   ****************************************************   */

	$.tools.validator.fn("[group-required]", "At least one option needs to be selected.", function(input) {
		var name = input.attr("group-required");
		var group_members = $('input[name=answers[' + name + ']]');
		var checked_count = $('input[name=answers[' + name + ']]:checked').length;
		if((checked_count == 0) && (group_members.first().attr('id') == input.attr('id'))) {
			$('input[name=answers[' + name + ']]').click(function() {
				validate_form.data("validator").reset($('input[name=answers[' + name + ']]'));
			});
			return false;
		} else {
			return true;
		}
	});

	//$("#thisform").validator();

	/*   ****************************************************   */
	/*   http://james.padolsey.com/javascript/jquery-plugin-autoresize/	  */
	/*   ****************************************************   */

	$('textarea#description').autoResize({
		// On resize:
		onResize : function() {
			$(this).css({opacity:0.8});
		},
		// After resize:
		animateCallback : function() {
			$(this).css({opacity:1});
		},
		// Quite slow animation:
		animateDuration : 300,
		// More extra space:
		extraSpace : 40
	});


	/*   ****************************************************   */
	/*   recaps.php / guest_update.php - http://muiomuio.com/web-design/add-remove-items-with-jquery - 9/16/2010	  */
	/*   ****************************************************   */

	var i = $('input').size() + 1; // check how many input exists on the document and add 1 for the add command to work

	$('a#add').click(function() { // when you click the add link

		$("#links ul").append('<li><input name="url[]" type="text" value="" /> <a href="javascript:void(0);" title="delete" class="itemDelete">delete</a></li>'); // append (add) a new input to the document.
		// if you have the input inside a form, change body to form in the appendTo
		i++; //after the click i will be i = 3 if you click again i will be i = 4

	});

	$('a#add_guest').click(function() { // when you click the add link

		$("#guests ul").append('<li><input type="text" name="guests_f[]" class="normal" value="" style="float:left; width:200px;">&nbsp;<input type="text" name="guests_l[]" class="normal" value="" style="float:left; width:200px; margin-left:10px;">&nbsp;<a href="javascript:void(0);" class="itemDelete" style="float:left; margin:5px 0 0 10px;">delete</a><br class="clear"><input type="hidden" name="username[]" value=""></li>');

		i++;

	});

	$('a#add_attachments').click(function() { // when you click the add link

		$("#attachments ul").append('<li><input type="text" name="attachments[]" class="normal" value="">&nbsp;<a href="javascript:void(0);" class="itemDelete">delete</a><input type="hidden" name="username[]" value=""></li>'); // append (add) a new input to the document.
		// if you have the input inside a form, change body to form in the appendTo
		i++; //after the click i will be i = 3 if you click again i will be i = 4

	});

	$('.itemDelete').live('click', function() {
		$(this).closest('li').remove();
	});

	$('.attachmentsDelete').live('click', function() {
		
		$(this).closest('li').remove();

		var Id = $(this).attr("id");

		$.ajax({
		   type: "POST",
		   url: host + "/standards/ajax.php",
		   data: 'attachmentsDelete=' + Id
		 });

		return false;

	});

	/*   ****************************************************   */
	/*   guests-update.php - http://localhost/theyoungturks.com/www/demo/animate2.php	  */
	/*   ****************************************************   */

	$('.wrap').hover(

		function(){
			$(this).children('.comment').stop().animate({"top": '0px'}, 200);
		},

		function(){
			$(this).children('.comment').stop().animate({"top": '119px'}, 200);
		}

	);

	$('.comment').click( function(){
		
		//window.location = "guests-update.php?id="+$(this).attr("id");

		return false;

	});

});