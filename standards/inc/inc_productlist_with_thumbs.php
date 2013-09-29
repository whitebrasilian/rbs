<? ////////////  NEXT PREVIOUS TABLE ////////////////// ?>

<? if (in_array('index.php',$php_self_exploded)) { ?>

<table width=<? pv($SITE->ManagerWidth); ?> border=0 cellspacing=0 cellpadding=5>
	<tr>
		<td align='center' colspan="2" class="PageHeaders">
			<b><? pv($totalrows); ?> <? pv($SITE->KeyWord); ?> <? pv($CatName[name]); ?>'s To Choose From</b> - 
		<!-- </td>
	 </tr> -->

	<? if ($totalrows > $limit) { ?>

		  <!-- <tr>
			<td colspan="2" align='center'> -->

			<?
			// create a start value
			$start = ($_vars[page] - 1) * $limit;

			// Showing Results 1 to 1 (or if you're page limit were 5) 1 to 5, etc.
			$starting_no = $start + 1;

			if ($totalrows - $start < $limit) {
			   $end_count = $totalrows;
			} else if ($totalrows - $start >= $limit) {
			   $end_count = $start + $limit;
			} ?>
			
			<!-- <?
			if($_vars[page] != 1){  
				$pageprev = $_vars[page] -1; ?>

				<a class="LightCopyLink" href='<? echo $CFG->host ?>/cart/index.php?page=<? pv($pageprev); ?>&category=<? pv($_vars[category]) ?>'><u>PREV</u></a>&nbsp;

			<? }else { ?>

				&nbsp;&nbsp;PREV&nbsp;

			<? } ?> -->

			<?
			$numofpages = ceil($totalrows / $limit);

			for($i = 1; $i <= $numofpages; $i++){ 

				if($i == $_vars[page]) {
					echo $i."&nbsp;"; 
				}else { ?>
					<a class="LightCopyLink" href='<? echo $CFG->host ?>/cart/index.php?page=<? pv($i); ?>&category=<? pv($_vars[category]) ?>'><u><? echo $i; ?></u></a>&nbsp;
					<?
				}

			}

			if(($totalrows % $limit) >= 9){

				if($i == $_vars[page]) {
					echo $i."&nbsp;"; 
				}else{ ?>
					<a class="LightCopyLink" href='<? echo $CFG->host ?>/cart/index.php?page=<? pv($i); ?>&category=<? pv($_vars[category]) ?>'><u><? echo $i; ?></u></a>&nbsp;
			<? }

			}

			if(($totalrows - ($limit * $_vars[page])) > 0){ 

				$pagenext = $_vars[page] + 1; ?>
				<a class="LightCopyLink" href='<? echo $CFG->host ?>/cart/index.php?page=<? pv($pagenext); ?>&category=<? pv($_vars[category]) ?>'><u>More <? pv($CatName[name]); ?> <? pv($SITE->KeyWord); ?>...</u></a>

			<? } ?>

			</td>
		</tr>

	<? }else{ ?>

		<tr>
		  <td align='center' >&nbsp;</td>
		</tr>

	<? } ?>

</table>

<? } ?>

<? /////////////////////  end nexy previous /////////////////////// ?>

<? 
if($SITE->NumberOfColumns == "1") {

	$x=0;
	while ($_result = db_fetch_array($qid)) {

		/*	**********************************************************	*/
		/*	This part is for when the display block is seen in manager	*/
		/*	**********************************************************	*/
		if (in_array('order.php',$php_self_exploded)) {

			$qid = db_query("SELECT * FROM products as p, products_categories as pc WHERE p.id = '$_result[product_id]' AND p.id = pc.product_id");
			$_result = db_fetch_array($qid);

			if($_result[sendBackType] == '' || $_result[sendBackType] == 'Repair'){

				if($_result[SalePriceEach]){

					$subtotal = $subtotal + $_result[SalePriceEach];

				}else{

					$subtotal = $subtotal + $_result[PriceEach];

				}
			}
		}

		/*	**********************************************************	*/
		/*	**********************************************************	*/
		
		if (is_null($CatName)) {
			
			$_result[cat_abrv] = substr($_result[sku],0,4);
			$catquery = db_query("SELECT id, name, cat_abrv FROM categories WHERE cat_abrv = '$_result[cat_abrv]'");
			$CatName = db_fetch_array($catquery);

		}elseif ($_vars[category] > 10000) {

			$qid_cats = db_query("SELECT c.id, c.cat_abrv, pc.category FROM categories as c, products_categories as pc WHERE c.id = pc.category_id AND pc.product_id = '$_result[id]'");
			$CatName = db_fetch_array($qid_cats);
			$CatName['name'] = $CatName['category'];

			if ($_vars[category] == 999999) { $CatName[description] = 'Newest Items'; }
			elseif ($_vars[category] == 888888) { $CatName[description] = 'Sale Items'; }
		}

		$SITE->ShowMoreInfo = "";
		$_vars[ProductLink] = "$CFG->host/cart/product_specs.php?id=$_result[id]&category=$CatName[id]&parent=$_vars[parent]";
		$_vars[ProductTitle] = "More info on Product #$_result[sku] - $_result[name]";
	
		if($_result[long_desc] != "") {
			$SITE->ShowMoreInfo = "Yes";
		}
		?>

		<table width=<? pv($SITE->ManagerWidth); ?> border=0 cellspacing=0 cellpadding=0>
			<tr>
				<td width=224 height=250 style="background-color:#878787;" valign="top">

					<table width= border=0 cellspacing=0 cellpadding=0>
						<tr>
							<td height=173 width=224 valign="bottom" align="center">
							<a name="<? pv($_result[sku]); ?>">
							<? if($SITE->ShowImage == "Yes") {
								
								//$_vars[category] = $CatName[id]; ?>

								<? 
								$rand = get_rand_id(3);
								//pre('/images/products/'.substr($_result[sku],0,4).'/'.$_result[sku].'.jpg');

								?>
								<div id="flashcontent<? pv($rand); ?>">
									<span class="label">You need to upgrade your Flash Player</span>
								</div> 
								<script type="text/javascript">
								   var fo = new FlashObject("../swf/image.swf?img_src=/images/products/<? pv(substr($_result[sku],0,4)); ?>/<? pv($_result[sku]); ?>.jpg&link_url=product_specs.php&sku=<? pv($_result[sku]); ?>&category=<? pv($_vars[category]); ?>&page=<? pv($_vars[page]); ?>&host=<? pv($CFG->host); ?>", "nav", "215", "163", "8", "#7E7E7E");
								   fo.addParam("wmode", "transparent");
								   fo.addParam("scale", "noscale");
								   fo.write("flashcontent<? pv($rand); ?>");
								</script>

							<? } ?>

							</td>
						</tr>
						<tr>
							<td height=20>
								<div id="swf<? pv($f); ?>"></div>
								<script type="text/javascript">
									var so = new SWFObject("<? echo $CFG->host ?>/flash/swftib.swf", "swf<? pv($f); ?>", "224", "30", "8", "#515151");
									so.addParam("scale", "noScale");
									so.addVariable("host_path", "<? echo $CFG->host ?>");
									so.addVariable("tib_str", "VIEW MORE IMAGES OF WATCH");
									so.addVariable("tib_txt", "0xFFFFFF");
									so.addVariable("tib_bg", "0x000000,0");
									so.addVariable("tib_link", "<? echo $CFG->host ?>/cart/product_specs.php");
									so.addVariable("tib_link_vars", "sku=<? pv($_result[sku]); ?>,category=<? pv($_vars[category]); ?>,page=<? pv($_vars[page]); ?>");
									so.addVariable("in_ani_0", "txt,slide,right,.5,easeOutSine");
									so.addVariable("over_ani_0", "txt,slide,up,.25,easeOutBack");
									so.addVariable("over_ani_1", "bg,fade,100,0");
									so.addVariable("out_ani_0", "bg,fade,0,.25,easeOutSine");
									so.write("swf<? pv($f); $f++; ?>");
								</script>
							</td>
						</tr>
						 <tr> 
							<td align="center" class="red11">

							<!-- <TABLE width=224 border=0 cellspacing=0 cellpadding=0>
									
									<?
									$letterCount = 0;
									$a = 0;
									$sku = substr($_result[sku],0,4);
									$Alphabet = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
									while(file_exists("../images/products/".$sku."/".$_result[sku].$Alphabet[$letterCount].".jpg")) {

											if ($a == 0) { echo"<tr><TD align='center' valign='middle' width=50>"; } ?>

											<? $rand = get_rand_id(3); ?>
											<div id="flashcontent<? pv($rand); ?>">
													<span class="label">You need to upgrade your Flash Player</span>
											</div> 
											<script type="text/javascript">
											   var fo = new FlashObject("../swf/image.swf?img_src=/images/products/<? pv(substr($_result[sku],0,4)); ?>/<? pv($_result[sku]); ?><? pv($Alphabet[$letterCount]); ?>.jpg&host=<? pv($CFG->host); ?>&fadetime=1", "nav", "50", "38", "8", "#f1e9cd");
											   fo.addParam("wmode", "transparent");
											   fo.addParam("scale", "noscale");
											   fo.write("flashcontent<? pv($rand); ?>");
											</script>

											<? if ($a < 3) {  echo"</TD><td align='center' valign='middle' width=50>"; $a++;  }else{  echo"</TD></tr>"; $a=0; }?>
									
									<? $letterCount++;
									} ?>

									<? if ($a == 1) { echo"</TD><td colspan=2></TD></tr>"; }elseif ($a == 2) { echo"</TD><td></TD></tr>"; } ?>

							</TABLE> -->

							</td>
						</tr>
					</table>

				</td>
				<td width=10></td>
				<td width=436 style="background-color:#878787;" valign="top">

					<table width= border=0 cellspacing=0 cellpadding=0>
						<tr>
							<td width=13 height=30 rowspan=2></td>
							<th colspan=3>

								<table width=410 border=0 cellspacing=0 cellpadding=3 class="PageHeaders">
									<tr>
										<td align="left">

										<? if($SITE->ShowMoreInfo == "Yes") { ?>

											<a class="PageHeadersLink" href="<? echo $_vars[ProductLink]; ?>" title="<? echo $_vars[ProductTitle]; ?>">

										<? } ?>
										
										<? if($SITE->ShowProductName == "Yes") { ?>

											<? pv($CatName[name]) ?> <? if ($_result[name] != $CatName[name]) { pv($_result[name]); } ?>
											
										<? } ?>
										
										<? if($SITE->ShowMoreInfo == "Yes") { ?></a><? } ?>		
								
										</td>
										<td align="right">
							
										<? if($SITE->ShowProductID == "Yes") { ?>
										
											<B># <? pv(substr($_result[sku],4)) ?></B>
											
										<? } ?>

										</td>
									</tr>
								</table>
								
							</td>
							<td width=13 rowspan=2></td>
						</tr>
						<tr>
							<td colspan=3 bgcolor="#7E7E7E" valign="top">

								<table width=410 border=0 cellspacing=0 cellpadding=5>
									<tr>
										<td align="left" height=60 style="font-size:12px;"><? if($SITE->ShowShortDesc == "Yes") { ?><? echo nl2br($_result[description]) ?><? } ?></td>
									</tr>
								</table>
							
							</td>
						</tr>
						<tr>
							<td width=436 height=8 colspan=5></td>
						</tr>
						<tr>
							<td width=13 height=94></td>
							<td bgcolor="#7E7E7E">

								<table width=217 border=0 cellspacing=0 cellpadding=2 style="margin-left:10px;margin-right:10px;">

									<? /*	SELECT and LOOP Att's that belong to this category*/
									$qdb = db_query("SELECT id, parent_id, child_id FROM attributes WHERE product_id = '$_result[id]'");
									while($row = db_fetch_array($qdb)) {

										/*	Get name of Parent Att.	*/
										$qax = db_query("SELECT name FROM attributes WHERE id = '$row[parent_id]'");
										$rax = db_fetch_array($qax);

										/*	Get name of Child Att.	*/
										$qdx = db_query("SELECT name FROM attributes WHERE id = '$row[child_id]'");
										$rox = db_fetch_array($qdx); ?>

										<tr style="font-size:12px;">
											<th width=80 align="right" class="LightCopy"><? pv($rax[name]); ?></td>
											<td width=152 align="left"><? pv($rox[name]); ?></td>
										</tr>

									<? } ?>

								</table>

							</td>
							<td width=28></td>
							<td bgcolor="#7E7E7E" align="center" valign="bottom">

								<? if (in_array('shopping_cart.php',$php_self_exploded)) { $form = "CartItem"; ?>

									<form action="shopping_cart.php" method="post" name="CartItem">

								<? }else{ $form = "AddItem"; ?>

									<form action="add_item.php" method="post" name="AddItem" onsubmit="return checkform(this);">

								<? } ?>

								<table width=150 border=0 cellspacing=0 cellpadding=0>
									<tr valign="middle">
										<th style="" height=50 align="center">

											<? if($SITE->ShowPrice == "Yes") { ?>

												<!-- Price<br> -->

												<? if ($_result[SalePriceEach] != 0){ ?>
													<span class="Red" style="font-size: 18px;"><? printf("$%.2f", $_result[SalePriceEach]); ?></span><br>
													<strike><? printf("$%.2f", $_result[PriceEach]); ?></strike>

												<? }else { ?>

													<? printf("$%.2f", $_result[PriceEach]); ?> 

												<? }?>

											<? } ?>
											
										</td>
										<th align="left" valign="middle">
										
											<? if($SITE->ShowQuantityBox == "Yes") { ?>
											
												&nbsp;&nbsp;<span class="" style="font-size:16px;">X</span>&nbsp;&nbsp;&nbsp;
												<input type="text" name="qty" style="width:20px;" value=<? if (in_array('shopping_cart.php',$php_self_exploded)) { pv($_result[Qty]); ?><? }else{ ?>1<? } ?>>
												
											<? } else { ?>
											
												<input type="hidden" name="qty">
												
											<? } ?>

										</td>
									</tr>

									<? if (in_array('shopping_cart.php',$php_self_exploded)) { ?>

										<input type="hidden" name="ProductID" value=<? pv($_result[id]); ?>>

										<tr align="center">
											<td colspan=2>
												<div id="swf<? pv($f); ?>"></div>
												<script type="text/javascript">
													var so = new SWFObject("<? echo $CFG->host ?>/flash/swftib.swf", "swf<? pv($f); ?>", "150", "20", "8", "#515151");
													so.addParam("scale", "noScale");
													so.addVariable("host_path", "<? echo $CFG->host ?>");
													so.addVariable("tib_str", "REMOVE");
													so.addVariable("tib_txt", "0xFFFFFF");
													so.addVariable("tib_bg", "0x000000,0");
													so.addVariable("tib_js", "frmsubmit");
													so.addVariable("tib_js_vars", "submit=submit");
													so.addVariable("in_ani_0", "txt,slide,right,.5,easeOutSine");
													so.addVariable("over_ani_0", "txt,slide,up,.25,easeOutBack");
													so.addVariable("over_ani_1", "bg,fade,100,0");
													so.addVariable("out_ani_0", "bg,fade,0,.25,easeOutSine");
													so.write("swf<? pv($f); $f++; ?>");
												</script>

											<!-- <div id="btn<? pv($f); ?>"></div>
											<script type="text/javascript">
												var so = new SWFObject("<? pv($CFG->host); ?>/flash/nav_button.swf","Button<? pv($f); ?>","150","20","8","#515151");
												so.addVariable("btntxt","REMOVE");
												so.addVariable("swfid","<? pv($f); ?>");
												so.addVariable("link","javascript:frmsubmit('remove')");
												so.addVariable("btn_color","0x000000");
												so.addVariable("txt_color","0xffffff");
												so.addVariable("var1","<? pv($_result[id]); ?>");
												so.write("btn<? pv($f); $f++; ?>");
											</script> -->

											</td>
										</tr>
										<tr align="center">
											<td colspan=2>

											<div id="btn<? pv($f); ?>"></div>
											<script type="text/javascript">
												var so = new SWFObject("<? pv($CFG->host); ?>/flash/nav_button.swf","Button<? pv($f); ?>","150","20","8","#515151");
												so.addVariable("btntxt","UPDATE");
												so.addVariable("swfid","<? pv($f); ?>");
												so.addVariable("link","javascript:frmsubmit('recalc')");
												so.addVariable("btn_color","0x000000");
												so.addVariable("txt_color","0xffffff");
												so.addVariable("var1","<? pv($_result[id]); ?>");
												so.write("btn<? pv($f); $f++; ?>");
											</script>

											</td>
										</tr>

									<? }elseif($_result[single_inventory] < 1){ ?>

										<tr>
											<td colspan=2 align="center">

											<div id="btn<? pv($f); ?>"></div>
											<script type="text/javascript">
												var so = new SWFObject("<? pv($CFG->host); ?>/flash/nav_button.swf","Button<? pv($f); ?>","150","20","8","#515151");
												so.addVariable("btntxt","NOT IN STOCK");
												so.addVariable("swfid","<? pv($f); ?>");
												so.addVariable("link","../modules/product_request.php");
												so.addVariable("btn_color","0x000000");
												so.addVariable("txt_color","0xffffff");
												so.addVariable("var1","<? pv($_result[id]); ?>");
												so.addVariable("var2","<? pv($_result[name]); ?>");
												so.addVariable("var3","<? pv($CatName[name]); ?>");
												so.write("btn<? pv($f); $f++; ?>");
											</script>

											</td>
										</tr>
										<tr align="center">
											<td colspan=2>
												<div id="swf<? pv($f); ?>"></div>
												<script type="text/javascript">
													var so = new SWFObject("<? echo $CFG->host ?>/flash/swftib.swf", "swf<? pv($f); ?>", "150", "20", "8", "#515151");
													so.addParam("scale", "noScale");
													so.addVariable("host_path", "<? echo $CFG->host ?>");
													so.addVariable("tib_str", "SPECIAL REQUEST IT");
													so.addVariable("tib_txt", "0xFFFFFF");
													so.addVariable("tib_bg", "0x000000,0");
													so.addVariable("tib_link", "<? echo $CFG->host ?>/modules/product_request.php");
													so.addVariable("tib_link_vars", "var1=<? pv($_result[id]); ?>,var2=<? pv($_result[name]); ?>,var3=<? pv($CatName[name]); ?>");
													so.addVariable("in_ani_0", "txt,slide,right,.5,easeOutSine");
													so.addVariable("over_ani_0", "txt,slide,up,.25,easeOutBack");
													so.addVariable("over_ani_1", "bg,fade,100,0");
													so.addVariable("out_ani_0", "bg,fade,0,.25,easeOutSine");
													so.write("swf<? pv($f); $f++; ?>");
												</script>

											<!-- <div id="btn<? pv($f); ?>"></div>
											<script type="text/javascript">
												var so = new SWFObject("<? pv($CFG->host); ?>/flash/nav_button.swf","Button<? pv($f); ?>","150","20","8","#515151");
												so.addVariable("btntxt","SPECIAL REQUEST IT");
												so.addVariable("swfid","<? pv($f); ?>");
												so.addVariable("link","../modules/product_request.php");
												so.addVariable("btn_color","0x000000");
												so.addVariable("txt_color","0xffffff");
												so.addVariable("var1","<? pv($_result[id]); ?>");
												so.addVariable("var2","<? pv($_result[name]); ?>");
												so.addVariable("var3","<? pv($CatName[name]); ?>");
												so.write("btn<? pv($f); $f++; ?>");
											</script> -->

											</td>
										</tr>
										
									<? }elseif (!in_array('shopping_cart.php',$php_self_exploded)) { ?>

										<tr>
											<td colspan=2 align="center">

												<input type="hidden" name="category_id" value="<? echo $_vars[category]; ?>">
												<input type="hidden" name="product_id" value="<? echo $_result[id]; ?>">

												<? if($SITE->CustomButtons == "Yes"){ ?>

													<input name='submit' type='image' src="<? echo $CFG->host ?>/images/site/CartIcon.jpg" alt='Add to Cart'>

												<? }else{ ?>

												<div id="swf<? pv($f); ?>"></div>
												<script type="text/javascript">
													var so = new SWFObject("<? echo $CFG->host ?>/flash/swftib.swf", "swf<? pv($f); ?>", "150", "20", "8", "#515151");
													so.addParam("scale", "noScale");
													so.addVariable("host_path", "<? echo $CFG->host ?>");
													so.addVariable("tib_str", "ADD TO CART");
													so.addVariable("tib_txt", "0xFFFFFF");
													so.addVariable("tib_bg", "0x000000,0");
													so.addVariable("tib_js", "submitMe");
													so.addVariable("tib_js_vars", "<? echo $form ?>");
													so.addVariable("in_ani_0", "txt,slide,right,.5,easeOutSine");
													so.addVariable("over_ani_0", "txt,slide,up,.25,easeOutBack");
													so.addVariable("over_ani_1", "bg,fade,100,0");
													so.addVariable("out_ani_0", "bg,fade,0,.25,easeOutSine");
													so.write("swf<? pv($f); $f++; ?>");
												</script>

													<!-- <div id="btn<? pv($f); ?>"></div>
													<script type="text/javascript">
														var so = new SWFObject("<? pv($CFG->host); ?>/flash/nav_button.swf","Button<? pv($f); ?>","150","20","8","#515151");
														so.addVariable("btntxt","ADD TO CART");
														so.addVariable("swfid","<? pv($f); ?>");
														so.addVariable("link","javascript:submit()");
														so.addVariable("btn_color","0x000000");
														so.addVariable("txt_color","0xffffff");
														so.addVariable("var1","<? pv($_result[id]); ?>");
														so.addVariable("var2","<? pv($_result[name]); ?>");
														so.addVariable("var3","<? pv($CatName[name]); ?>");
														so.write("btn<? pv($f); $f++; ?>");
													</script> -->
													<!-- <input class='Copy' type="submit" value="Add to Cart"> -->

												<? } ?>

											</td>
										</tr>
										
									<? } ?>

								</table>

								</form>

							</td>
							<td width=13></td>
						</tr>
						<tr>
							<td width=436 height=21 colspan=5></td>
						</tr>
					</table>
				</td>
			</tr>
			<tr>
			  <td colspan=5 width=<? pv($SITE->ManagerWidth); ?> height=20 valign="middle"><img src="<? echo $CFG->host ?>/images/site/DoubleLines.png" alt="" width=<? pv($SITE->ManagerWidth); ?> height=4></td>
			</tr>
		</table>

	<? $x++;
	}

/*	**********************************************************	*/
/*	**********************	End Single Display	**************	*/

} else {

/*	**********************************************************	*/
/*	*********************	Begin Multiple Display	**********	*/

?>
	<table border="0" cellpadding="4" cellspacing="0" width="100%">
		<tr>
			<td bgcolor="#<? pv($SITE->HeaderBannerColor) ?>" height="2"></td>
		</tr>

			<?
			$_vars[columnWidth] = @round(100 / $SITE->NumberOfColumns);
			$_vars[rowcount] = db_num_rows($qid);
			for($i=0;$i<$_vars[rowcount];$i++) {

				$prod = db_fetch_array($qid);

				$_vars[Image1Thumbnail] = "/products/".$_result[sku];//<!-- ."_a"_01_th -->
				$_vars[Image1Large] = "/products/".$_result[sku]."_a";
				$_vars[ProductLink] = "/cart/product_specs.php?id=$_result[id]&category=$CatName[id]&parent=$_vars[parent]";
				$_vars[ProductTitle] = "More info on Product #$_result[sku] - $_result[name]";

				if($_result[long_desc] != "") {
					$SITE->ShowMoreInfo = "Yes";
				} else {
					$SITE->ShowMoreInfo = "";
				}

				if($i % $SITE->NumberOfColumns == 0) {
				?>

				<tr>

				<? } ?>

					<td valign="top" align="center" width="<? echo $_vars[columnWidth]; ?>%">

						<form action="<? echo $CFG->host ?>/cart/add_item.php" method="post" name="AddItem" onsubmit="return checkform(this);">

							<table border="0" cellspacing="0" cellpadding="1">

								<? if($SITE->ShowImage == "Yes") { ?>

									<tr>
										<td align="center" class="Copy">

										<? if($SITE->ShowMoreInfo == "Yes") { 
											echo "<a class=\"CopyLink\" href=\"$_vars[ProductLink]\" title=\"$_vars[ProductTitle]\">";
										}

										$specialThumbnail = "$CFG->host/images/products/".$_result[sku];//<!-- ."_a" -->
										SpecialsImages($specialThumbnail,'125','windowImageBorder','1',$SITE->HeaderBannerColor,'','','0'); 

										if($SITE->ShowMoreInfo == "Yes") { 
											echo "</a>";
										}
										?>

										</td>
									</tr>

								<? } ?>

									<tr height=30>
										<th align="center" class="Accent" width="90%"><? //Image_Title
										if($SITE->ShowMoreInfo == "Yes") {
											echo "<a class=\"CopyLink\" href=\"$_vars[ProductLink]\" title=\"$_vars[ProductTitle]\">";
										}
										if($SITE->ShowProductName == "Yes") {
											pv($_result[name]);
										}
										if($SITE->ShowProductID == "Yes") {
										splicein($_result[sku],"&nbsp;- #");
										}
										if($SITE->ShowMoreInfo == "Yes") {
											echo "</a>";
										}
										?></td>
									</tr>
									<tr>
										<td align="center" class="Copy">

											<TABLE border="0" cellpadding="0" cellspacing="0" width="100%">

											<? if($SITE->CasePrices == "Yes") { ?>

													<TR>
														<th align="center" width="49%" class="Copy">Single</TD>
														<th align="center" width="1%" bgcolor="#<? pv($SITE->HeaderBannerColor) ?>"></TD>
														<Th align="center" width="50%" class="Copy">Case of <? pv($_result[quanity_in_case]) ?></TD>
													</TR>

												<? if($SITE->ShowPrice == "Yes") { ?>

													<TR>
														<TD align="center" class="Copy"><b>
														<? if($_result[SalePriceEach]){ ?>
															<a class='RedLine'>$<? pv($_result[PriceEach]) ?>.00</a>
															<br>
															<b><a class='Red'>Sale Price<br>$<? pv($_result[SalePriceEach]) ?>.00</a></b>
														<? }else{ ?>
															$<? pv($_result[PriceEach]) ?>.00
														<? } ?>
														</b></TD>
														<th align="center" bgcolor="#<? pv($SITE->HeaderBannerColor) ?>"></TD>
														<TD align="center" class="Copy"><b>
														<? if($_result[CaseSalePriceEach]){ ?>
															<a class='RedLine'>$<? pv($_result[CasePriceEach]) ?>.00</a>
															<br>
															<b><a class='Red'>Sale Price<br>$<? pv($_result[CaseSalePriceEach]) ?>.00</a></b>
														<? }else{ ?>
															$<? pv($_result[CasePriceEach]) ?>.00
														<? } ?>
														</b></TD>
													</TR>

												<? } ?>

												<TR>
													<TD align="center" class="Copy"><? if($SITE->ShowQuantityBox == "Yes") { ?>Qty:<input type="text" name="qty" size="1"><? } else { ?><input type="hidden" name="qty"><? } ?></TD>
													<th align="center" width="1%" bgcolor="#<? pv($SITE->HeaderBannerColor) ?>"></TD>
													<TD align="center" class="Copy"><? if($SITE->CasePrices == "Yes") { ?>Case Qty:<input type="text" name="case_inventory" size="1"><? } else { ?><input type="hidden" name="case_inventory"><? } ?></TD>
												</TR>

											<? }else{ ?>

													<? if($SITE->ShowPrice == "Yes") { ?>

														<TR>
															<TD align="center" class="Copy" colspan=2><b>

															<? if($_result[SalePriceEach]){ ?>

																<a class='RedLine'>$<? pv($_result[PriceEach]) ?>.00</a>
																<br>
																<b><a class='Red'>Sale Price<br>$<? pv($_result[SalePriceEach]) ?>.00</a></b>

															<? }else{ ?>

																$<? pv($_result[PriceEach]) ?>.00

															<? } ?>

															</b></TD>
														</TR>

													<? } ?>

													<TR>
														<TD align="center" class="Copy"><? if($SITE->ShowQuantityBox == "Yes") { ?>Qty:<input type="text" name="qty" size="1"><? } else { ?><input type="hidden" name="qty"><? } ?></TD>
													</TR>

											<? } ?>

											</TABLE>

										</td>
									</tr>

								<? if($SITE->ShowShortDesc == "Yes") { ?>

									<tr>
										<td align="center">

											<TABLE width="90%">
												<TR>
													<TD valign="top" align="center" class="Copy" style='text-align:left;'><? echo nl2br($_result[description]) ?></TD>
												</TR>
											</TABLE>

										</td>
									</tr>

								<? } ?>

								<? if($SITE->ShowMoreInfo == "Yes") { ?>

									<tr>
										<td align="center" class="Copy"><b>

										<? if($_result[long_desc] != "") {
											echo "<a href=\"$_vars[ProductLink]\" title=\"$_vars[ProductTitle]\">More Info</a>";
										} ?>
										
										</b></td>
									</tr>

								<? } ?>

								<? if($SITE->ShowBuyButton == "Yes") { ?>

									<tr>
										<td align="center" class="Copy"><? Product_Attribute_List($_result[id]); ?></td>
									</tr>
									<tr>
										<td align="center" class="Copy">

										<? if($SITE->CustomButtons == "Yes"){ ?>

											<input name='submitButtonName' type='image' src="<? echo $CFG->host ?>/images/site/Purchase.jpg" alt='Purchase'>

										<? }else{ ?>

											<input name='submitButtonName' type="submit" value="Purchase">

										<? } ?>

										</td>
									</tr>

								<? } ?>

								<TR>
									<TD height="9">&nbsp;</TD>
								</TR>
							</table>

						<input type="hidden" name="product_id" value="<? echo $_result[id]; ?>">
						</form>

					</td>

					<? if(($i % $SITE->NumberOfColumns) == ($SITE->NumberOfColumns - 1) || ($i + 1) == $_vars[rowcount]) { ?>
				</tr>

			<? } ?>

		<? } ?>

	</table>

<? } ?>

<br>

<? if ($_vars[category] > 10000) { $CatName[name] = ''; } ?>

<? ////////////  NEXT PREVIOUS TABLE ////////////////// ?>

<table width=<? pv($SITE->ManagerWidth); ?> border=0 cellspacing=0 cellpadding=5>

	<? if ($totalrows > $limit) { ?>

		  <tr>
			<td colspan="2" align='center' class="PageHeaders">

			<?
			// create a start value
			$start = ($_vars[page] - 1) * $limit;

			// Showing Results 1 to 1 (or if you're page limit were 5) 1 to 5, etc.
			$starting_no = $start + 1;

			if ($totalrows - $start < $limit) {
			   $end_count = $totalrows;
			} else if ($totalrows - $start >= $limit) {
			   $end_count = $start + $limit;
			}

			if($_vars[page] != 1){  
				$pageprev = $_vars[page] -1; ?>

				<a class="LightCopyLink" href='<? echo $CFG->host ?>/cart/index.php?page=<? pv($pageprev); ?>&category=<? pv($_vars[category]) ?>'><u>PREV</u></a>&nbsp;

			<? }

			$numofpages = ceil($totalrows / $limit);

			for($i = 1; $i <= $numofpages; $i++){ 

				if($i == $_vars[page]) {
					echo $i."&nbsp;"; 
				}else { ?>
					<a class="LightCopyLink" href='<? echo $CFG->host ?>/cart/index.php?page=<? pv($i); ?>&category=<? pv($_vars[category]) ?>'><u><? echo $i; ?></u></a>&nbsp;
					<?
				}

			}

			if(($totalrows % $limit) >= 9){

				if($i == $_vars[page]) {
					echo $i."&nbsp;"; 
				}else{ ?>
					<a class="LightCopyLink" href='<? echo $CFG->host ?>/cart/index.php?page=<? pv($i); ?>&category=<? pv($_vars[category]) ?>'><u><? echo $i; ?></u></a>&nbsp;
			<? }

			}

			if(($totalrows - ($limit * $_vars[page])) > 0){ 

				$pagenext = $_vars[page] + 1; ?>
				<a class="LightCopyLink" href='<? echo $CFG->host ?>/cart/index.php?page=<? pv($pagenext); ?>&category=<? pv($_vars[category]) ?>'><u>MORE <? pv(strtoupper($SITE->KeyWord)); ?> <? pv(strtoupper($CatName[name])); ?>'S ...</u></a>

			<? } ?>

			</td>
		</tr>

	<? }else{ ?>

		<tr>
		  <td align='center' >&nbsp;</td>
		</tr>

	<? } ?>

</table>

<? /////////////////////  end nexy previous /////////////////////// ?>