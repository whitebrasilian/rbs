<?
session_start();

if(!session_is_registered("ST_Count")) {
	session_register("ST_Count");
	session_register("ST_Start");
	$ST_Count = 0;
	$ST_Start = time();
} else {
	$ST_Count++;
}
$ST_Age = time() - $ST_Start;
$ST_SessionId = session_id();
?>
<HEAD>


	<script type="text/javascript" src="sessionTimer.js"></script>
</HEAD>

<BODY OnLoad="timeIt()">
	<table width="100%" border="1" cellspacing="0" cellpadding="5">
		<tr>
			<td valign="top"><? include("sessionTimer.php"); ?></td>
			<td valign="top">
				<div align="center">
					Session Timer
</div>
				<p>Usage:</p>
				<p>In the starter.php, where you set up your session, include the following:<br>
					<textarea name="textareaName" rows="10" cols="55" style="width:100%"><?
						if(!session_is_registered("ST_Count")) {
							session_register("ST_Count");
							session_register("ST_Start");
							$ST_Count = 0;
							$ST_Start = time();
						} else {
							$ST_Count++;
						}
						$ST_Age = time() - $ST_Start;
						$ST_SessionId = session_id();?>
						</textarea></p>
				<p>In header.php, include the following Javascript file in the header of the document:<br>
					<textarea name="textareaName" rows="2" cols="55" style="width:100%">&lt;script type=&quot;text/javascript&quot; src=&quot;sessionTimer.js&quot;&gt;&lt;/script&gt;</textarea></p>
				<p>Also in header.php, add the following to the body tag:<br>
					<textarea name="textareaName" rows="2" cols="55" style="width:100%">OnLoad="timeIt()"</textarea></p>
				<p>Finally, use php to include sessionTimer.php where you want to have the information displayed:<br>
					<textarea name="textareaName" rows="2" cols="55" style="width:100%">include("sessionTimer.php");</textarea></p>
			</td>
		</tr>
	</table>

