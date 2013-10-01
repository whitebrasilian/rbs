<b>Contact Us</b><br>
<table width="95%" border="0" cellpadding="1" cellspacing="0">
	<tr>
		<td valign="middle" align="center"><small><b><? pv($SITE->Company); ?></b></small></td>
	</tr>
	<tr>
		<td valign="middle" align="center"><small><? pv($SITE->Address1); ?></small></td>
	</tr>
	<tr>
		<td valign="middle" align="center"><small><? pv($SITE->Address2); ?></small></td>
	</tr>
	<tr>
		<td valign="middle" align="center"><small><? pv($SITE->City); ?>,&nbsp;<? pv($SITE->State); ?>&nbsp;<? pv($SITE->Zip); ?></small></td>
	</tr>
	<tr>
		<td valign="middle" align="center"><small><? pv($SITE->Country); ?></small></td>
	</tr>
	<? if($SITE->Telephone1 != "") { ?>
	<tr>
		<td valign="middle" align="center"><small>Tel:&nbsp;<? pv($SITE->Telephone1); ?></small></td>
	</tr>
	<? } ?>
	<? if($SITE->Telephone2 != "") { ?>
	<tr>
		<td valign="middle" align="center"><small>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<? pv($SITE->Telephone2); ?></small></td>
	</tr>
	<? } ?>
	<? if($SITE->FAX != "") { ?>
	<tr>
		<td valign="middle" align="center"><small>Fax:&nbsp;<? pv($SITE->FAX); ?></small></td>
	</tr>
	<? } ?>
	<? if($SITE->Email != "") { ?>
	<tr>
		<td valign="middle" align="center"><small>Email:&nbsp;<a href="mailto:<? pv($SITE->Email); ?>"><? pv($SITE->Email); ?></a></small></td>
	</tr>
	<? } ?>
</table>
