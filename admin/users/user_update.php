<?php
include("../../starter.php");
require_login();
priv_level(1);

$membership_levels = array('recruit','private','lieutenant','captain','colonel','lgeneral','general');
$membership_prices = array('Recruit - Free','Private - $10','Lieutenant - $25','Captain - $50','Colonel - $100','Lt. General - $250','General - $1000');
$member_price = array(0, 10, 25, 50, 100, 250, 1000);

/*   delete product & images   */
if (!is_empty($_vars['del'])) {

	$_qdb = db_query("SELECT lrg_rename, med_rename, sml_rename FROM upload WHERE upload_id = '".$_vars['del']."' limit 1");
	$qdb = db_fetch_array($_qdb);

	unlink(USERS_UPLOADS.'/'.$qdb[lrg_rename]);

	db_query("DELETE FROM upload WHERE upload_id = '".$_vars['del']."'");
	db_query("DELETE FROM connector_upload WHERE upload_id = '".$_vars['del']."' AND ref = 'users' AND ref_id = '".$_vars['id']."'");

	header("Location:user_update.php?complete=Deleted&id=".$_vars['id']);
	die;

}

if (!is_empty($_vars['crop_id'])) { //pre($_vars,1);

	$_upload = db_query("
	SELECT
	upload.lrg_rename,
	upload.upload_id
	FROM
	upload
	Inner Join connector_upload ON upload.upload_id = connector_upload.upload_id
	WHERE
	connector_upload.ref =  'users' AND
	connector_upload.ref_id =  '".$_vars['crop_id']."'
	limit 1
	");
	$upload = db_fetch_array($_upload);

	$width = 170;
	$height = 226;

	header('Content-type: image/jpeg');

	$filename = USERS_UPLOADS.'/'.$upload['lrg_rename'];

	$image_p = imagecreatetruecolor($width, $height);
	$image = imagecreatefromjpeg($filename);
	imagecopyresampled($image_p, $image, 0, 0, $_vars['x'], $_vars['y'], $width, $height, $_vars['w'],$_vars['h']);
	imagejpeg($image_p, $filename, 90);

	//db_query("UPDATE upload SET file_display_image = '1' WHERE upload_id = '".$upload['upload_id']."'");

	header("Location:user_update.php?complete=Cropped&id=".$_vars['crop_id']);
	die;

}

if (!is_empty($_vars['email'])) { //pre($_vars,1);

	$latlon = latlon_from_address($address, $_vars[b_zip]);

	$_vars['exp_date'] = $_vars['cc_month'].substr($_vars['cc_year'],2);

	if ($_vars['priv']=='free') { $_vars['rank'] = 'recruit'; }

	if(is_empty($_vars['id'])){

		db_query("INSERT INTO users (password, priv, username, first_name, last_name, b_address, b_address2, b_city, b_state, b_zip, b_country, s_address, s_address2, s_city, s_state, s_zip, s_country, email, phone, phone2, fax, newsletter, discount, comments, status, activate_date, creation_date, activate_key, lat, lon) VALUES ('" . md5($_vars[password1]) ."','".$_vars[priv]."','".mrClean($_vars[username])."','".mrClean($_vars[first_name])."','".mrClean($_vars[last_name])."','".mrClean($_vars[b_address])."','".mrClean($_vars[b_address2])."','".mrClean($_vars[b_city])."','".$_vars[b_state]."','".mrClean($_vars[b_zip])."','".$_vars[b_country]."','".$_vars[s_address]."','".$_vars[s_address2]."','".$_vars[s_city]."','".$_vars[s_state]."','".$_vars[s_zip]."','".$_vars[s_country]."','".$_vars[email]."','".$_vars[phone]."','".$_vars[phone2]."','".$_vars[fax]."','".$_vars[newsletter]."','".$_vars[discount]."','".$_vars[comments]."','".$_vars[status]."', '".$_vars[activate_date]."', NOW(), '".get_rand_id(16)."', '".$latlon['lat']."', '".$latlon['lon']."')");
		$_vars[id] = db_insert_id();
		$outcome = "Added&id=".$_vars[id];

	}else{

		if(!is_empty($_vars[password1]) && ($_vars[password1] == $_vars[password2])){
			db_query("UPDATE users SET password='" . md5($_vars[password1]) ."' WHERE id = '".$_vars[id]."'");
		}

		$qdb = db_query("UPDATE users SET priv = '".$_vars[priv]."', username = '".mrClean($_vars[username])."', first_name = '".mrClean($_vars[first_name])."', last_name = '".mrClean($_vars[last_name])."', b_address = '".mrClean($_vars[b_address])."', b_address2 = '".mrClean($_vars[b_address2])."', b_city = '".mrClean($_vars[b_city])."', b_state = '".$_vars[b_state]."', b_zip = '".mrClean($_vars[b_zip])."', b_country = '".$_vars[b_country]."', s_address = '".$_vars[s_address]."', s_address2 = '".$_vars[s_address2]."', s_city = '".$_vars[s_city]."', s_state = '".$_vars[s_state]."', s_zip = '".$_vars[s_zip]."', s_country = '".$_vars[s_country]."', email = '".$_vars[email]."', phone = '".$_vars[phone]."', phone2 = '".$_vars[phone2]."', fax = '".$_vars[fax]."', newsletter = '".$_vars[newsletter]."', discount = '".$_vars[discount]."', comments = '".$_vars[comments]."', status = '".$_vars[status]."', lat = '".$latlon['lat']."', lon = '".$latlon['lon']."' WHERE id = '".$_vars[id]."'");
		$outcome = "Edited&id=".$_vars[id];

	}

	if (!is_empty($_vars['photo_url'])) {

		// http://www.bitrepository.com/download-image.html
		include_once STANDARDS.'/classes/class.get.image.php';

		// initialize the class
		$image = new GetImage;

		// just an image URL
		$image->source = $_vars['photo_url'];
		$image->save_to = USERS_UPLOADS.'/'; // with trailing slash at the end
		//$image->set_extension = true;

		$get = $image->download('curl'); // using GD

		$webfile = str_from_last_occurrence($_vars['photo_url'],'/');
		$filename = basename($webfile);
		$ext = strtolower(substr($filename, strrpos($filename, '.') + 1));
		$strtime = strtotime("now");
		$lrg_rename = $strtime."lrg.".$ext;
		$lrg_path = USERS_UPLOADS."/".$lrg_rename;
		$org_file = USERS_UPLOADS.'/'.$webfile;

		rename($org_file, $lrg_path);

		$size = getimagesize($lrg_path);

		db_query("INSERT INTO upload (file_name, lrg_rename, lrg_height, lrg_width) VALUES ('".str_from_last_occurrence($_vars['photo_url'],'/')."', '".$lrg_rename."', '".$size[1]."', '".$size[0]."')",0,0);

		$temp_id = db_insert_id();

		db_query("INSERT INTO connector_upload (upload_id, ref, ref_id) VALUES ('".$temp_id."', 'users', '".$_vars['id']."')",0,0);

	}else{

		/*   do image manipulation   */
		if (!is_empty($_FILES['photoupload_0']['name'])) { include('save_file.php'); }
		//pre($_FILES,1);

	}

	header("Location:user_update.php?complete=".$outcome);

}

if(!is_empty($_vars['id'])) { $title = "Edit User Details"; }
else { $title = "Add a New User"; }

$qdb = db_query("SELECT * FROM users WHERE id = '".$_vars['id']."'");
$user = db_fetch_array($qdb);


$PageText = GetPageText($title);
include($CFG->baseroot."/admin/cms-header.php");
?>

<?php if (!is_empty($_GET['complete'])) { ?>

	<div id="facebox">

		<div class="facebox_border">

			<?php $copy = GetPageText("User Successfully ".$_GET['complete']); ?>

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

					<div id="content_title"><?= $PageText->PageTitle ?></div>

					<select class="normal" name="id" onchange="window.open(this.options[this.selectedIndex].value,'_self');this.selectedIndex=0;" style="float:right;">
						<option value=" ">Jump to Method</option>
						<option value="index.php?display=admin">Admin</option>
						<option value="index.php?display=memeber">Member</option>
					</select>

				</div>

				<div id="content_body">

					<form method="POST" action="user_update.php" id="thisform" name="thisform" enctype="multipart/form-data">

					<div class="field_title">Username *</div>

					<div class="field_box">
						<input type="text" name="username" value="<? pv($user[username]) ?>" class="normal" tabindex=<?php pv($ti); $ti++; ?> style="margin-bottom:5px;" required="required"><br>
					</div>

					<br>

					<div class="field_title">First & Last Name</div>

					<div class="field_box">
						<input type="text" name="first_name" value="<? pv($user[first_name]) ?>" class="normal" tabindex=<?php pv($ti); $ti++; ?> style="margin-bottom:5px;">&nbsp;&nbsp;<input type="text" name="last_name" value="<? pv($user[last_name]) ?>" class="normal" tabindex=<?php pv($ti); $ti++; ?>> <br>
					</div>

					<br>

					<div class="field_title">Email *</div>

					<div class="field_box">
						<input type="text" name="email" value="<? pv($user[email]) ?>" class="normal" tabindex=<?php pv($ti); $ti++; ?> style="margin-bottom:5px;" required="required"><br>
					</div>

					<br>

					<div class="field_title">Street Address</div>

					<div class="field_box">
						<input type="text" name="b_address" value="<? pv($user[b_address]) ?>" class="normal" tabindex=<?php pv($ti); $ti++; ?> style="margin-bottom:5px;"><br>
						<input type="text" name="b_address2" value="<? pv($user[b_address2]) ?>" class="normal" tabindex=<?php pv($ti); $ti++; ?> >
					</div>

					<br>

					<div class="field_title">City</div>

					<div class="field_box">
						<input type="text" name="b_city" value="<? pv($user[b_city]) ?>" class="normal" tabindex=<?php pv($ti); $ti++; ?> style="margin-bottom:5px;">
					</div>

					<br>

					<div class="field_title">State</div>

					<div class="field_box">
						<select name="b_state" class="normal" tabindex=<?php pv($ti); $ti++; ?>>
							<? StatesFull($user[b_state]) ?>
						</select>
					</div>

					<br>

					<div class="field_title">Postal Code</div>

					<div class="field_box">
						<input type="text" name="b_zip" value="<? pv($user[b_zip]) ?>" class="normal" tabindex=<?php pv($ti); $ti++; ?> style="margin-bottom:5px;">
					</div>

					<br>

					<div class="field_title">Password</div>

					<div class="field_copy">Leave blank if you do not wish to edit.</div>

					<div class="field_box">
						<input type="password" name="password1" value="" class="normal" tabindex=<?php pv($ti); $ti++; ?> style="width:115px;">
						<input type="password" name="password2" value="" class="normal" tabindex=<?php pv($ti); $ti++; ?> style="width:115px;">
					</div>

					<br>

					<div class="field_title">Privileges</div>

					<div class="field_copy">Set access levels for users.</div>

					<div class="field_box">

						<?php if (is_empty($user['priv'])) { $user['priv'] = 'free'; } ?>

						<select name="priv" id="priv" class="normal" tabindex=<?php pv($ti); $ti++; ?>>

							<option value="member" <?php if ($user['priv'] == 'member') { echo"selected"; } ?>>Member</option>
							<option value="admin" <?php if ($user['priv'] == 'admin') { echo"selected"; } ?>>Admin</option>

						</select>

					</div>

					<br>

					<div class="field_title">Status</div>

					<div class="field_copy">An Active user can access the site, an Not-Active user cannot access the site.</div>

					<div class="field_box">
						<?php if (is_empty($user['status'])) { $user['status'] = 'show'; } ?>
						<select name="status" class="normal" tabindex=<?php pv($ti); $ti++; ?> >
							<option value="show" <?php if ($user['status'] == 'show') { echo"selected"; } ?>>Active</option>
							<option value="archive" <?php if ($user['status'] == 'archive') { echo"selected"; } ?>>Not-Active</option>
						</select>
					</div>

					<br>

					<div class="field_title">Activation Date</div>

					<div class="field_copy">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</div>

					<?php if (is_empty($_vars['id'])) { $user['activate_date'] = date('Y-m-d'); } ?>

					<div class="field_box">
						<input type="text" id="datepicker" name="activate_date" value="<?= $user['activate_date'] ?>" class="normal" tabindex=<?php pv($ti); $ti++; ?> style="">
					</div>

					<br>

					<!-- <div class="field_title">Photo Upload</div>

					<div class="field_copy">Upload file types jpg, jpeg, png and gif. Note, large files will take longer to upload and process.</div>

					<?php
					$_upload = db_query("
					SELECT
					upload.lrg_rename,
					upload.upload_id
					FROM
					upload
					Inner Join connector_upload ON upload.upload_id = connector_upload.upload_id
					WHERE
					connector_upload.ref =  'users' AND
					connector_upload.ref_id =  '".$_vars['id']."'
					limit 1
					",0,0);
					$upload = db_fetch_array($_upload);
					if (db_num_rows($_upload)==0) { ?>

						<div class="field_box">

							<div id="triggers" style="margin:20px 0 0 0; ">

								<input type="file" class="file" name="photoupload_0" style="float:left; margin:0 8px;" size="15">
								<br class="clear">

								<div class="field_box">
									<input style="width:500px;" type="text" name="photo_url" value="" class="normal" tabindex=<?php pv($ti); $ti++; ?>>
									<br>
									<div class="field_copy">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</div>
								</div>

								<br class="clear">

							</div>

						</div>

					<?php }else{ ?>

						<div class="field_box" style="background-color:#fff;">

							<div class="wrap1">
							 <div class="wrap2">
							  <div class="wrap3">

								<div class="wraplrg">

									<img src="<?= $CFG->host ?>/images/users/<? pv($upload['lrg_rename']) ?>" style="width:170px; height:226px; border:0;" alt="">

								</div>

							  </div>
							 </div>
							</div>

							<br class="clear">

							<div style="margin:5px 0 0 0;">
								<a href="user_update.php?del=<? pv($upload[upload_id]) ?>&id=<?= $_vars['id'] ?>">delete</a>
							</div>

						</div>

					<?php } ?>

					<br> -->

					<hr class="normal">

					<div style="float:right;"><button type="submit" class="buttons">Submit</button></div>

					<br>

					<input type="hidden" name="id" value="<? pv($_vars[id]); ?>">
					</form>

					<br><br>

					<?php if (!is_empty($upload['lrg_rename'])) { ?>

						<div class="field_box">

							<div style="margin:0 10px 10px 0; width:590px;">
								<img id="cropbox" src="<?= $CFG->host ?>/images/users/<? pv($upload['lrg_rename']) ?>" style="" alt="" rel="#image_<? pv($upload[upload_id]) ?>">
							</div>

							<div style="margin:0 20px 0 0; float:left;">

								<form action="user_update.php" method="post" onsubmit="return checkCoords();">
									<input type="hidden" id="x" name="x" />
									<input type="hidden" id="y" name="y" />
									<input type="hidden" id="w" name="w" />
									<input type="hidden" id="h" name="h" />
									<input type="hidden" name="crop_id" value="<?= $_vars['id'] ?>">
									<input type="submit" value="Crop Image" />
								</form>

							</div>

							<div style="margin:5px 0 0 0; float:left;">
								<a href="users_update.php?del=<? pv($upload[upload_id]) ?>&id=<?= $_vars['id'] ?>">delete</a>
							</div>

							<br class="clear">

						</div>

						<br>

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
			<li><a class="nblock" href="index.php">All Users</a></li>
		</ul>

	</div>

	<?php include('../user-info.php'); ?>

</div>

<br class="clear">

<? include($CFG->baseroot."/admin/cms-footer.php"); ?>
