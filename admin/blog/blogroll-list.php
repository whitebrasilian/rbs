<?php
include("../../starter.php");
require_login();
priv_level(0);

$PageText = GetPageText("Blogroll List");
include($CFG->baseroot."/admin/cms-header.php");
?>

<div id="mainbar">

	<div class="blur">
		<div class="shadow">
			<div class="content">

				<div id="content_header">

					<div id="content_title"><? pv($PageText->PageTitle) ?></div>

					<select class="normal" name="id" onchange="window.open(this.options[this.selectedIndex].value,'_self');this.selectedIndex=0;" style="float:right;">
						<option value=" ">Jump to Post</option>
						<?php
						//$_jump = db_query("SELECT * FROM blog WHERE 1 ORDER BY datetime DESC");
						while($jump = db_fetch_assoc($_jump)) {
							echo "<option value=\"blog-update.php?id=".$jump['id']."\">&nbsp;&nbsp;".$jump['title']."</option>";
						} ?>
					</select>

					<br class="clear">

				</div>

				<div id="content_body">

					<div style="padding:5px; margin-bottom:2px; background:#E5E5E5;">

						<div style="float:left; width:380px;"><b>Website Name</b></div>
						<div style="float:left; width:220px;"><b>Website URL</b></div>
						<br class="clear">

					</div>

					<?php
					$x=1;
					$_qdb = db_query("
					SELECT
					*
					FROM
					blogroll
					WHERE
					1
					ORDER BY name ASC
					");
					while ($qdb = db_fetch_assoc($_qdb)){

						if ($x == 1){ $x=0; $color="background:#F0F0F0;"; }else{ $x=1; $color="background:#E5E5E5;"; } ?>

						<div style="<?= $color ?> width:380px; float:left; margin-bottom:2px;">

							<a class="pblock" href="blogroll-update.php?id=<?= $qdb['id'] ?>" title="" style="text-decoration:none;"><?= $qdb['name'] ?></a>

						</div>

						<div style="<?= $color ?> width:245px; float:left; margin-bottom:2px;">

							<a class="pblock" href="blogroll-update.php?id=<?= $qdb['id'] ?>" title="" style="text-decoration:none;"><?= $qdb['url'] ?></a>

						</div>

						<br class="clear">

					<?php } ?>

				</div>

			</div>
		</div>
	</div>

	<br>

</div>

<div class="sidebar">

	<?php include('nav.php'); ?>

	<?php include('../user-info.php'); ?>

</div>

<br class="clear">

<? include($CFG->baseroot."/admin/cms-footer.php"); ?>