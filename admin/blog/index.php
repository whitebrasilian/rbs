<?php
include("../../starter.php");
require_login();
priv_level(0);

$PageText = GetPageText("Blog List");
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
						$_jump = db_query("SELECT * FROM blog WHERE 1 ORDER BY datetime DESC");
						while($jump = db_fetch_assoc($_jump)) {
							echo "<option value=\"blog-update.php?id=".$jump['id']."\">&nbsp;&nbsp;".$jump['title']."</option>";
						} ?>
					</select>

					<br class="clear">

				</div>

				<div id="content_body">

					<ul style="margin:0px; padding:0px;">

						<?php
						$x=1;
						$_qdb = db_query("
						SELECT
						blog_section.section,
						blog_link.blog_id
						FROM
						blog_section
						Inner Join blog_link ON blog_section.id = blog_link.section_id
						WHERE
						1
						ORDER BY blog_link.section_id ASC, blog_section.dorder ASC
						");
						while ($qdb = db_fetch_assoc($_qdb)){

							if ($x == 1){ $x=0; $color="background:#F0F0F0;"; }else{ $x=1; $color="background:#E5E5E5;"; }

							if ($sec != $qdb['section']) {

								$sec = $qdb['section']; ?>

								<div class="handle" style="<?= $color ?> width:620px; float:left; margin-bottom:2px; padding:5px; font-weight:bold;"><?= $qdb['section'] ?></div>

								<br class="clear">

							<?php } ?>

							<li>

								<ul style="margin:0px; padding:0px;">

									<?php
									$x=1;
									$_qdc = db_query("SELECT id, title FROM blog WHERE id = '".$qdb['blog_id']."' ORDER BY datetime DESC");
									while ($qdc = db_fetch_assoc($_qdc)){

										if ($x == 1){ $x=0; $color="background:#F0F0F0;"; }else{ $x=1; $color="background:#E5E5E5;"; } ?>

										<li>

											<div class="handle2" style="<?= $color ?> width:610px; float:left; margin-bottom:2px; padding-left:20px;">

												<a class="pblock" href="blog-update.php?id=<?= $qdc['id'] ?>" title="" style="text-decoration:none;"><?= $qdc['title'] ?></a>

											</div>

											<br class="clear">

										</li>

									<?php } ?>

								</ul>

							</li>

						<?php } ?>

					</ul>

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