<?php 
include("../../starter.php");
require_login();
require_priv("admin");

pre($_GET);

if (is_array($_GET['listItem'])) {
	foreach ($_GET['listItem'] as $position => $item) : 
		db_query("UPDATE connector_modules SET dorder = '".$position."' WHERE module_id = '".$item."' AND page_id = '".$_SESSION['temp']['page_id']."'",1,0);
	endforeach; 
}
?>