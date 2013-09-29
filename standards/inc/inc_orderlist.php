<? while ($CartItem = db_fetch_array($qid)) {

	$CartItem['Linetotal'] = $CartItem['Qty'] * $CartItem['Price'];

	$item_name .= "(".$CartItem[Qty].")".$CartItem[name].", ";
	$item_code .= "(".$CartItem[Qty].")".$CartItem[sku].", ";

	$catquery = db_query("SELECT name FROM categories WHERE id = '$CartItem[category_id]'");
	$CatName = db_fetch_array($catquery);

	$_qdb = db_query("SELECT sku FROM products WHERE id = '".$CartItem['attached_to']."'");
	$qdb = db_fetch_array($_qdb); ?>

	<div id="" class="" style="margin:10px 0 10px 0;">

		<div id="" class="" style="width:360px; float:left;">

			<? if($SITE->ShowProductName == "Yes") { pv($CatName['name']) ?><br><? pv($CartItem['name']) ?> - <? } ?>
			<? if($SITE->ShowProductID == "Yes") { ?>#<? pv($CartItem['sku']) ?><? } ?>

		</div>
		<div id="" class="" style="width:100px; float:left;">

			<?  if($CartItem[SalePriceEach] > 0){ ?>

				<strike><? printf("$%.2f", $CartItem[PriceEach]); ?></strike>
				&nbsp;&nbsp;&nbsp;
				<span class="RedWhite"><? printf("$%.2f", $CartItem[SalePriceEach]); ?></span>

			<? }else{ ?>

				<? printf("$%.2f", $CartItem[PriceEach]); ?>

			<? } ?>

		</div>
		<div id="" class="" style="width:100px; float:left;"><span class="BlueWhite" style="font-size:10px;">x</span>&nbsp;<? echo $CartItem[Qty]; ?></div>
		<div id="" class="" style="width:100px; float:left;">=&nbsp;&nbsp;&nbsp;<? printf("$%.2f", $CartItem['Linetotal']); ?></div>

		<br class="clear">
		<br>

	</div>

<? } ?>

<?php /************************************************** */ ?>
<?php /* Commented out sections are for the gift wrapping */ ?>
<?php /* section. donot remove incase mike changes mind   */ ?>
<?php /************************************************** */ ?>
<?php /************************************************** */ ?>
<?php /************************************************** */ ?>

<!-- <span class="LightCopy" style=""><? pv($CatName['name']) ?> <?php if ($qdb['sku'] != "") { ?>for #<?php pv($qdb['sku']); } ?></span><br><? pv($CartItem['name']) ?> - -->