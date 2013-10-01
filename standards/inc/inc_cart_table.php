<?
$x=0;
while ($_result = db_fetch_array($qid)) { //pre($_result);

	if($_result['long_desc'] != "") { $SITE->ShowMoreInfo = "Yes"; }else{ $SITE->ShowMoreInfo = ""; }
	$_vars['ProductLink'] = $CFG->host."/cart/product_specs.php?id=".$_result['id']."&category=".$_result['category_id']."&parent=".$_vars['parent'];
	$_vars['ProductTitle'] = "More info on Product #".$_result['sku']." - ".$_result['name']; ?>

	<?php /*   start checkout_tout   */ ?>
	<div id="checkout_tout">

		<div id="tout_header">

			<div id="tout_header_l" style="background-image:url(<?php pv($CFG->host); ?>/images/site/tout_header_l.png); background-repeat:no-repeat;"></div>
			<div id="tout_header_c" style="background-image:url(<?php pv($CFG->host); ?>/images/site/tout_header_c.png); background-repeat:repeat-x;">

				<div id="tout_header_title">

					<h2 style="display:inline;"><a class="WhiteBlue" href="<? echo $_vars['ProductLink']; ?>" title="<? echo $_vars['ProductTitle']; ?>"><? pv($_result['category']); ?></a></h2>
					<span class="product_tout_title" style="">#<? pv(substr($_result['sku'],4)); ?></span>

				</div>

			</div>
			<div id="tout_header_r" style="background-image:url(<?php pv($CFG->host); ?>/images/site/tout_header_r.png); background-repeat:no-repeat;"></div>

			<br class="clear">

		</div>

		<div id="checkout_tout_background">

			<div id="checkout_tout_gray">

				<div id="checkout_tout_line1"></div>
				<div id="checkout_tout_copy">

					<?php
					if ($_result['long_desc'] <> "") { TextOrHTML($_result['long_desc']); }
					else{TextOrHTML($_result['description']); }
					?>

				</div>
				<div id="checkout_tout_line2"></div>

			</div>

			<div id="checkout_title"><h3><a href="<? echo $_vars['ProductLink']; ?>" title="<? echo $_vars['ProductTitle']; ?>" class="WhiteBlueLink"><?php pv(str_replace($_result['category'],"",$_result['name'])); ?></a></h3></div>

			<div id="checkout_price">

				<? if ($_result['SalePriceEach'] > 0){ ?>

					<span class="Red" style="font-size:20px;"><? printf("$%.2f", $_result['SalePriceEach']); ?></span>
					<span class="StrikeThrough" style="font-size:14px;" ><? printf("$%.2f", $_result['PriceEach']); ?></span>

				<? }else{ ?>

					<? printf("$%.2f", $_result['PriceEach']); ?>

				<? }?>

				<span class="WhiteBlue" style="">x</span> <? pv($_result['Qty']); ?>

			</div>

			<form action="shopping_cart.php" method="post" name="thisform_<? pv($f); $form = "thisform_$f"; $f++; ?>">

				<div id="checkout_basket" class="BlueRed"><input type="image" name="submit" src="<? echo $CFG->host ?>/images/site/basket_remove.png" class="" style="width:48px; height:47px;"><br>Remove<br>From<br>Basket</div>

			<input type="hidden" name="func" value="remove">
			<input type="hidden" name="ProductID" value=<? pv($_result[id]); ?>>
			</form>

			<div id="swf_spec_<? pv($_result['sku']); ?>" class="border" style="z-index:2; position:absolute; left:10px; top:30px; width:215px; height:161px;"><a href="<? echo $_vars['ProductLink']; ?>" title="<? echo $_vars['ProductTitle']; ?>"><img src="<? echo $CFG->host ?>/images/products/<? pv(substr($_result['sku'],0,4)); ?>/<? pv($_result['sku']); ?>.jpg" alt="" style="width:215px; height:161px;"></a></div>
			<script type="text/javascript">
				var so = new SWFObject("<? pv($CFG->host); ?>/flash/image.swf","swf_spec_<? pv($_result['sku']); ?>","215","161","7","#7E7E7E");
				so.addVariable("img_src","/images/products/<? pv(substr($_result['sku'],0,4)); ?>/<? pv($_result['sku']); ?>.jpg");
				so.addVariable("watermark_src","/flash/image_watermark.swf");
				so.addVariable("watermark_alpha","20");
				so.addVariable("fadetime","2");
				so.addVariable("link_url","product_specs.php");
				so.addVariable("sku","<? pv($_result['sku']); ?>");
				so.addVariable("category","<? echo $_vars['category']; ?>");
				so.addVariable("host","<? pv($CFG->host); ?>");
				so.write("swf_spec_<? pv($_result['sku']); ?>");
			</script>

		</div>

	</div>
	<?php /*   end checkout_tout   */ ?>

<? } ?>