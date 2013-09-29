<?php $content = content($page); ?>

<div id="mail-sent" class="alertbox" style="display:none;" onclick="$(this).fadeOut(500);">
	Your message has been sent. We will be contacting you very soon. Thank you!
	<br><br>

	<small>(click to close)</small>
</div>

<div class="single-column">

	<div class="section-header"><?= $content[0][0] ?></div>
	<div class="single-section-header-bar"></div>
	<div class="single-section-copy">

		<?= prep_var($content[0][1]) ?>

		<br><br>

		<form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>" name="thisform">

			<div style="float:left; margin-right:20px;"><input type="text" id="name" name="name" value="" tabindex=<?php pv($ti); $ti++; ?> style="width:180px; height:21px; border:1px solid #7f7f7f; background-color:#000; color:#fff;"></div>
			<div style="float:left; margin-top:5px;">Name (required)</div>
			<br class="clear">
			<br>

			<div style="float:left; margin-right:20px;"><input type="text" id="email" name="email" value="" tabindex=<?php pv($ti); $ti++; ?> style="width:180px; height:21px; border:1px solid #7f7f7f; background-color:#000;  color:#fff;"></div>
			<div style="float:left; margin-top:5px;">Email (will not be published)(required)</div>
			<br class="clear">
			<br>

			<div style="float:left; margin-right:20px;"><input type="text" id="phone" name="phone" value="" tabindex=<?php pv($ti); $ti++; ?> style="width:180px; height:21px; border:1px solid #7f7f7f; background-color:#000; color:#fff;"></div>
			<div style="float:left; margin-top:5px;">Phone</div>
			<br class="clear">
			<br>

			<div style="float:left;"><textarea id="body" name="body" style="width:565px; height:145px; border:1px solid #7f7f7f; background-color:#000; color:#fff;" tabindex=<?php pv($ti); $ti++; ?>></textarea></div>
			<br class="clear">
			<br>

			<a class="sprite submit" onMouseUp="sendMail()" style="float:left;"></a>

			<br class="clear">

			<script type="text/javascript">
			<!--
			function sendMail() {

				var name			= $('#name').val();
				var email			= $('#email').val();
				var phone			= $('#phone').val();
				var body			= $('#body').val();

				$.ajax({
					type: "POST",
					url: "<?= $CFG->host ?>/standards/ajax.php",
					data: 'name=' + name + '&email=' + email + '&phone=' + phone + '&body=' + body,
					success: function(msg){

						if (msg==1){
							$('#mail-sent').fadeIn(1000);
							$('#body').val('');
						}

					}
				});

				return false;

			}
			//-->
			</script>

		</form>

	</div>

</div>