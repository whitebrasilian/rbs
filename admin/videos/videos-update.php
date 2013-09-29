<?php
include("../../starter.php");
require_login();
priv_level(0);

$whoami = "Videos";
$whoamis = strtolower($whoami);

/*   delete product & images   */
if($_vars['status']=='delete') {

	db_query("DELETE FROM videos WHERE id = '".$_vars['id']."'",0,0);
	$outcome = "Deleted";
	header("Location:index.php?complete=".$outcome);
	die;

}

if (!is_empty($_vars['process'])) { //pre($_vars,1);

	if(is_empty($_vars['id'])) {

		/* add data to table table */
		db_query("INSERT INTO ".$whoamis." (name, embed_code, status, date_time) VALUES ('".mrClean($_vars['name'])."', '".$_vars['embed_code']."', '".$_vars['status']."', NOW())",0,0);

		/* get the id that was just created */
		$_vars[id] = db_insert_id();

		$outcome = "Added&id=".$_vars[id];

	} else {

		/* update the table with the new information */
		db_query("UPDATE ".$whoamis." SET name = '".mrClean($_vars['name'])."', embed_code = '".$_vars['embed_code']."', status = '".$_vars['status']."', date_time = NOW() WHERE id = '".$_vars['id']."'");
		$outcome = "Edited&id=".$_vars[id];

	}

	header("Location:".$whoamis."-update.php?complete=".$outcome);

}

if (!is_empty($_vars['id'])) {
	$_dbq = db_query("SELECT * FROM ".$whoamis." WHERE id = '".$_vars['id']."' limit 1");
	$dbq = db_fetch_assoc($_dbq);

}else{ $dbq=""; }

$PageText = GetPageText($whoami." Add/Edit");
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
						<option value=" ">Jump to Video</option>
						<?php
						$_jump = db_query("SELECT id, name FROM ".$whoamis." WHERE status <> 'archive' ORDER BY name ASC");
						while($jump = db_fetch_array($_jump)) {
							echo "<option value=\"".$whoamis."-update.php?id=".$jump['id']."\">&nbsp;&nbsp;".$jump['name']."</option>";
						} ?>
					</select>

					<br class="clear">

				</div>

				<div id="content_body">

					<form method="POST" action="<?= $whoamis ?>-update.php" id="thisform" name="thisform">

					<div class="field_title">Video Title</div>

					<div class="field_copy">This is the title that displays above the video.</div>

					<div class="field_box">
						<input type="text" name="name" value="<?= $dbq['name'] ?>" class="normal" style="width:500px;" tabindex=<?php pv($ti); $ti++; ?>>
					</div>

					<br>

					<div class="field_title">YouTube Video Id</div>

					<div class="field_copy">Enter the YouTube ID number. This can be found by clicking the "Share" button below the video. You will see a link like "http://youtu.be/nrWe420uxEA", you want the "nrWe420uxEA". Having a video ID in this field will override an image in the blog.</div>

					<div class="field_box">
						<input type="text" name="embed_code" value="<?= $dbq['embed_code'] ?>" class="normal" tabindex=<?php pv($ti); $ti++; ?>>

						<?php if (!is_empty($_vars['id'])) { echo '<br><br><iframe width="423" height="270" src="http://www.youtube.com/embed/'.$dbq['embed_code'].'" frameborder="0" allowfullscreen></iframe>'; } ?>

					</div>

					<br>

					<div class="field_title">Status</div>

					<div class="field_copy">Show to the public, Hide from the public, Delete (reversible by webmaster).</div>

					<div class="field_box">
						<?php if (is_empty($dbq['status'])) { $dbq['status'] = 'show'; } ?>
						<select name="status" class="normal" tabindex=<?php pv($ti); $ti++; ?> >
							<option value="show" <?php if ($dbq['status'] == 'show') { echo"selected"; } ?>>Display</option>
							<option value="delete" <?php if ($dbq['status'] == 'delete') { echo"selected"; } ?>>Delete</option>
						</select>

					</div>

					<br>

					<hr class="normal">

					<div style="float:right;"><button type="submit" class="buttons">Submit</button></div>

					<br>

					<input type="hidden" name="process" value="1">
					<input type="hidden" name="id" value="<?= $_vars['id']; ?>">
					</form>

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
			<li><a class="nblock" href="index.php"><?= $whoami ?> List</a></li>
			<li><a class="nblock" href="<?= $whoamis ?>-update.php"><?= $whoami ?> Add/Edit</a></li>
		</ul>

	</div>

	<?php include('../user-info.php'); ?>

</div>

<br class="clear">

<? include($CFG->baseroot."/admin/cms-footer.php"); ?>