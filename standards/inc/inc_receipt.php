<?
/* This page is included in the payment module at the point the order is complete and everything has been processed, and in the paypal return page, paypal_complete.php.  All it needs is an order_id, and it should be good to go. */

if ($_vars[invoice]) { $_vars[order_id] = $_vars[invoice]; }
elseif ($order[id]) { $_vars[order_id] = $order[id]; }

unset($order);
$qid_order = db_query("SELECT * FROM orders WHERE id = '".$_vars['order_id']."'");
$order = db_fetch_array($qid_order);

$qcd = db_query("SELECT Country FROM taxes_countries WHERE Code = '".$order['b_country']."' OR Country = '".$order['b_country']."'");
$qc = db_fetch_array($qcd); ?>

<div id="" class="" style="width:800px; margin:15px;">

	<div id="" class="" style="margin:0 0 40px 0;">

		Frock Boutique Order #<? pv($_vars['order_id']) ?><br><br>

	</div>

	<div id="" class="" style="float:left; margin-right:10px;"><b>Seller<br>Information:</b></div>
	<div id="" class="" style="float:left; width:200px; margin-right:10px;">

		<? echo $SITE->Company ?><br>
		<? if($SITE->Address1 !=""){ ?><? echo $SITE->Address1; ?><br><? } ?>
		<? if($SITE->Address2 !=""){ ?><? echo $SITE->Address2; ?><br><? } ?>
		<? if($SITE->City !=""){ ?><? echo $SITE->City ?>, <? echo $SITE->State ?>&nbsp;<? echo $SITE->Zip ?><br><br><? } ?>
		<? if($SITE->Telephone1 !=""){ ?>Phone: <? echo $SITE->Telephone1 ?><br><? } ?>
		<? if($SITE->Telephone2 !=""){ ?>Alt Phone: <? echo $SITE->Telephone2 ?><br><? } ?>
		<? if($SITE->FAX !=""){ ?>Fax: <? echo $SITE->FAX ?><br><? } ?>
		Email: <? echo $SITE->Email ?>

	</div>

	<div id="" class="" style="float:left; margin-right:10px;"><b>Buyer Billing &<br>Shipping Information:</b></div>
	<div id="" class="" style="float:left; width:200px;">

		<? pv($order['c_first_name']); ?>&nbsp;<? pv($order['c_last_name']); ?><br>
		<? pv($order['b_address']); ?><br>
		<? if($order['b_address2']){ pv($order['b_address2']); ?><br><? } ?>
		<? pv($order['b_city']); ?>, <? pv($order['b_state']); ?> <? pv($order['b_zip']); ?><br>
		<? pv($qc[Country]); ?><br><br>
		Phone: <? pv($order['c_telephone']); ?> <? if($order['c_telephone2']){ ?> or <? pv($order['c_telephone2']); ?><? } ?><br>
		Email: <? pv($order['c_email']); ?>

	</div>

	<br class="clear">

	<br><br>

	<div id="" class="" style="float:left; width:300px;">

		<b>Promotional Code</b>: <? pv($order['promo_code']); ?>

	</div>

	<br class="clear">
	<br><br>

	<div id="" style="margin:40px 0 0 0;">

		<div style="width:200px;float:left;">PRODUCT(S)</div>
		<div style="width:200px;float:left;">OPTIONS</div>
		<div style="width:50px;float:left;">QTY.</div>
		<div style="width:100px;float:right;">TOTAL</div>
		<br class="clear">

		<?php
		$qid = GetOrderItems($order[id]);
		$x=0;
		while ($_result = db_fetch_array($qid)) { ?>

			<div style="width:200px;float:left;"><?= $_result['name'] ?></div>
			<div style="width:200px;float:left;">
				
				<?php 
				$atts = "";
				$explode = explode(',',$_result['attributes']);
				for ($i = 0; $i < count($explode); $i++) {

					$exp = explode('-',$explode[$i]);
					
					$_rox = db_query("SELECT name FROM attributes WHERE id = '".$exp[1]."'",0,0);
					$rox = db_fetch_array($_rox);
					$atts .= $rox[name].', ';
			
				} ?>	
				
				<?= substr($atts,0,-2) ?>

			</div>
			<div style="width:50px;float:left;"><?= $_result['qty'] ?></div>
			<div style="width:100px;float:right;"><?= format_number($_result['Linetotal'],'1','1','2') ?></div>
			<br class="clear">

		<?php } ?>

	</div>

	<br>

	<div id="" class="" style="float:right; width:300px;">

		<div id="" class="" style="float:right;">Sub Total: <? printf("USD $%.2f", $order['SubTotal']); ?></div>
		<br class="clear">

		<?php if ($order["DiscountType"]=="Percentage Off") {
			
			$perc = ($order[DiscountValue] / 100) * $order['SubTotal']; ?>

			<div id="" class="" style="float:right;"><?= $order['DiscountValue'] ?>% Discount: <? printf("USD $%.2f", $perc); ?></div>
			<br class="clear">

		<?php } elseif ($order["DiscountType"]=="Fate Rate") { ?>

			<div id="" class="" style="float:right;">Flat Rate Discount: <? printf("USD $%.2f", $order["DiscountValue"]); ?></div>
			<br class="clear">

		<?php } ?>

		<? if($SITE->Taxes == "Yes"){ ?>

			<div id="" class="" style="float:right;">Tax: <? printf("USD $%.2f", $order["Tax"]); ?></div>
			<br class="clear">

		<?php } ?>

		<?php if ($order["DiscountType"]=="Free Shipping") { ?>
			
			<div id="" class="" style="float:right;">Free Delivery:&nbsp;<? printf("USD $%.2f", 0); ?></div>
			<br class="clear">

		<?php } else { ?>
					
			<div id="" class="" style="float:right;">Delivery:&nbsp;<? printf("USD $%.2f", $order['Shipping']); ?></div>
			<br class="clear">

		<?php } ?>

		<? if($order['ShippingChoice'] > 0) { ?>

			<div id="" class="" style="float:right;"><? echo $order['ShippingChoice']; ?><? printf("USD $%.2f", $order['ShippingExtra']); ?></div>
			<br class="clear">

		<?php } ?>

		<div id="" class="" style="float:right;">Grand Total: <? printf("USD $%.2f", $order['amount']); ?></div>
		<br class="clear">

	</div>

</div>