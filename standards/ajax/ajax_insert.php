<?php
include("../../starter.php");

if ($_vars['field_name'] == 'load_speed') {

	$load_speed = timer(0);
	db_query("UPDATE action_tracking SET load_speed = '".$load_speed."' WHERE id = '".$_vars['field_value']."'",1,0);

die;
}

if (isset($_vars['remove_file'])) {

	$_qdb = db_query("SELECT lrg_rename, med_rename, sml_rename FROM upload WHERE upload_id = '".$_vars['id']."' limit 1",0,0);
	$qdb = db_fetch_array($_qdb);

	rm(UPLOADS.'/'.$qdb['lrg_rename']);
	rm(UPLOADS.'/'.$qdb['med_rename']);
	rm(UPLOADS.'/'.$qdb['sml_rename']);

	db_query("DELETE FROM connector_upload WHERE upload_id = '".$_vars['id']."' limit 1",1,0);
	db_query("DELETE FROM upload WHERE upload_id = '".$_vars['id']."' limit 1",1,0);

}

if ($_vars['field_name'] == 'product_show') { 

	/*	check to see which child is selected.	*/
	$qsz = db_query("SELECT display FROM attributes WHERE child_id = '".$_vars['child']."' AND parent_id = '".$_vars['parent']."' AND product_id = '".$_vars['pid']."'",1,0);
	$rsz = db_fetch_array($qsz);
	
	if (is_empty($rsx[display])) { $a=1; }else{ $a=0; }

	db_query("UPDATE attributes SET display = '".$a."' WHERE child_id = '".$_vars['child']."' AND parent_id = '".$_vars['parent']."' AND product_id = '".$_vars['pid']."'",1,0);
	//pre($rsx);

}

?>