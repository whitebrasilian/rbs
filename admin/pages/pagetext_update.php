<?php
include("../../starter.php");
require_login();
priv_level(0);

if($_vars['status']=='delete') {

	db_query("DELETE FROM website_text WHERE PageID = '".$_vars['id']."'",0,0);
	$outcome = "Deleted";
	header("Location:index.php?complete=".$outcome);
	die;

}

if(!is_empty($_vars['PageTitle'])) {

	/*   Spaw2 bug fix - 9/12/2012   */
	$_vars['PageText'] = preg_replace('{^(<br(\s*/)?>|ANDnbsp;)+}i', '', $_vars['PageText']);
	$_vars['PageText'] = preg_replace('{(<br(\s*/)?>|ANDnbsp;)+$}i', '', $_vars['PageText']);

	if (is_empty($_vars['id'])) {

		db_query("INSERT INTO website_text (PageName, PageTitle, PageText, PageMetaKeywords, PageMetaDesc, PageLink, type, gallery_id, video_id, content_block) VALUES ('".mrClean($_vars['PageName'])."', '".mrClean($_vars['PageTitle'])."', '".mrClean($_vars['PageText'])."', '".mrClean($_vars['PageMetaKeywords'])."', '".mrClean($_vars['PageMetaDesc'])."', '".$_vars['PageLink']."', '".$_vars['type']."', '".$_vars['gallery_id']."', '".$_vars['video_id']."', '".$_vars['content_block']."')");
		$_vars['id'] = db_insert_id();
		$outcome = "Added";

	}else{

		if (!is_empty($_vars['PageName'])) { $clause = "PageName = '".mrClean($_vars['PageName'])."',"; }

		db_query("UPDATE website_text SET ".$clause." PageTitle = '".mrClean($_vars['PageTitle'])."', PageText = '".mrClean($_vars['PageText'])."', PageMetaKeywords = '".mrClean($_vars['PageMetaKeywords'])."', PageMetaDesc = '".mrClean($_vars['PageMetaDesc'])."', PageLink = '".$_vars['PageLink']."', type = '".$_vars['type']."', gallery_id = '".$_vars['gallery_id']."', video_id = '".$_vars['video_id']."', content_block = '".$_vars['content_block']."' WHERE PageID = '".$_vars['id']."'");
		$outcome = "Edited";

	}

	header("Location:pagetext_update.php?complete=".$outcome."&id=".$_vars[id]);
}

if (!is_empty($_vars['id'])) {
	$_qdb = db_query("SELECT * FROM website_text WHERE PageID = '".$_vars['id']."' limit 1");
	$qdb = db_fetch_array($_qdb);
}else{ $qdb=""; }

$PageText = GetPageText("Edit Page");
include($CFG->baseroot."/admin/cms-header.php");
?>

<!-- <?php if (!is_empty($_GET['complete'])) { ?>

	<div id="facebox">

		<div class="facebox_border">

			<?php $copy = GetPageText("Page Text  Successfully ".$_GET['complete']); ?>

			<div ><h2 class="faceboxh2"><? pv($copy->PageTitle); ?></h2></div>
			<div style="margin:10px 0 15px 0; font-size:13px;"><? pv($copy->PageText); ?></div>
			<div style="color:#666; font-size:10px; float:left; padding-top:10px;">To close, click the Close button or hit the ESC key.</div>
			<div style="float:right;"><button class="close"> Close </button></div>
			<br class="clear">

		</div>

	</div>

<?php } ?> -->

<div id="mainbar">

	<div class="blur">
		<div class="shadow">
			<div class="content">

				<div id="content_header">

					<div id="content_title"><?= $PageText->PageTitle ?></div>

					<? //build_jump_to_category_tree($_vars['category_list']); ?>

					<select class="normal" name="id" onchange="window.open(this.options[this.selectedIndex].value,'_self');this.selectedIndex=0;" style="float:right;">
						<option value=" ">Jump to Page</option>
						<?php
						$_jump = db_query("SELECT * FROM website_text WHERE type = 'page' ORDER BY type DESC, PageName ASC");
						while($jump = db_fetch_array($_jump)) {

							if ($jump[PageName] == "Home") { $jump[PageName] = $jump[PageName]." - ".$jump[content_block]; }

							echo "<option value=\"pagetext_update.php?id=".$jump[PageID]."\">".$jump[PageName]."</option>";
						} ?>
					</select>

					<br class="clear">

				</div>

				<div id="content_body">

					<form method="POST" action="pagetext_update.php" id="thisform" name="thisform">

					<div class="field_title">Page Name *</div>

					<div class="field_copy">This is the name of the page and also whats show in the page title. This is only updateable by the webmaster.</div>

					<div class="field_box">
						<input type="text" name="PageName" value="<? pv($qdb[PageName]) ?>" class="normal" tabindex=<?php pv($ti); $ti++; ?> required="required" style="width:500px;" disabled>
					</div>

					<br>

					<div class="field_title">Content Block *</div>

					<div class="field_copy">Only required for the home page.</div>

					<div class="field_box">
						<input type="text" name="content_block" value="<? pv($qdb[content_block]) ?>" class="normal" tabindex=<?php pv($ti); $ti++; ?> style="width:500px;">
					</div>

					<br>

					<div class="field_title">Content Title *</div>

					<div class="field_copy">This is the title of the section. It is found just above the main body of copy.</div>

					<div class="field_box">
						<input type="text" name="PageTitle" value="<? pv($qdb[PageTitle]) ?>" class="normal" tabindex=<?php pv($ti); $ti++; ?> style="width:500px;">
					</div>

					<br>

					<div class="field_title">Content</div>

					<div class="field_copy">The is the main body copy. For best results, type directly into the editor. DO NOT copy & paste data from a Word style application without having pasted it into a Notepad style application first. If this warning is not followed the page will look really messed up! It is strongly suggested that you make updates using the Firefox browser. Other browsers tend not to place nice.</div>

					<div class="field_box">

						<!--<textarea name="PageText" class="normal wysiwyg" style="height:300px;"  tabindex=<?php pv($ti); $ti++; ?>><?= prep_var($qdb['PageText'],1) ?></textarea>-->

						<?php
						include("../../standards/spaw2/spaw.inc.php");
						$spaw1 = new SpawEditor("PageText", prep_var($qdb['PageText']));
						$spaw1->show();
						?>

					</div>

					<br>

					<div class="field_title">Image Gallery</div>

					<div class="field_copy">Select a gallery that has been pre-made in the Image Galleries Section. </div>

					<div class="field_box">
						<select name="gallery_id" class="normal" tabindex=<?php pv($ti); $ti++; ?> style="float:left; width:300px;">
							<option value=""></option>

							<?php
							$_qdx = db_query("SELECT * FROM galleries WHERE status = 'show' ORDER BY name ASC");
							while ($qdx = db_fetch_assoc($_qdx)){ ?>

								<option value="<?= $qdx['id'] ?>" <?php if ($qdx['id'] == $qdb['gallery_id']) { echo "selected"; } ?>><?= $qdx['name'] ?></option>

							<?php } ?>

						</select>

						<?php if (!is_empty($qdb['gallery_id'])) { ?>

							<a href="../galleries/galleries-update.php?id=<?= $qdb['gallery_id'] ?>" style="float:left; margin:3px 0 0 20px;">Edit Gallery</a>

						<?php } ?>

						<br class="clear">

						<?php if (!is_empty($qdb['gallery_id'])) { ?>

							<br>

							<div id="galleria-2" style="width:415px;">

								<?php
								$_dbq = db_query("SELECT * FROM upload WHERE gallery_id = '".$qdb['gallery_id']."'");
								while($dbq = db_fetch_assoc($_dbq)){ ?>

									<a href="<?= HOST ?>/images/galleries/<?= $dbq['lrg_rename'] ?>">
										<img
											title=""
											alt=""
											src="<?= HOST ?>/images/galleries/<?= $dbq['lrg_rename'] ?>">
									</a>

								<?php } ?>

							</div>

							<script>

							// Initialize Galleria
							$('#galleria-2').galleria();

							</script>

						<?php } ?>

					</div>

					<br>

					<div class="field_title">Video</div>

					<div class="field_copy">Select a video that has been added in the Videos section.</div>

					<div class="field_box">
						<select name="video_id" class="normal" tabindex=<?php pv($ti); $ti++; ?> style="float:left; width:300px;">
							<option value=""></option>

							<?php
							$_qdx = db_query("SELECT * FROM videos WHERE 1 ORDER BY name ASC");
							while ($qdx = db_fetch_assoc($_qdx)){ ?>

								<option value="<?= $qdx['id'] ?>" <?php if ($qdx['id'] == $qdb['video_id']) { echo "selected"; } ?>><?= $qdx['name'] ?></option>

							<?php } ?>

						</select>

						<?php if (!is_empty($qdb['video_id'])) { ?>

							<a href="../videos/videos-update.php?id=<?= $qdb['video_id'] ?>" style="float:left; margin:3px 0 0 20px;">Edit Video</a>

						<?php } ?>

						<br class="clear">

						<?php
						if (!is_empty($qdb['video_id'])) {

							$_qdy = db_query("SELECT embed_code FROM videos WHERE id = '".$qdb['video_id']."' limit 1");
							$qdy = db_fetch_assoc($_qdy);

							echo '<br><iframe width="423" height="270" src="http://www.youtube.com/embed/'.$qdy['embed_code'].'" frameborder="0" allowfullscreen></iframe>';

						} ?>

					</div>

					<br>

					<div class="field_title">Page Type</div>

					<div class="field_copy">Do not change this</div>

					<div class="field_box">
						<?php if (is_empty($qdb['type'])) { $qdb['type'] = 'page'; } ?>
						<select name="type" class="normal" tabindex=<?php pv($ti); $ti++; ?> >
							<option value="page" <?php if ($qdb['type'] == 'page') { echo"selected"; } ?>>Client Side</option>
							<option value="manager" <?php if ($qdb['type'] == 'manager') { echo"selected"; } ?>>Manager</option>
							<option value="facebox" <?php if ($qdb['type'] == 'facebox') { echo"selected"; } ?>>Notifications</option>
							<option value="email" <?php if ($qdb['type'] == 'email') { echo"selected"; } ?>>Emails</option>
						</select>
					</div>

					<br>

					<div class="field_title">Status</div>

					<div class="field_copy">Set this to Delete if you want to PERMANENTLY remove the page.</div>

					<div class="field_box">
						<?php if (is_empty($user['status'])) { $user['status'] = 'show'; } ?>
						<select name="status" class="normal" tabindex=<?php pv($ti); $ti++; ?> >
							<option value="show" <?php if ($user['status'] == 'show') { echo"selected"; } ?>>Show</option>
							<option value="delete" <?php if ($user['status'] == 'delete') { echo"selected"; } ?>>Delete</option>
						</select>
					</div>

					<br>

					<input type="hidden" name="id" value="<? pv($qdb['PageID']); ?>">

					<hr class="normal">

					<div style="float:right;"><button type="submit" class="buttons">Submit</button></div>

					<br>

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