<? if($_vars[refresh] == "Yes") { ?>
	<meta http-equiv="refresh" content="60;URL=refresher.php?refresh=<? echo $_vars[refresh]; ?>">
	<font size="-1"><b>Yes >> <a href="refresher.php?refresh=No">No</a></b></font><br>
<? } else { ?>
	<font size="-1"><b>No >> <a href="refresher.php?refresh=Yes">Yes</a></b></font><br>
<? } ?>