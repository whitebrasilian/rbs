<?php 
include("../../starter.php");
require_login();
require_priv("admin");

pre($_GET);

if (is_array($_GET['listItem'])) {
	foreach ($_GET['listItem'] as $position => $item) : 
		db_query("UPDATE media SET dorder = '".$position."' WHERE id = '".$item."'",1,0);
	endforeach; 
}

?>