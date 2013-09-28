<?php 
for ($i = 0; $i < count($_FILES); $i++) {

	if (!is_empty($_FILES['photoupload_'.$i]['name'])){ $v++;

		$file = $_FILES['photoupload_'.$i]['tmp_name'];
		$error = false;
		$size = false;

		$filename = basename($_FILES['photoupload_'.$i]['name']);
		$ext = strtolower(substr($filename, strrpos($filename, '.') + 1));

		$types = array('jpg','jpeg','png','gif');

		if (!is_uploaded_file($file) || ($_FILES['photoupload_'.$i]['size'] > 2 * 1024 * 1024)){
			$error = 'Please upload only files smaller than 2Mb!';
		}elseif (!in_array($ext, $types)){
			$error = 'Unsupported File Format.';
		}else{

			/*   set pathing for all files   */
			$strtime = strtotime("now").$v;
			$lrg_rename = $strtime."lrg.".$ext;
			$lrg_path = MODULE_UPLOADS."/".$lrg_rename;

			/*   move file to its folder   */
			move_uploaded_file($_FILES['photoupload_'.$i]['tmp_name'], $lrg_path);
			chmod($lrg_path, 0755);
			$size = getimagesize($lrg_path);

			db_query("INSERT INTO upload (file_name,file_size,file_tmp_name,lrg_rename,lrg_height,lrg_width,med_rename,med_height,med_width,sml_rename,sml_height,sml_width) VALUES ('".$_FILES['photoupload_'.$i]['name']."','".$_FILES['photoupload_'.$i]['size']."','".$_FILES['photoupload_'.$i]['tmp_name']."','".$lrg_rename."','".$size[1]."','".$size[0]."','".$med_rename."','".$med_height."','".$med_width."','".$sml_rename."','".$sml_height."','".$sml_width."')",0,0);
			$temp_id = db_insert_id();

			db_query("INSERT INTO connector_upload (upload_id, ref, ref_id) VALUES ('".$temp_id."', 'modules', '".$_vars['id']."')",0,0);

		}

	}

} 
?>