<?php
include("../../starter.php");
require_login();
priv_level(0);

if (!is_empty($_vars['delete'])) {
	db_query("DELETE FROM blog_section WHERE id = '".$_vars['section_id']."'",0,0);
	db_query("DELETE FROM blog_link WHERE section_id = '".$_vars['section_id']."'",0,0);

	header("Location:blog-categories.php");
}

if (!is_empty($_vars['section'])) {

	if (is_empty($_vars['section_id'])) {

		$_qdb = db_query("SELECT id FROM blog_section WHERE section = '".$_vars['section']."' limit 1");
		$qdb = db_fetch_assoc($_qdb);

		if (!is_empty($qdb['id'])) {

			$_vars['section_id'] = $qdb['id'];

		}else{

			db_query("INSERT INTO blog_section (section) VALUES ('".mrClean($_vars['section'])."')",0,0);
			$_vars['section_id'] = db_insert_id();

		}

	}elseif (!is_empty($_vars['section_id']) && !is_empty($_vars['section'])) {

		db_query("UPDATE blog_section SET section = '".mrClean($_vars['section'])."' WHERE id = '".$_vars['section_id']."'");
	}

	header("Location:blog-categories.php");

}

if (!is_empty($_vars['id'])) {
	$_dbq = db_query("SELECT * FROM blog WHERE id = '".$_vars['id']."' limit 1");
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

					<form method="POST" action="blog-categories.php" id="thisform" name="thisform">

						<div class="field_title">Blog Section *</div>

						<div class="field_copy">To add a new tag, fill in the textfield on the right. To edit a tag, select the tag to edit on the left, then fill in the edited version on the right.</div>

						<div class="field_box">

							<select name="section_id" tabindex=<?php pv($ti); $ti++; ?> style="width:200px; margin-right:10px;" class="normal lfloat">

								<option value=""></option>

								<?php
								$_sec = db_query("SELECT id, section FROM blog_section WHERE 1 ORDER BY section ASC");
								while($sec = db_fetch_assoc($_sec)){ ?>

									<option value="<?= $sec['id'] ?>"><?= $sec['section'] ?></option>

								<?php } ?>

							</select>

							<input type="text" name="section" value="" class="normal_sm lfloat" tabindex=<?php pv($ti); $ti++; ?>  required="required" style="width:180px;">

							<br class="clear">

						</div>

						<div class="field_box">

							<input type="checkbox" class="normal lfloat" style="width:25px; margin:5px 5px 0 0;" name="delete" value="1" tabindex=<?php pv($ti); $ti++; ?>>
							<div id="" class="field_copy lfloat" style="width:350px;">Check to delete tag chosen in the drop down. Before deleting make sure all posts have been properly re-tagged.</div>
							<br class="clear">

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