<? if($SITE->payment_cc_orderbutton != "") { ?>
	<!-- We Accept:<br> -->
		<? if(strstr($SITE->payment_cc_cards_accepted, "1")) { ?>
			<img src="<? echo $CFG->host ?>/images/site/cc_button_mc.gif" alt="" width="80" height="60" border="0">
		<? } ?>
		<? if(strstr($SITE->payment_cc_cards_accepted, "2")) { ?>
			<img src="<? echo $CFG->host ?>/images/site/cc_button_visa.gif" alt="" width="80" height="60" border="0">
		<? } ?>
		<? if(strstr($SITE->payment_cc_cards_accepted, "3")) { ?>
			<img src="<? echo $CFG->host ?>/images/site/cc_button_amex.gif" alt="" width="80" height="60" border="0">
		<? } ?>
		<? if(strstr($SITE->payment_cc_cards_accepted, "4")) { ?>
			<img src="<? echo $CFG->host ?>/images/site/cc_button_din.gif" alt="" width="80" height="60" border="0">
		<? } ?>
		<? if(strstr($SITE->payment_cc_cards_accepted, "5")) { ?>
			<img src="<? echo $CFG->host ?>/images/site/cc_button_discover.gif" alt="" width="80" height="60" border="0">
		<? } ?>
		<? if(strstr($SITE->payment_cc_cards_accepted, "6")) { ?>
			<img src="<? echo $CFG->host ?>/images/site/cc_button_jcb.gif" alt="" width="80" height="60" border="0">
		<? } ?>
<? } ?>
<? if($SITE->payment_paypal_orderbutton != "") { ?>
	<img src="<? echo $CFG->host ?>/images/site/verification_seal.gif" alt="" width="100" height="100" border="0"><p>&nbsp;</p>
	<img src="<? echo $CFG->host ?>/images/site/logo_cards-echeck_192x26.gif" alt="" width="192" height="26" border="0">
<? } ?>