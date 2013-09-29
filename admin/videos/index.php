<?php
include("../../starter.php");
require_login();
priv_level(0);

$whoami = "Videos";
$whoamis = strtolower($whoami);

$PageText = GetPageText($whoami." List");
include($CFG->baseroot."/admin/cms-header.php");
?>

<div id="mainbar">

	<div class="blur">
		<div class="shadow">
			<div class="content">

				<div id="content_header">

					<div id="content_title"><? pv($PageText->PageTitle) ?></div>

					<select class="normal" name="id" onchange="window.open(this.options[this.selectedIndex].value,'_self');this.selectedIndex=0;" style="float:right;">
						<option value=" ">Jump to Gallery</option>
						<?php
						$_jump = db_query("SELECT id, name FROM ".$whoamis." WHERE status <> 'archive' ORDER BY name ASC");
						while($jump = db_fetch_array($_jump)) {
							echo "<option value=\"".$whoamis."-update.php?id=".$jump['id']."\">&nbsp;&nbsp;".$jump['name']."</option>";
						} ?>
					</select>

					<br class="clear">

				</div>

				<div id="content_body">

					<div style="padding:5px; margin-bottom:2px; background:#E5E5E5;">

						<div style="float:left; width:440px;"><b>Title</b></div>
						<!-- <div style="float:left; margin:0px 0 0 1px;"><b>Image Count</b></div>
						<div style="float:left; margin:0px 0 0 1px;"><b>Type</b></div> -->
						<br class="clear">

					</div>

					<div id="info"></div>

					<ul id="slide_sort">

						<?php
						$x=1;

						$_qdb = db_query("SELECT * FROM ".$whoamis." WHERE status <> 'archive' ORDER BY name ASC");
						while ($qdb = db_fetch_array($_qdb)){

							if ($x == 1){ $x=0; $color="background:#F0F0F0;"; }else{ $x=1; $color="background:#E5E5E5;"; } ?>

							<li id="listItem_<? pv($qdb[id]) ?>">

								<div class="handle" style="margin-bottom:2px;">

									<table width="100%" border=0 cellspacing=1 cellpadding=0 style="text-align:left;">
										<tr>
											<td style="width:440px; <?= $color ?>"><a class="pblock" href="<?= $whoamis ?>-update.php?id=<? pv($qdb['id']) ?>" title="" style="text-decoration:none;"><?= $qdb['name'] ?>&nbsp;</a></td>
											<td style="width:2px;"></td>
											<!-- <td style="<?= $color ?> padding:5px;">

												<?php
												$_dbq = db_query("SELECT * FROM upload WHERE gallery_id = '".$qdb['id']."'");
												echo db_num_rows($_dbq);
												?>

											</td> -->
										</tr>
									</table>

									<!--
									<div style="width:110px; float:left; padding:0 0 0 1px; <?= $color ?> margin-left:1px;">

										<a class="pblock" href="index.php?type=<? pv($qdb['type']) ?>" title="" style="text-decoration:none;"><?= $qdb['type'] ?></a>

									</div>

									<br class="clear">
									-->

								</div>

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

	<div class="sidebar_header"><?= $whoami ?> Navigation</div>
	<div class="sidebar_content">

		<ul>
			<li><a class="nblock" href="<?= $whoamis ?>-update.php"><?= $whoami ?> Add/Edit</a></li>
		</ul>

	</div>

	<?php include('../user-info.php'); ?>

</div>

<br class="clear">

<? include($CFG->baseroot."/admin/cms-footer.php"); ?>