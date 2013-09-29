<div class="sidebar_header">Logged In User</div>
<div class="sidebar_content">

	<?php pv($_SESSION['user']['first_name']); ?> <?php pv($_SESSION['user']['last_name']); ?><br>
	Member ID "<?php pv($_SESSION['user']['username']); ?>"<br>
	<?php pv(ucwords($_SESSION['user']['priv'])); ?> Privileges<br>

</div>

<div class="sidebar_header">Content Managment</div>
<div class="sidebar_content">

	<div id="" class="" style="float:left; margin-right:10px; width:120px;">

		<?php if ($_SESSION['user']['username'] == 'webmaster') { ?>

			<a href="<?= $CFG->host ?>/admin/users/" class="">Members</a><br>

			<a href="<?= $CFG->host ?>/admin/pages/" class="">Pages</a><br>

			<!--<a href="<?= $CFG->host ?>/admin/galleries/" class="">Image Galleries</a><br>
			<a href="<?= $CFG->host ?>/admin/videos/" class="">Videos</a><br>-->
			<a href="<?= $CFG->host ?>/admin/testimonials/" class="">Testimonials</a><br>

		<?php } ?>

		<a href="<?= $CFG->host ?>/admin/blog/" class="">Blog</a><br>
		<br>
		<a href="<?= $CFG->host ?>/logout.php">Logout</a>

	</div>
	<br class="clear">

</div>
