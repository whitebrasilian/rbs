<?php include('../../starter.php');

$q = strtolower($_GET["q"]);
if (!$q) return;

$_gst = db_query("SELECT author FROM blog WHERE author LIKE '%".$q."%' GROUP BY author",0,0);
while($gst = db_fetch_array($_gst)){
	$gsts .= $gst['author']."\n";
}
echo $gsts;
?>