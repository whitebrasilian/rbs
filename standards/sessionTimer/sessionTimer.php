
<table border="0" cellspacing="0" cellpadding="1">
		<tr>
			<td>
				<fieldset>
					<legend><b><font size="-1">Session ID</font></b></legend><input type="text" name="SessionId" size="10" value="<? echo $ST_SessionId; ?>"> 
				</fieldset>
			</td>
		</tr>
		<tr>
			<td>
				<fieldset>
					<legend><b><font size="-1">Pages Loaded</font></b></legend><input type="text" name="count" size="10" value="<? echo $ST_Count; ?>"> 
				</fieldset>
			</td>
		</tr>
		<form name="timerform">
		<tr>
			<td>
				<fieldset>
					<legend><b><font size="-1">Time Left</font></b></legend><input type="text" name="clock" size="10" value="20:00"> 
				</fieldset>
			</td>
		</tr>
		</form>
		<tr>
			<td>
				<fieldset>
					<legend><b><font size="-1">Start Time</font></b></legend><input type="text" name="start" size="10" value="<? echo $ST_Start; ?>"> 
				</fieldset>
			</td>
		</tr>
		<tr>
			<td>
				<fieldset>
					<legend><b><font size="-1">Age</font></b></legend><input type="text" name="SessionAge" size="10" value="<? echo $ST_Age; ?> Seconds"> 
				</fieldset>
			</td>
		</tr>
	<tr>
		<td>
			<fieldset>
				<legend><b><font size="-1">Stay Logged In?</font></b></legend>
				<myiFrame>
					<iFrame  width="75" height="20" src="<? echo $_SERVER[DOCUMENT_ROOT] ?>/standards/sessionTimer/refresher.php" id="target" name="refresher" border="1" bordercolor="gray" hspace="0" vspace="0" marginwidth="0" marginheight="0" scrolling="no" frameborder="yes"></iFrame>
					

				</myiFrame>
				 
			</fieldset>
		</td>
	</tr>
</table>
