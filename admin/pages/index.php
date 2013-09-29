<?php
include("../../starter.php");
require_login();
priv_level(0);

if (is_empty($_vars['type'])) { $_vars['type'] = "page"; }

$PageText = GetPageText("Pages");
include($CFG->baseroot."/admin/cms-header.php");
?>

<div id="mainbar">

	<div class="blur">
		<div class="shadow">
			<div class="content">

				<div id="content_header">

					<div id="content_title"><? pv($PageText->PageTitle) ?></div>

					<!-- <select class="normal" name="id" onchange="window.open(this.options[this.selectedIndex].value,'_self');this.selectedIndex=0;" style="float:right;">
						<option value=" ">Jump to Page</option>
						<?php
						$_jump = db_query("SELECT * FROM website_text ORDER BY type DESC, PageName ASC");
						while($jump = db_fetch_array($_jump)) {
							echo "<option value=\"pagetext_update.php?id=".$jump[PageID]."\">".$jump[PageName]."</option>";
						} ?>
					</select> -->

					<br class="clear">

				</div>

				<div id="content_body">

					<!-- <div style="padding:5px; margin-bottom:2px; background:#E5E5E5;">

						<div style="float:left;"><b>Page Name</b></div>
						<br class="clear">

					</div> -->

					<div style="margin-bottom:2px;">

						<table width="100%" border=0 cellspacing=0 cellpadding=0 style="text-align:left;">
							<tr>
								<td style="background:#F0F0F0; padding:5px;"><b>Page Name</b></td>
								<td style="width:2px;"></td>
								<td style="width:100px; background:#F0F0F0; padding:5px;"><b>Type</b></td>
							</tr>
						</table>

					</div>

					<?php
					$x=1;
					$qdb = db_query("SELECT * FROM website_text WHERE type = '".$_vars['type']."' ORDER BY type DESC, PageName ASC");
					while ($row = db_fetch_array($qdb)){

						if ($x == 1){ $x=0; $color="background:#F0F0F0;"; }else{ $x=1; $color="background:#E5E5E5;"; } ?>

						<div style="margin-bottom:2px;">

							<table width="100%" border=0 cellspacing=0 cellpadding=0 style="text-align:left;">
								<tr>
									<td style="<?= $color ?>"><a class="pblock" href="pagetext_update.php?id=<? pv($row[PageID]) ?>&type=<?= $_vars['type'] ?>" title="" style="text-decoration:none;">
										<?php
										echo $row['PageName'];
										if (!is_empty($row['content_block'])) { echo " - ".$row['content_block']; }
										?>
									</a></td>
									<td style="width:2px;"></td>
									<td style="width:100px; <?= $color ?> padding:5px;"><?= ucwords_ext($row['type']) ?></td>
								</tr>
							</table>

						</div>

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