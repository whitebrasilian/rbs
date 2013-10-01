<div id="" class="" style="float:left;">

	<div id="" class="" style=""><? if(!($_vars[first_name])){ echo"<a class='RedWhite'>*</a>&nbsp;"; } ?>First Name:</div><input type="text" id="" name="first_name" value="<? pv($_vars[first_name]) ?>" class="input_text" tabindex=<?php pv($ti); $ti++; ?> style=""><br class="clear">
	<div id="" class="" style=""><? if(!($_vars[last_name])){ echo"<a class='RedWhite'>*</a>&nbsp;"; } ?>Last Name:</div><input type="text" id="" name="last_name" value="<? pv($_vars[last_name]) ?>" class="input_text" tabindex=<?php pv($ti); $ti++; ?> style=""><br class="clear">
	<div id="" class="" style=""><? if(!($_vars[b_address])){ echo"<a class='RedWhite'>*</a>&nbsp;"; } ?>Address:</div><input type="text" id="" name="b_address" value="<? pv($_vars[b_address]) ?>" class="input_text" tabindex=<?php pv($ti); $ti++; ?> style=""><br class="clear">
	<div id="" class="" style="">Line 2:</div><input type="text" id="" name="b_address2" value="<? pv($_vars[b_address2]) ?>" class="input_text" tabindex=<?php pv($ti); $ti++; ?> style=""><br class="clear">
	<div id="" class="" style=""><? if(!($_vars[b_city])){ echo"<a class='RedWhite'>*</a>&nbsp;"; } ?>City:</div><input type="text" id="" name="b_city" value="<? pv($_vars[b_city]) ?>" class="input_text" tabindex=<?php pv($ti); $ti++; ?> style=""><br class="clear">
	<div id="" class="" style=""><? if(!($_vars[b_state])){ echo"<a class='RedWhite'>*</a>&nbsp;"; } ?>State/Province:</div><select name="b_state" class="input_text" tabindex=<?php pv($ti); $ti++; ?>><? StatesFull($_vars[b_state]) ?></select><br class="clear">
	<div id="" class="" style=""><? if(!($_vars[b_zip])){ echo"<a class='RedWhite'>*</a>&nbsp;"; } ?>Postal Code:</div><input type="text" id="" name="b_zip" value="<? pv($_vars[b_zip]) ?>" class="input_text" tabindex=<?php pv($ti); $ti++; ?> style=""><br class="clear">
	<div id="" class="" style=""><? if(!($_vars[b_country])){ echo"<a class='RedWhite'>*</a>&nbsp;"; } ?>Country:</div><select name="b_country" class="input_text" tabindex=<?php pv($ti); $ti++; ?>><? CountriesDD($_vars[b_country]) ?></select><br class="clear">

</div>

<div id="" class="" style="margin-left:30px; float:left;">

	<? if (!in_array('cart',$php_self_exploded)) { ?>

		<div id="" class="" style=""><? if(!($_vars[password1])){ echo"<a class='RedWhite'>*</a>&nbsp;"; } ?>Password:</div><input type="text" id="" name="password1" value="<? pv($_vars[password1]) ?>" class="input_text" tabindex=<?php pv($ti); $ti++; ?> style=""><br class="clear">
		<div id="" class="" style=""><? if(!($_vars[password2])){ echo"<a class='RedWhite'>*</a>&nbsp;"; } ?>Repeat Password:</div><input type="text" id="" name="password2" value="<? pv($_vars[password2]) ?>" class="input_text" tabindex=<?php pv($ti); $ti++; ?> style=""><br class="clear">
		<div id="" class="RedWhite" style="">* Password will change if fields are filled.</div><br class="clear">

	<?php } ?>

	<div id="" class="" style=""><? if(!($_vars[email])){ echo"<a class='RedWhite'>*</a>&nbsp;"; } ?>Email:</div><input type="text" id="" name="email" value="<? pv($_vars[email]) ?>" class="input_text" tabindex=<?php pv($ti); $ti++; ?> style=""><br class="clear">
	<div id="" class="" style=""><? if(!($_vars[phone])){ echo"<a class='RedWhite'>*</a>&nbsp;"; } ?>Telephone I:</div><input type="text" id="" name="phone" value="<? pv($_vars[phone]) ?>" class="input_text" tabindex=<?php pv($ti); $ti++; ?> style=""><br class="clear">
	<div id="" class="" style="">Telephone II:</div><input type="text" id="" name="phone2" value="<? pv($_vars[phone2]) ?>" class="input_text" tabindex=<?php pv($ti); $ti++; ?> style=""><br class="clear">
	<div id="" class="" style="">Comments:</div><textarea id="" name="comments" style="height:50px;" tabindex=<?php pv($ti); $ti++; ?> class="input_text"><? pv($_vars[comments]) ?></textarea><br class="clear">
	<div id="" class="" style=""><?php pv($SITE->Company); ?> would like to send you emails about upcoming and current sales, or other news pertaining to our website and industry. Newsletters come out about 4-6 times a year.</div><div id="" class="" style="margin:50px 0 0 10px;"><input style="width:20px" type="checkbox" name="newsletter" value='Yes' <? if ($_vars[newsletter] != "No") { echo "checked"; } ?>> ~ Send them to me</div><br class="clear">

</div>

<br class="clear">

<script language="Javascript">
<!--
function checkFields(){
	var errormsg = "";
	if (document.this_form.first_name.value == "") { var errormsg = errormsg + "Please Enter Your First Name.\n\r"; }
	if (document.this_form.last_name.value == "") { var errormsg = errormsg + "Please Enter Your Last Name.\n\r"; }
	if (document.this_form.phone.value == "") { var errormsg = errormsg + "Please Enter Your Telephone.\n\r"; }
	if (document.this_form.email.value == "") { var errormsg = errormsg + "Please Enter Your Email.\n\r"; }

	if (document.this_form.b_address.value == "") { var errormsg = errormsg + "Please Enter Your Billing Address.\n\r"; }
	if (document.this_form.b_city.value == "") { var errormsg = errormsg + "Please Enter Your Billing City.\n\r"; }
	if (document.this_form.b_zip.value == "") { var errormsg = errormsg + "Please Enter Your Billing Zip Code.\n\r"; }
	if (document.this_form.b_country.value == "") { var errormsg = errormsg + "Please Enter Your Billing Country.\n\r"; }
	if (document.this_form.b_state.value == ""){
		if(document.this_form.b_country.value == "US" || document.this_form.b_country.value == "CA") { var errormsg = errormsg + "Please Enter Your Billing State or Province.\n\r"; }
	}

	if (errormsg != "") { alert(errormsg); return false; }
}
// -->
</script>