<?  if($SITE->NumberOfColumns == "1") {

	$x=0;
	while ($_result = db_fetch_array($qid)) { //pre($_result);

		/*	**********************************************************	*/
		/*	This part is for when the display block is seen in manager	*/
		/*	**********************************************************	*/
		if (in_array('order.php',$php_self_exploded)) {

			$qid = db_query("SELECT * FROM products as p, products_categories as pc WHERE p.id = '".$_result['product_id']."' AND p.id = pc.product_id");
			$_result = db_fetch_array($qid);

			if($_result['sendBackType'] == '' || $_result['sendBackType'] == 'Repair'){

				if($_result['SalePriceEach']){

					$subtotal = $subtotal + $_result['SalePriceEach'];

				}else{

					$subtotal = $subtotal + $_result['PriceEach'];

				}
			}
		}

		/*	**********************************************************	*/
		/*	**********************************************************	*/

		if (is_null($CatName)) {

			$_result['cat_abrv'] = substr($_result['sku'],0,4);
			$catquery = db_query("SELECT id, name, cat_abrv FROM categories WHERE cat_abrv = '".$_result['cat_abrv']."'");
			$CatName = db_fetch_array($catquery);

		}elseif ($_vars['category'] > 10000) {

			$qid_cats = db_query("SELECT c.id, c.cat_abrv, pc.category FROM categories as c, products_categories as pc WHERE c.id = pc.category_id AND pc.product_id = '".$_result['id']."'");
			$CatName = db_fetch_array($qid_cats);
			$CatName['name'] = $CatName['category'];

			if ($_vars['category'] == 999999) { $CatName['description'] = 'Newest Items'; }
			elseif ($_vars['category'] == 888888) { $CatName['description'] = 'Sale Items'; }
		}

		$SITE->ShowMoreInfo = "";
		$_vars['ProductLink'] = $CFG->host."/cart/product_specs.php?id=".$_result['id']."&category=".$CatName['id']."&parent=".$_vars['parent'];
		$_vars['ProductTitle'] = "More info on Product #".$_result['sku']." - ".$_result['name'];

		if($_result['long_desc'] != "") { $SITE->ShowMoreInfo = "Yes"; } ?>

		<a name="<? pv($_result['sku']); ?>">
		<?php /*   start products_tout   */ ?>
		<div id="products_tout">

			<div id="tout_header">

				<div id="tout_header_l" style="background-image:url(<?php pv($CFG->host); ?>/images/site/tout_header_l.png); background-repeat:no-repeat;"></div>
				<div id="tout_header_c" style="background-image:url(<?php pv($CFG->host); ?>/images/site/tout_header_c.png); background-repeat:repeat-x;">

					<div id="tout_header_title">

						<h2 style="display:inline;"><a class="WhiteBlue" href="<? echo $_vars['ProductLink']; ?>" title="<? echo $_vars['ProductTitle']; ?>"><? pv($CatName['name']); ?></a></h2>
						<span class="product_tout_title" style="">#<? pv(substr($_result['sku'],4)); ?></span>

					</div>

				</div>
				<div id="tout_header_r" style="background-image:url(<?php pv($CFG->host); ?>/images/site/tout_header_r.png); background-repeat:no-repeat;"></div>

				<br class="clear">

			</div>

			<div id="products_tout_background">

				<div id="products_tout_gray">

					<div id="products_tout_stock" style="padding:4px 0 4px 0;">

						<? if($_result['single_inventory'] < 1){ ?>

							<div id="" class="" style="width:268px; text-align:center;"><a href="<? echo $CFG->host ?>/cart/product_request.php?id=<? pv($_result['id']); ?>&brand=<? pv($CatName['name']); ?>&model=<?php pv(str_replace($CatName['name'],"",$_result['name'])); ?>&sku=<? pv($_result['sku']); ?>" class="RedWhiteLink"><u>Out of Stock, Click to be Notified</u></a></div><!-- &name=<? pv($_result['name']); ?> -->

						<? }else{ ?>

							<div id="" class="BlueRed" style="width:268px; text-align:center;">In Stock</div>

						<? }?>

					</div>
					<div id="products_tout_line1"></div>
					<div id="products_tout_copy">

						<?php if ($_result['description'] == "" || in_array('product_specs.php',$php_self_exploded)) {

							TextOrHTML($_result['long_desc']);

						} else {

							TextOrHTML($_result['description']);

						} ?>

						<br>

						<?php if (in_array('product_specs.php',$php_self_exploded)) { ?>

							<br>

							<? /*	SELECT and LOOP Att's that belong to this category*/
							$_temp = array();
							$qdb = db_query("SELECT id, parent_id FROM attributes WHERE product_id = '".$_vars['id']."'");
							while($row = db_fetch_array($qdb)) {

								/*	Get name of Parent Att.	*/
								$qax = db_query("SELECT name FROM attributes WHERE id = '".$row['parent_id']."'");
								$rax = db_fetch_array($qax);

								$_temp[] = $rax['name'];
							}

							sort($_temp);

							for ($i = 0; $i < count($_temp); $i++) {

								/*	Get name of Child Att.	*/
								$qdf = db_query("SELECT id FROM attributes WHERE name = '".$_temp[$i]."'");
								$rof = db_fetch_array($qdf);

								$qdb = db_query("SELECT id, child_id FROM attributes WHERE product_id = '".$_vars['id']."' AND parent_id = '".$rof['id']."'");
								$row = db_fetch_array($qdb);

								/*	Get name of Child Att.	*/
								$qdx = db_query("SELECT name FROM attributes WHERE id = '".$row['child_id']."'");
								$rox = db_fetch_array($qdx); ?>

								<div id="" class="BlueWhite" style="width:100px; float:left;"><? pv($_temp[$i]); ?>:</div>
								<div id="" class="" style=" float:left;"><? pv($rox['name']); ?></div>

								<br class="clear">

							<? } ?>

							<!-- <?
							$rater_id=$_vars['category'] . $_vars['sku'];
							$rater_item_name= strtoupper($prod->name);
							include("rater.php");
							?> -->

						<?php } ?>

					</div>
					<div id="products_tout_line2" style="margin-bottom:10px;"></div>

					<?php if (in_array('product_specs.php',$php_self_exploded)) { ?>

						<div id="" class="BlueWhite" style="margin-bottom:10px; width:490px; text-align:center;">IMAGES LOAD TIMES WILL VARY WITH CONNECTION SPEEDS</div>

						<?php
						$letterCount = 0;
						$sku = substr($_result['sku'],0,4);
						$Alphabet = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");

						while(file_exists("../images/products/".$sku."/".$_result['sku'].$Alphabet[$letterCount].".jpg")) { ?>

							<div id="products_tout_image_container<? pv($Alphabet[$letterCount]); ?>" style="text-align:center; width:490px;">

								<a href="http://www.timetraditions.com<?php if (OddOrEven($letterCount) == 0) { ?>/cart/index.php?category=<? pv($_vars['category']); ?>#<? pv($_result['sku']); } ?>" class="" title="<? pv($CatName['name']); ?> <?php pv(str_replace($CatName['name'],"",$_result['name'])); ?> Replica Watches">
									<img class="border" src="<? pv($CFG->host); ?>/images/products/<? pv($sku); ?>/<? pv($_result['sku']).pv($Alphabet[$letterCount]); ?>.jpg" alt="<? pv($CatName['name']); ?> <?php pv(str_replace($CatName['name'],"",$_result['name'])); ?> Replica Watches">
								</a>

							</div>

							<script type="text/javascript">
								var so = new SWFObject("<? pv($CFG->host); ?>/flash/image.swf","products_tout_image_container<? pv($Alphabet[$letterCount]); ?>","434","326","7","#7E7E7E");
								so.addVariable("img_src","/images/products/<? pv($sku); ?>/<? pv($_result['sku']); ?><? pv($Alphabet[$letterCount]); ?>.jpg");
								so.addVariable("watermark_src","/flash/image_watermark.swf");
								so.addVariable("watermark_alpha","20");
								so.addVariable("fadetime","2");
								so.addVariable("link_url","<? pv($CFG->host); ?>");
								so.addVariable("sku","<? pv($_result['sku']); ?>");
								so.addVariable("category","<? echo $CatName['id']; ?>");
								so.addVariable("host","<? pv($CFG->host); ?>");
								so.write("products_tout_image_container<? pv($Alphabet[$letterCount]); ?>");
							</script>

							<br>

							<?
							$letterCount++;
						}

					}else{ ?>

						<div id="products_tout_read">

							<div id="tout_images" style="float:right; padding:0 10px 0 0;"><img class="fit_image" src="<? pv($CFG->host); ?>/images/site/white_arrow.png" alt="" style="width:12px; height:12px;"></div>
							<div id="" class="" style="padding:1px 3px 0 0; font-size:12px; float:right;"><a href="<? echo $_vars['ProductLink']; ?>" class="BlueWhiteLink">View more images and information about this <?php pv(str_replace($CatName['name'],"",$_result['name'])); ?></a></div>

							<br class="clear">

						</div>

					<?php } ?>

				</div>

				<div id="products_title"><h3><a href="<? echo $_vars['ProductLink']; ?>" title="<? echo $_vars['ProductTitle']; ?>" class="WhiteBlueLink"><?php pv(str_replace($CatName['name'],"",$_result['name'])); ?></a></h3></div>

				<div id="products_price">

					<? if ($_result['SalePriceEach'] > 0){ ?>

						<span class="Red" style="font-size:20px;"><? printf("$%.2f", $_result['SalePriceEach']); ?></span>
						<span class="StrikeThrough" style="font-size:14px;" ><? printf("$%.2f", $_result['PriceEach']); ?></span>

					<? }else{ ?>

						<? printf("$%.2f", $_result['PriceEach']); ?>

					<? }?>

				</div>

				<? if($_result['single_inventory'] < 1){ ?>

					<!-- <div id="products_out_of_stock"><a href="<? echo $CFG->host ?>/cart/product_request.php?id=<? pv($_result['id']); ?>&name=<? pv($_result['name']); ?>&cat_name=<? pv($CatName['name']); ?>&category=<? pv($_vars['category']); ?>&sku=<? pv($_result['sku']); ?>"><img name="no_stock" src="<? echo $CFG->host ?>/images/site/out_of_stock.png" style="" class="noborder"></a></div> -->

				<?php }else{ ?>

					<form action="add_item.php" method="post" name="thisform">
					<input type="hidden" name="category_id" value="<? echo $_vars['category']; ?>">
					<input type="hidden" name="product_id" value="<? echo $_result['id']; ?>">
					<input type="hidden" name="qty" value="1">

						<div id="products_basket" class="BlueRed"><input type="image" name="submit" src="<? echo $CFG->host ?>/images/site/basket.png" class="" style="width:48px; height:47px;"><br>Add to<br>Basket</div>

					</form>

				<?php } ?>

				<div id="swf_spec_<? pv($_result['sku']); ?>" class="border" style="z-index:2; position:absolute; left:10px; top:30px; width:215px; height:161px;">

					<a href="http://www.timetraditions.com/cart/index.php?category=<? pv($_vars['category']); ?>" title="<? pv($CatName['name']); ?> <?php pv(str_replace($CatName['name'],"",$_result['name'])); ?> Replica Watches">
						<img src="<? echo $CFG->host ?>/images/products/<? pv(substr($_result['sku'],0,4)); ?>/<? pv($_result['sku']); ?>.jpg" alt="<? pv($CatName['name']); ?> <?php pv(str_replace($CatName['name'],"",$_result['name'])); ?> Replica Watches" style="width:215px; height:161px;">
					</a>

				</div>

				<script type="text/javascript">
					var so = new SWFObject("<? pv($CFG->host); ?>/flash/image.swf","swf_spec_<? pv($_result['sku']); ?>","215","161","7","#7E7E7E");
					so.addVariable("img_src","/images/products/<? pv(substr($_result['sku'],0,4)); ?>/<? pv($_result['sku']); ?>.jpg");
					so.addVariable("watermark_src","/flash/image_watermark.swf");
					so.addVariable("watermark_alpha","20");
					so.addVariable("fadetime","2");
					so.addVariable("link_url","<? pv($CFG->host); ?>");
					so.addVariable("sku","<? pv($_result['sku']); ?>");
					so.addVariable("category","<? echo $_vars['category']; ?>");
					so.addVariable("host","<? pv($CFG->host); ?>");
					so.write("swf_spec_<? pv($_result['sku']); ?>");
				</script>

			</div>

		</div>
		<?php /*   end products_tout   */ ?>

		<? $x++; ?>

	<? } ?>

<? } ?>

<br>

<? if ($_vars['category'] > 10000) { $CatName['name'] = ''; } ?>