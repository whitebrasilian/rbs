<?php
include("../../starter.php");
require_login();
priv_level(0);

$whoami = "Galleries";
$whoamis = strtolower($whoami);

/*   delete product & images   */
if (!is_empty($_vars['del'])) {

	$_qdb = db_query("SELECT lrg_rename FROM upload WHERE upload_id = '".$_vars['del']."' limit 1");
	$qdb = db_fetch_array($_qdb);

	unlink(UPLOADS.'/'.$qdb[lrg_rename]);

	db_query("DELETE FROM upload WHERE upload_id = '".$_vars['del']."'");

	header("Location:".$whoamis."-update.php?complete=Deleted&id=".$_vars['id']);
	die;

}


if (!is_empty($_vars['process'])) { //pre($_vars,1);

	if($_vars['status']=="delete") {

		$_qdb = db_query("SELECT lrg_rename, upload_id FROM upload WHERE gallery_id = '".$_vars['id']."'");
		while($qdb = db_fetch_assoc($_qdb)){

			unlink(UPLOADS.'/'.$qdb['lrg_rename']);

			db_query("DELETE FROM upload WHERE upload_id = '".$qdb['upload_id']."'");

		}

		/* add data to table table */
		db_query("DELETE FROM ".$whoamis." WHERE id = '".$_vars['id']."'",0,0);

	} elseif(is_empty($_vars['id'])) {

		/* add data to table table */
		db_query("INSERT INTO ".$whoamis." (name, status, date_time) VALUES ('".mrClean($_vars['name'])."', '".$_vars['status']."', NOW())",0,0);

		/* get the id that was just created */
		$_vars[id] = db_insert_id();

		$outcome = "Added&id=".$_vars[id];

	} else {

		/* update the table with the new information */
		db_query("UPDATE ".$whoamis." SET name = '".mrClean($_vars['name'])."', status = '".$_vars['status']."', date_time = NOW() WHERE id = '".$_vars['id']."'");
		$outcome = "Edited&id=".$_vars[id];

	}

	if ($_vars['status'] == 'delete') { header("Location:index.php"); }
	else{ header("Location:".$whoamis."-update.php?complete=".$outcome); }

}

if (!is_empty($_vars['id'])) {
	$_dbq = db_query("SELECT * FROM ".$whoamis." WHERE id = '".$_vars['id']."' limit 1");
	$dbq = db_fetch_assoc($_dbq);

	db_query("UPDATE site_settings SET galleries_id = 'gallery_".$_vars['id']."' WHERE id = '1'");

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

					<form method="POST" action="<?= $whoamis ?>-update.php" id="thisform" name="thisform">

					<div class="field_title">Image Gallery Title</div>

					<div class="field_copy">This is the title that displays above the image gallery.</div>

					<div class="field_box">
						<input type="text" name="name" value="<?= $dbq['name'] ?>" class="normal" style="width:500px;" tabindex=<?php pv($ti); $ti++; ?>>
					</div>

					<br>

					<div class="field_title">Status</div>

					<div class="field_copy">Show to the public, Hide from the public, Delete.</div>

					<div class="field_box">
						<?php if (is_empty($dbq['status'])) { $dbq['status'] = 'show'; } ?>
						<select name="status" class="normal" tabindex=<?php pv($ti); $ti++; ?> >
							<option value="show" <?php if ($dbq['status'] == 'show') { echo"selected"; } ?>>Display</option>
							<option value="hide" <?php if ($dbq['status'] == 'hide') { echo"selected"; } ?>>Hide</option>
							<option value="delete" <?php if ($dbq['status'] == 'archive') { echo"selected"; } ?>>Delete</option>
						</select>

					</div>

					<br>

					<div class="field_title">Upload Images</div>

					<div class="field_copy">If this is a new gallery, save the form with the Image Gallery Title filled and once the page returns you can add images.
					</div>

					<div class="field_box">

						<?php if (!is_empty($_vars['id'])) { ?>

							<?php
							$_dbx = db_query("SELECT * FROM upload WHERE gallery_id = '".$_vars['id']."'");
							if (db_num_rows($_dbx)>0) {

								while($dbx = db_fetch_assoc($_dbx)){ $images[] = $dbx; } //pre($images); ?>

								<div id="galleria-2" style="width:415px;">

									<?php
									for ($i = 0; $i < count($images); $i++) { ?>

										<a href="<?= HOST ?>/images/galleries/<?= $images[$i]['lrg_rename'] ?>">
											<img
												title="<?= $images[$i]['lrg_rename'] ?>"
												alt=""
												src="<?= HOST ?>/images/galleries/<?= $images[$i]['lrg_rename'] ?>">
										</a>

									<?php } ?>

								</div>
								<div id="" class="" style="font-size:11px;">** added or deleted images will reflect after a page reload.</div>

								<script>

								// Initialize Galleria
								$('#galleria-2').galleria();

								</script>

								<br>

								<ul style="margin:0;padding:0;">

								<?php
								for ($i = 0; $i < count($images); $i++) { ?>

									<li id="line-<?= $images[$i]['upload_id'] ?>" style="margin:5px 0;"><a href="javascript:void(0);" onMouseUp="remove('<?= $images[$i]['upload_id'] ?>')">delete</a>&nbsp;-&nbsp;<?= $images[$i]['lrg_rename'] ?></li>

								<?php } ?>

								</ul>

								<script type="text/javascript">
								<!--
								function remove(id) {

									$.ajax({
										type: "POST",
										url: "../../standards/ajax.php",
										data: 'remove=' + id,
										success: function(msg){

											$("#line-" + id).remove();

										}
									});

									return false;

								}
								//-->
								</script>

								<br>

							<?php } ?>

							<input id="file_upload" name="file_upload" type="file" />
							<div id="status-message"></div>

						<?php } ?>

					</div>

					<script type="text/javascript">
					$(document).ready(function() {
						$('#file_upload').uploadify({
							'uploader'  : '<?= HOST ?>/standards/classes/uploadify/uploadify.swf',
							'script'    : '<?= HOST ?>/standards/classes/uploadify/uploadify.php',
							'cancelImg' : '<?= HOST ?>/standards/classes/uploadify/cancel.png',
							'folder'    : '<?= HOST ?>/images/galleries',
							'multi'     : true,
							'fileExt'   : '*.jpg;*.gif;*.png',
							'fileDesc'  : 'Image Files (.JPG, .GIF, .PNG)',
							'auto'      : true,
							'simUploadLimit' : 3,
							'removeCompleted': false,
							'onSelectOnce'   : function(event,data) {
								$('#status-message').text(data.filesSelected + ' files have been added to the queue.');
							},
							'onAllComplete'  : function(event,data) {
								$('#status-message').text(data.filesUploaded + ' files uploaded, ' + data.errors + ' errors.');
							}

						});
					});
					</script>

					<br>

					<hr class="normal">

					<div style="float:right;"><button type="submit" class="buttons">Submit</button></div>

					<br>

					<input type="hidden" name="process" value="1">
					<input type="hidden" name="id" value="<?= $dbq['id']; ?>">
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