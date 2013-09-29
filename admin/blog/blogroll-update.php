<?php
include("../../starter.php");
require_login();
priv_level(0);

if ($_vars['status'] == "delete") {

	db_query("DELETE FROM blogroll WHERE id = '".$_vars['id']."'",0,0);
	header("Location:blogroll-list.php");
	die;

}

if (!is_empty($_vars['name'])) {

	if (is_empty($_vars['id'])) {

		db_query("INSERT INTO blogroll (name, url, status) VALUES ('".mrClean($_vars['name'])."', '".$_vars['url']."', '".$_vars['status']."')",0,0);
		$_vars['id'] = db_insert_id();

	}else{

		db_query("UPDATE blogroll SET name = '".mrClean($_vars['name'])."', url = '".$_vars['url']."', status = '".$_vars['status']."' WHERE id = '".$_vars['id']."'");
	}

	header("Location:blogroll-update.php?id=".$_vars['id']);
	die;

}

if (!is_empty($_vars['id'])) {
	$_dbq = db_query("SELECT * FROM blogroll WHERE id = '".$_vars['id']."' limit 1");
	$dbq = db_fetch_assoc($_dbq);

}else{ $dbq=""; }

$PageText = GetPageText("Blog Categories");
include($CFG->baseroot."/admin/cms-header.php");
?>

<?php if (!is_empty($_GET['complete'])) { ?>

	<div id="facebox">

		<div class="facebox_border">

			<?php $copy = GetPageText($whoami." Successfully ".$_GET['complete']); ?>

			<div ><h2 class="faceboxh2"><? pv($copy->PageTitle); ?></h2></div>
			<div style="margin:10px 0 15px 0; font-size:13px;"><? pv($copy->PageText); ?></div>
			<div style="color:#666; font-size:10px; float:left; padding-top:10px;">To close, click the Close button or hit the ESC key.</div>
			<div style="float:right;"><button class="close"> Close </button></div>
			<br class="clear">

		</div>

	</div>

<?php } ?>

<div id="mainbar">

	<div class="blur">
		<div class="shadow">
			<div class="content">

				<div id="content_header">

					<div id="content_title"><?= $PageText->PageName ?></div>

					<select class="normal" name="id" onchange="window.open(this.options[this.selectedIndex].value,'_self');this.selectedIndex=0;" style="float:right;">
						<option value=" ">Jump to Questions</option>
						<?php
						//$_jump = db_query("SELECT id, question FROM ".$whoamis." WHERE comment_parent IS NULL AND status <> 'archive' ORDER BY dorder ASC");
						while($jump = db_fetch_array($_jump)) {
							echo "<option value=\"".$whoamis."-update.php?id=".$jump['id']."\">&nbsp;&nbsp;".$jump['question']."</option>";
						} ?>
					</select>

					<br class="clear">

				</div>

				<div id="content_body">

					<form method="POST" action="blogroll-update.php" id="thisform" name="thisform">

						<div class="field_title">Website Name *</div>

						<div class="field_copy">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque massa augue, congue a consectetur id, condimentum ac purus.</div>

						<div class="field_box">

							<input type="text" name="name" value="<?= $dbq['name'] ?>" class="normal" tabindex=<?php pv($ti); $ti++; ?>  required="required" style="">

						</div>

						<br>

						<div class="field_title">Website URL *</div>

						<div class="field_copy">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque massa augue, congue a consectetur id, condimentum ac purus.</div>

						<div class="field_box">

							<input type="text" name="url" value="<?= $dbq['url'] ?>" class="normal" tabindex=<?php pv($ti); $ti++; ?>  required="required" style="">

						</div>

						<br>

						<div class="field_title">Status</div>

						<div class="field_copy">Show to the public, Hide from the public, Delete.</div>

						<div class="field_box">
							<?php if (is_empty($dbq['status'])) { $dbq['status'] = 'show'; } ?>
							<select name="status" class="normal" tabindex=<?php pv($ti); $ti++; ?> >
								<option value="show" <?php if ($dbq['status'] == 'show') { echo"selected"; } ?>>Show</option>
								<option value="hide" <?php if ($dbq['status'] == 'hide') { echo"selected"; } ?>>Hide</option>
								<option value="delete" <?php if ($dbq['status'] == 'delete') { echo"selected"; } ?>>Delete</option>
							</select>
						</div>

						<br>&nbsp;<br>

						<hr class="normal">
						<br>

						<button type="submit" class="buttons" class="lfloat">Save</button>

						<br>

					<input type="hidden" name="id" value="<?= $dbq['id']; ?>">
					</form>

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