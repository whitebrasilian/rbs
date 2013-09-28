<?php
include("../starter.php");
//pre($_vars);

if (!is_empty($_POST['remove'])) {

	$_qdb = db_query("SELECT lrg_rename FROM upload WHERE upload_id = '".$_POST['remove']."' limit 1");
	$qdb = db_fetch_assoc($_qdb);

	unlink(UPLOADS.'/'.$qdb['lrg_rename']);

	db_query("DELETE FROM upload WHERE upload_id = '".$_POST['remove']."'",0,0);

	$rturn = 1;

}elseif ($_vars['email']<>"") {

	$message = "
	<p>Name - ".$_vars['name']."</p>

	<p>Email - ".$_vars['email']."</p>

	<p>Phone - ".$_vars['phone']."</p>
	";

	if (!is_empty($_vars['trip_dates'])) { $message .= "<p>Trip Dates - ".$_vars['trip_dates']."</p>"; }

	$message .= "
	<p>Message - ".$_vars['body']."</p>
	";

	//clear out slashes from php
	$body = stripslashes($message);

	if (!is_empty($_vars['trip_dates'])) { $subject =  "Book Now Form Submission"; }
	else { $subject =  "Contact Form Submission"; }

	$headers  = "MIME-Version: 1.0\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\n";
	$headers .= "From:".$_vars['email']."\n";
	$headers .= "Reply-To:".$_vars['email']."\n";
	$headers .= "X-Mailer: PHP"."\n";

	// send mail to customer
	if(mail("branham@royalwolf.com", $subject, $body, $headers)){
		echo 1;
	}

}

die;
?>