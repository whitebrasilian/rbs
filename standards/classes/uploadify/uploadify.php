<?php
include('../../../starter.php');
/*
Uploadify v2.1.4
Release Date: November 8, 2010

Copyright (c) 2010 Ronnie Garcia, Travis Nickels

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/
if (!empty($_FILES)) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $_REQUEST['folder'] . '/';
	$targetFile =  str_replace('//','/',$targetPath) . $_FILES['Filedata']['name'];

	/*   set pathing for all files   */
	$filename = basename($_FILES['Filedata']['name']);
	$ext = strtolower(substr($filename, strrpos($filename, '.') + 1));
	$strtime = strtotime("now")."-".str_replace(" ","-",$filename);
	$lrg_rename = $strtime;
	$lrg_path = UPLOADS."/".$lrg_rename;

	//pre($_FILES);

	// $fileTypes  = str_replace('*.','',$_REQUEST['fileext']);
	// $fileTypes  = str_replace(';','|',$fileTypes);
	// $typesArray = split('\|',$fileTypes);
	// $fileParts  = pathinfo($_FILES['Filedata']['name']);

	// if (in_array($fileParts['extension'],$typesArray)) {
		// Uncomment the following line if you want to make the directory if it doesn't exist
		// mkdir(str_replace('//','/',$targetPath), 0755, true);

		move_uploaded_file($tempFile,$lrg_path);
		echo str_replace($_SERVER['DOCUMENT_ROOT'],'',$targetFile);
	// } else {
	// 	echo 'Invalid file type.';
	// }

		$size = getimagesize($lrg_path);

		$_dbq = db_query("SELECT galleries_id FROM site_settings WHERE id = '1' limit 1");
		$dbq = db_fetch_assoc($_dbq);

		$xpl = explode("_",$dbq['galleries_id']);

		db_query("INSERT INTO upload (file_name,file_size,file_tmp_name,lrg_rename,lrg_height,lrg_width, ".$xpl['0']."_id) VALUES ('".$_FILES['Filedata']['name']."','".$_FILES['Filedata']['size']."','".$tempFile."','".$lrg_rename."','".$size[1]."','".$size[0]."','".$xpl['1']."')",0,0);
		$temp_id = db_insert_id();

}
?>