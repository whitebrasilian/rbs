<div class="sidebar_header">Page Text Navigation</div>
<div class="sidebar_content">

	<ul>
		<li><a class="nblock" href="pagetext_update.php">Add a Page</a></li>
		<li><a class="nblock" href="index.php?type=page">View Pages</a></li>

		<?php if ($_SESSION['user']['username'] == 'webmaster') { ?>

		<li><a class="nblock" href="index.php?type=no-page">View Non-Pages</a></li>
		<li><a class="nblock" href="index.php?type=facebox">View Notifications</a></li>
		<li><a class="nblock" href="index.php?type=manager">View Manager Pages</a></li>

		<?php } ?>

	</ul>

</div>