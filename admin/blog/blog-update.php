<?php
include("../../starter.php");
require_login();
priv_level(0);

if($_vars['status'] == "delete") {

	db_query("DELETE FROM blog WHERE id = '".$_vars['id']."'",1,0);
	db_query("DELETE FROM blog_link WHERE blog_id = '".$_vars['id']."'",1,0);

	//$outcome = "Deleted";

	header("Location:index.php");
	die;

}

if (!is_empty($_vars['title'])) {

	/*   Spaw2 bug fix - 9/12/2012   */
	$_vars['content'] = preg_replace('{^(<br(\s*/)?>|ANDnbsp;)+}i', '', $_vars['content']);
	$_vars['content'] = preg_replace('{(<br(\s*/)?>|ANDnbsp;)+$}i', '', $_vars['content']);

	if(is_empty($_vars['id'])) {

		/* add data to table table */
		db_query("INSERT INTO blog (title, content, datetime, status, video_id, gallery_id, author) VALUES ('".mrClean($_vars['title'])."', '".mrClean($_vars['content'])."', NOW(), '".$_vars['status']."', '".$_vars['video_id']."', '".$_vars['gallery_id']."', '".$_vars['author']."')",0,0);

		$outcome = "Added";

		/* get the id that was just created */
		$_vars['id'] = db_insert_id();

	} else {

		/* update the table with the new information */
		db_query("UPDATE blog SET title = '".mrClean($_vars['title'])."', content = '".mrClean($_vars['content'])."', video_id = '".$_vars['video_id']."', gallery_id = '".$_vars['gallery_id']."', status = '".$_vars['status']."', author = '".$_vars['author']."' WHERE id = '".$_vars['id']."'");

		$outcome = "Edited";

	}

	//db_query("DELETE FROM blog WHERE parent_id = '".$_vars['id']."'");

	db_query("DELETE FROM blog_link WHERE blog_id = '".$_vars['id']."'");

	for ($i = 0; $i < count($_vars['section_id']); $i++) {

		db_query("INSERT INTO blog_link (blog_id, section_id) VALUES ('".$_vars['id']."', '".$_vars['section_id'][$i]."')",0,0);

	}

	$dloc = BLOG_UPLOADS;
	$ref = 'blog';
	include('save-file.php');

	//header("Location:blog.php?complete=".$outcome."&id=".$_vars[id]);
	header("Location:blog-update.php?id=".$_vars['id']);

}

if (!is_empty($_vars['id'])) {
	$_dbq = db_query("SELECT * FROM blog WHERE id = '".$_vars['id']."' limit 1");
	$dbq = db_fetch_assoc($_dbq);

}else{ $dbq=""; }

$PageText = GetPageText("Blog");
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

					<form method="POST" action="blog-update.php" id="thisform" name="thisform" enctype="multipart/form-data" onSubmit="return checkFields();">

						<div class="field_title">Blog Tags *</div>

						<div class="field_copy">Choose one or more tags to place this post in. Use CTRL+Click (PC) or Apple+Click (Mac) to select multiple categories.</div>

						<div class="field_box">

							<select id="section_id" name="section_id[]" tabindex=<?php pv($ti); $ti++; ?> style="width:200px; margin-right:10px;" class="normal lfloat" multiple>

								<?php pre($section);
								$_section = db_query("SELECT section_id FROM blog_link WHERE blog_id = '".$_vars['id']."' GROUP BY section_id");
								while($section = db_fetch_assoc($_section)){ $sections[] = $section['section_id']; }

								$_sec = db_query("SELECT id, section FROM blog_section WHERE 1 ORDER BY section ASC");
								while($sec = db_fetch_assoc($_sec)){ ?>

									<option value="<?= $sec['id'] ?>" <?php if (in_array($sec['id'],$sections)) { echo"selected"; } ?>><?= $sec['section'] ?></option>

								<?php } ?>

							</select>

							<br class="clear">

						</div>

						<br>

						<div class="field_title">Post Title *</div>

						<div class="field_copy">The keyword rich title of your post.</div>

						<div class="field_box">
							<input type="text" name="title" value="<? pv($dbq['title']) ?>" class="normal_sm" tabindex=<?php pv($ti); $ti++; ?> style="width:500px;">
						</div>

						<br>

						<div class="field_title">Post Author *</div>

						<div class="field_copy">The name of the writer. This field has an auto complete feature that will show names of previous author after a few letters are written. Select the name of the author if found. Otherwise just continue and it will be added as new.</div>

						<div class="field_box">
							<input type="text" id="author" name="author" value="<? pv($dbq['author']) ?>" class="normal_sm" tabindex=<?php pv($ti); $ti++; ?> style="">
						</div>

						<br>

						<script language="Javascript">
						<!--

						/*   ****************************************************   */
						/*   http://docs.jquery.com/Plugins/autocomplete - 9/20/2010	  */
						/*   ****************************************************   */

						$(function() {

							function formatItem(row) {
								return row[0] + " (<strong>id: " + row[1] + "</strong>)";
							}
							function formatResult(row) {
								return row[0].replace(/(<.+?>)/gi, '');
							}

							$("#author").autocomplete('auto-complete.php', {
								width: 300,
								matchContains: true,
								formatItem: formatItem,
								formatResult: formatResult
							});
							$("#acm").result(function(event, data, formatted) {
								var hidden = $(this).parent().next().find(">:input");
								hidden.val( (hidden.val() ? hidden.val() + ";" : hidden.val()) + data[1]);
							});

						});

						//-->
						</script>

						<div class="field_title">Content *</div>

						<div class="field_copy">The is the main body copy. DO NOT copy & paste data without having pasted it into a notepad editor first. If this warning is not followed the page will look really messed up! Images inserted into post MUST BE resized before adding.</div>

						<div class="field_box">
							<!--<textarea name="content" class="normal wysiwyg" style="height:350px;" tabindex=<?php pv($ti); $ti++; ?>><?= $dbq['content'] ?></textarea>-->

							<?php
							include("../../standards/spaw2/spaw.inc.php");
							$spaw1 = new SpawEditor("content", prep_var($dbq['content']));
							$spaw1->show();
							?>

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

									<option value="<?= $qdx['id'] ?>" <?php if ($qdx['id'] == $dbq['video_id']) { echo "selected"; } ?>><?= $qdx['name'] ?></option>

								<?php } ?>

							</select>

							<?php if (!is_empty($dbq['video_id'])) { ?>

								<a href="../videos/videos-update.php?id=<?= $dbq['video_id'] ?>" style="float:left; margin:3px 0 0 20px;">Edit Video</a>

							<?php } ?>

							<br class="clear">

							<?php
							if (!is_empty($dbq['video_id'])) {

								$_qdy = db_query("SELECT embed_code FROM videos WHERE id = '".$dbq['video_id']."' limit 1");
								$qdy = db_fetch_assoc($_qdy);

								echo '<br><iframe width="423" height="270" src="http://www.youtube.com/embed/'.$qdy['embed_code'].'" frameborder="0" allowfullscreen></iframe>';

							} ?>

						</div>

						<br>

						<div class="field_title">Image Gallery</div>

						<div class="field_copy">Select a gallery that has been pre-made in the Image Galleries Section.</div>

						<div class="field_box">
							<select name="gallery_id" class="normal" tabindex=<?php pv($ti); $ti++; ?> style="float:left; width:300px;">
								<option value=""></option>

								<?php
								$_qdx = db_query("SELECT * FROM galleries WHERE status = 'show' ORDER BY name ASC");
								while ($qdx = db_fetch_assoc($_qdx)){ ?>

									<option value="<?= $qdx['id'] ?>" <?php if ($qdx['id'] == $dbq['gallery_id']) { echo "selected"; } ?>><?= $qdx['name'] ?></option>

								<?php } ?>

							</select>

							<?php if (!is_empty($dbq['gallery_id'])) { ?>

								<a href="../galleries/galleries-update.php?id=<?= $dbq['gallery_id'] ?>" style="float:left; margin:3px 0 0 20px;">Edit Gallery</a>

							<?php } ?>

							<br class="clear">

							<?php if (!is_empty($dbq['gallery_id'])) { ?>

								<br>

								<div id="galleria-2" style="width:415px;">

									<?php
									$_dby = db_query("SELECT * FROM upload WHERE gallery_id = '".$dbq['gallery_id']."'");
									while($dby = db_fetch_assoc($_dby)){ ?>

										<a href="<?= HOST ?>/images/galleries/<?= $dby['lrg_rename'] ?>">
											<img
												title=""
												alt=""
												src="<?= HOST ?>/images/galleries/<?= $dby['lrg_rename'] ?>">
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

						<br>

						<br>&nbsp;<br>

						<hr class="normal">
						<br>

						<button type="submit" class="buttons" class="lfloat">Save</button>

						<br>

					<input type="hidden" name="id" value="<?= $dbq['id']; ?>">
					</form>

					<script type="text/javascript">
					<!--
					function GetMDDselections() {
						var pcnt = 0;
						for (i=0; i<document.getElementById('section_id').options.length; i++) {
							if (document.getElementById('section_id').options[i].selected == true) { pcnt++; }
						}
						return pcnt;
					}

					function checkFields(){
						var errormsg = "";
						if (GetMDDselections() == 0) { var errormsg = errormsg + "Please select at least one blog category.\n\r"; }
						if (document.thisform.title.value == "") { var errormsg = errormsg + "Please enter a post title.\n\r"; }
						if (document.thisform.content.value == "") { var errormsg = errormsg + "Please enter some post content.\n\r"; }

						if (errormsg != "") { alert(errormsg); return false; }else { document.thisform.submit(); }
					}

					// -->
					</script>


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