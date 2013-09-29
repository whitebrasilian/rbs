<?php
include("../../starter.php");
require_login();
priv_level(1);

$PageText = GetPageText("Members List");
include($CFG->baseroot."/admin/cms-header.php");
?>

<div id="mainbar">

	<div class="blur">
		<div class="shadow">
			<div class="content">

				<div id="content_header">

					<div id="content_title"><? pv($PageText->PageTitle) ?></div>

					<?php
					if (is_empty($_vars['sort'])) { $_vars['sort']='username'; }
					//if (is_empty($_vars['display'])) { $_vars['display']='member'; }
					?>

					<select class="normal" name="id" onchange="window.open(this.options[this.selectedIndex].value,'_self');this.selectedIndex=0;" style="float:right;">
						<option value=" ">Jump to Privilege</option>
						<option value="index.php?display=admin" <?php if ($_vars['display'] == 'admin') { echo"selected"; } ?>>Admin</option>
						<option value="index.php?display=member" <?php if ($_vars['display'] == 'member') { echo"selected"; } ?>>Member</option>
						<option value="index.php?display=not-activated" <?php if ($_vars['display'] == 'not-activated') { echo"selected"; } ?>>Not Activated</option>
						<option value="index.php?display=" <?php if ($_vars['display'] == '') { echo"selected"; } ?>>All</option>
					</select>

					<style media="screen" type="text/css">
						.defaultText { width: 300px; float:right; margin-right:5px; }
						.defaultTextActive { color: #a1a1a1; font-style: italic; }
					</style>

					<script language="javascript">
					<!--
					$(document).ready(function()
					{
						$(".defaultText").focus(function(srcc)
						{
							if ($(this).val() == $(this)[0].title)
							{
								$(this).removeClass("defaultTextActive");
								$(this).val("");
							}
						});

						$(".defaultText").blur(function()
						{
							if ($(this).val() == "")
							{
								$(this).addClass("defaultTextActive");
								$(this).val($(this)[0].title);
							}
						});

						$(".defaultText").blur();
					});
					//-->
					</script>

					<form method="POST" action="index.php" name="search">

						<input type="submit" name="" value="Search" class="" style="width:100px; float:right; margin-right:30px;">

						<input type="text" id="" name="user_search" value="" class="defaultText" tabindex=<?php pv($ti); $ti++; ?> style="width:250px; float:right; margin-right:5px;" title="Search by Name, Username or Email">

					</form>

					<br class="clear">

				</div>

				<div id="content_body">

					<div id="" class="" style="">Search Alphabetically by Username</div>

					<?php
					$AlphaLinks = "<div class='pagez'><a class='pblock' href='index.php?letter=1&display=".$_vars['display']."' style='text-decoration:none;'>#</a></div>";

					$Alphabet = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
					$letterCount = 0;
					while(list($key, $letter) = each($Alphabet)) {
						$AlphaLinks .= "<div class='pagez'><a class='pblock' href='index.php?letter=".$letter."&display=".$_vars['display']."' style='text-decoration:none;'>".strtoupper($letter)."</a></div>";
						$letterCount++;
					}
					echo $AlphaLinks;
					?>

					<br class="clear">
					<br>

					<div style="padding:5px; margin-bottom:2px; background:#E5E5E5;">

						<div style="float:left; width:100px;"><b><a href="index.php?display=<?= $_vars['display'] ?>&sort=first_name" style="text-decoration:none;">Username</a></b></div>
						<div style="float:left; width:265px;"><b><a href="index.php?display=<?= $_vars['display'] ?>&sort=email" style="text-decoration:none;">Email</a></b></div>
						<div style="float:left; width:90px;"><b><a href="index.php?display=<?= $_vars['display'] ?>&sort=first_name" style="text-decoration:none;">First Name</a></b></div>
						<div style="float:left; width:90px;"><b><a href="index.php?display=<?= $_vars['display'] ?>&sort=last_name" style="text-decoration:none;">Last Name</a></b></div>
						<div style="float:left; width:70px;"><b><a href="index.php?display=<?= $_vars['display'] ?>&sort=priv" style="text-decoration:none;">Privilege</a></b></div>
						<br class="clear">

					</div>

					<?php
					$x=1;

					$per_page = 50;
					if (is_empty($_vars['page'])) { $_vars['page']=1; }
					$start = ($_vars['page']-1)*$per_page;

					if (!is_empty($_vars['user_search'])) {

						$aclause = "";

						$clause = " AND (first_name LIKE '%".$_vars['user_search']."%' OR last_name LIKE '%".$_vars['user_search']."%' OR email LIKE '%".$_vars['user_search']."%' OR username LIKE '%".$_vars['user_search']."%')";

					}else{

						if (!is_empty($_vars['letter'])) {

							if ($_vars['letter'] == 1) {

								$clause .= "AND (";

								for ($i = 0; $i < 10; $i++) {

									if ($i > 0) { $clause .= " OR "; }
									$clause .= $_vars['sort']." LIKE '".$i."%'";

								}

								$clause .= ")";

							}else{

								$clause = "AND ".$_vars['sort']." LIKE '".$_vars['letter']."%'";

							}

						}

						if (is_empty($_vars['display'])) {

							$aclause = "";

						}elseif ($_vars['display']=='not-activated') {

							$aclause = "AND activate_date = ''";

						}else{

							$aclause = "AND priv = '".$_vars['display']."'";

						}

					}

					$_qdb = db_query("
					SELECT id, first_name, last_name, priv, email, username
					FROM users
					WHERE status != 'archive' ".$aclause." ".$clause."
					ORDER BY ".$_vars['sort']." ASC
					");
					$pages = ceil(db_num_rows($_qdb)/$per_page);

					$_qdb = db_query("
					SELECT id, first_name, last_name, priv, email, username
					FROM users
					WHERE status != 'archive' ".$aclause." ".$clause."
					ORDER BY ".$_vars['sort']." ASC
					limit ".$start.",".$per_page."
					",0,0);
					while ($row = db_fetch_array($_qdb)){

						//pre($row);

						if (!is_empty($row['first_name']) || !is_empty($row['last_name']) || !is_empty($row['email'])) {

							if ($x == 1){ $x=0; $color="background:#F0F0F0;"; }else{ $x=1; $color="background:#E5E5E5;"; } ?>

							<div style=" margin-bottom:2px;">

								<div style="<?= $color ?> width:100px; float:left;">

									<a class="pblock" href="user_update.php?id=<? pv($row[id]) ?>" title="" style="text-decoration:none;">

										<?php if (!is_empty($row[username])) { ?>
											<?= $row['username'] ?>&nbsp;
										<?php }else{ ?>
											Unknown
										<?php } ?>

									</a>

								</div>

								<div style="<?= $color ?> width:275px; float:left;">

									<a class="pblock" href="user_update.php?id=<? pv($row[id]) ?>" title="" style="text-decoration:none;">

										<?php if (!is_empty($row[email])) { ?>
											<?= $row['email'] ?>&nbsp;
										<?php }else{ ?>
											Unknown
										<?php } ?>

									</a>

								</div>

								<div style="<?= $color ?> width:90px; float:left;">

									<a class="pblock" href="user_update.php?id=<? pv($row[id]) ?>" title="" style="text-decoration:none;">

										<?php if (!is_empty($row[first_name])) {
											echo ucwords(strtolower($row[first_name]));
										}else{ ?>
											Unknown
										<?php } ?>

									</a>

								</div>

								<div style="<?= $color ?> width:90px; float:left;">

									<a class="pblock" href="user_update.php?id=<? pv($row[id]) ?>" title="" style="text-decoration:none;">

										<?php if (!empty($row[last_name])) {
											echo ucwords(strtolower($row[last_name]));
										}else{ ?>
											Unknown
										<?php } ?>

									</a>

								</div>

								<div style="<?= $color ?> width:70px; float:left;">

									<a class="pblock" href="user_update.php?id=<? pv($row[id]) ?>" title="" style="text-decoration:none;">

										<?= $row['priv'] ?>

									</a>

								</div>

								<br class="clear">

							</div>

						<?php } ?>

					<?php } ?>

					<?php if ($pages > 1) { ?>

						<div id="paging_button">
							<?php for($i=1; $i<=$pages; $i++){ ?><div <?php if ($_vars['page'] == $i) { ?>style="background-color:#CCCCCC;"<?php } ?>><a href="index.php?letter=<?= $_vars['letter'] ?>&sort=<?= $_vars['sort'] ?>&page=<?= $i ?>" class="forum_page" style="text-decoration:none;"><?= $i ?></a></div><?php } ?>
							<br class="clear">
						</div>

					<?php } ?>

				</div>

			</div>
		</div>
	</div>

	<br>

</div>

<div class="sidebar">

	<div class="sidebar_header">Users Navigation</div>
	<div class="sidebar_content">

		<ul>
			<li><a class="nblock" href="user_update.php">Add a User</a></li>
		</ul>

	</div>

	<?php include('../user-info.php'); ?>

</div>

<br class="clear">

<? include($CFG->baseroot."/admin/cms-footer.php"); ?>