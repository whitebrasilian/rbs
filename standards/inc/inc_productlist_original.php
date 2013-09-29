<?
if($SITE->NumberOfColumns == "1") {
	while ($prod = db_fetch_object($qid)) {
		$_vars[ShowMoreInfo] = "";
		$_vars[Image1Thumbnail] = "$CFG->host/products/".$prod->prod_id;//<!-- ."_a"_01_th -->
		$_vars[Image1Large] = "$CFG->host/products/".$prod->prod_id."_a";
		$_vars[ProductLink] = "$CFG->host/cart/product_specs.php?id=$prod->id&category=$cat->id&parent=$_vars[parent]";
		$_vars[ProductTitle] = "More info on Product #$prod->prod_id - $prod->name";
	
		if($prod->long_desc != "") {
			$_vars[ShowMoreInfo] = "Yes";
		}
	?>
		<table border="0" cellpadding="0" cellspacing="5" width="95%">
		<tr>
			<td colspan="3" bgcolor="<? pv($SITE->HeaderBannerColor) ?>" height="2"></td>
		</tr>
		<tr>
			<td colspan="3" height="10"></td>
		</tr>
		<tr>
			<? if($SITE->ShowImage == "Yes") { ?>
				<td valign="top" align="left" class="Copy">
					<? if($_vars[ShowMoreInfo] == "Yes") { ?>
						<a href="<? echo $_vars[ProductLink]; ?>" title="<? echo $_vars[ProductTitle]; ?>">
					<? } ?>
					<? $specialThumbnail = "$CFG->baseroot/images/products/".$prod->prod_id; ?><!-- ."_a" -->
					<? SpecialsImages($specialThumbnail,'125','windowImageBorder','1',$SITE->HeaderBannerColor,'','','0'); ?>
					<!-- <? ImageOrText($_vars[Image1Thumbnail], ""); ?> -->
					<? if($_vars[ShowMoreInfo] == "Yes") { ?></a><? } ?>
				</td>
			<? } ?>

			<td valign="top" width="100%" class="Copy">
				<? if($_vars[ShowMoreInfo] == "Yes") { ?>
					<a class="CopyLink" href="<? echo $_vars[ProductLink]; ?>" title="<? echo $_vars[ProductTitle]; ?>"><? } ?><b>
					<? if($SITE->ShowProductName == "Yes") { ?><? pv($prod->name) ?><? } ?>
					<? if($SITE->ShowProductID == "Yes") { ?><? splicein($prod->prod_id,"&nbsp;- #") ?><? } ?></b>
					<? if($_vars[ShowMoreInfo] == "Yes") { ?></a><? } ?><br>
					<? if($SITE->ShowShortDesc == "Yes") { ?>&nbsp;&nbsp;&nbsp;<? echo nl2br($prod->description) ?><? } ?><? if($SITE->ShowShortDesc == "Yes") { ?><br><? if($prod->long_desc != "") { ?><p><a class="CopyLink" href="<? echo $_vars[ProductLink]; ?>" title="<? echo $_vars[ProductTitle]; ?>"><b><u>More Info and Pictures</u></b></a><? } ?><? } ?></td>

			<form action="<? echo $CFG->host ?>/cart/add_item.php" method="post" name="AddItem" onsubmit="return checkform(this);">

				<td valign="top" align="right" width="100" class="Copy">
					<? if($SITE->ShowPrice == "Yes") { ?><b><? splicein($prod->price,"$") ?></b><br><? } ?>

					<? if($SITE->ShowQuantityBox == "Yes") { ?>Quantity:<input class="FormElements" type="text" name="qty" size="4" value="1"><br><? } else { ?><input type="hidden" name="qty" value="1"><? } ?> 

					<? if($SITE->ShowBuyButton == "Yes") { ?>
						<? if($SITE->ProductAttributes == "Yes"){ ?>
							<? Product_Attribute_List($prod->id); ?><br>
						<? } ?>

						<input type="hidden" name="product_id" value="<? echo $prod->id; ?>">

						<? if($SITE->CustomButtons == "Yes"){ ?>
							<input name='submitButtonName' type='image' src="<? echo $CFG->host ?>/images/site/Purchase.jpg" alt='Purchase'>&nbsp;
						<? }else{ ?>
							<input type="submit" value="Purchase">
						<? } ?>
						<br>
					<? } ?>
				</td>

			</form>
		</tr>

	</table>
		
<? } //end while()
} else { ?>
	<table border="0" cellpadding="4" cellspacing="0" width="100%">
		<tr>
			<td bgcolor="<? pv($SITE->HeaderBannerColor) ?>" height="2"></td>
		</tr>
			<?
			$_vars[columnWidth] = @round(100 / $SITE->NumberOfColumns);
			$_vars[rowcount] = mysql_num_rows($qid);
			for($i=0;$i<$_vars[rowcount];$i++) {
				$prod = mysql_fetch_object($qid);
				$_vars[Image1Thumbnail] = "/products/".$prod->prod_id;//<!-- ."_a"_01_th -->
				$_vars[Image1Large] = "/products/".$prod->prod_id."_a";
				$_vars[ProductLink] = "/cart/product_specs.php?id=$prod->id&category=$cat->id&parent=$_vars[parent]";
				$_vars[ProductTitle] = "More info on Product #$prod->prod_id - $prod->name";
				if($prod->long_desc != "") {
					$_vars[ShowMoreInfo] = "Yes";
				} else {
					$_vars[ShowMoreInfo] = "";
				}
				if($i % $SITE->NumberOfColumns == 0) { ?>
				<tr>
				<? } ?>
					<td valign="top" align="center" width="<? echo $_vars[columnWidth]; ?>%">
						<form action="<? echo $CFG->host ?>/cart/add_item.php" method="post" name="AddItem" onsubmit="return checkform(this);">
							<table border="0" cellspacing="0" cellpadding="1">
									<!-- <tr>
										<td align="center">
											<TABLE width="95%">
												<TR>
													<TD align="center" height=2 bgcolor="<? pv($SITE->HeaderBannerColor) ?>"></TD>
												</TR>
											</TABLE>
										</td>
									</tr> -->
								<? if($SITE->ShowImage == "Yes") { ?>
									<tr>
										<td align="center" class="Copy"><? //Image_Thumbnail
										if($_vars[ShowMoreInfo] == "Yes") { 
											echo "<a class=\"CopyLink\" href=\"$_vars[ProductLink]\" title=\"$_vars[ProductTitle]\">";
										}
										$specialThumbnail = "$CFG->baseroot/images/products/".$prod->prod_id;//<!-- ."_a" -->
										SpecialsImages($specialThumbnail,'125','windowImageBorder','1',$SITE->HeaderBannerColor,'','','0'); 

										//ImageOrText($_vars[Image1Thumbnail], "");
										if($_vars[ShowMoreInfo] == "Yes") { 
											echo "</a>";
										}
										?></td>
									</tr>
								<? } ?>
									<tr height=30>
										<th align="center" class="Accent" width="90%"><? //Image_Title
										if($_vars[ShowMoreInfo] == "Yes") {
											echo "<a class=\"CopyLink\" href=\"$_vars[ProductLink]\" title=\"$_vars[ProductTitle]\">";
										}
										if($SITE->ShowProductName == "Yes") {
											pv($prod->name);
										}
										if($SITE->ShowProductID == "Yes") {
										splicein($prod->prod_id,"&nbsp;- #");
										}
										if($_vars[ShowMoreInfo] == "Yes") {
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
														<th align="center" width="1%" bgcolor="<? pv($SITE->HeaderBannerColor) ?>"></TD>
														<Th align="center" width="50%" class="Copy">Case of <? pv($prod->per_case_qty) ?></TD>
													</TR>
												<? if($SITE->ShowPrice == "Yes") { ?>
													<TR>
														<TD align="center" class="Copy"><b>
														<? if($prod->sale_price){ ?>
															<a class='RedLine'>$<? pv($prod->price) ?>.00</a>
															<br>
															<b><a class='Red'>Sale Price<br>$<? pv($prod->sale_price) ?>.00</a></b>
														<? }else{ ?>
															$<? pv($prod->price) ?>.00
														<? } ?>
														</b></TD>
														<th align="center" bgcolor="<? pv($SITE->HeaderBannerColor) ?>"></TD>
														<TD align="center" class="Copy"><b>
														<? if($prod->case_sale_price){ ?>
															<a class='RedLine'>$<? pv($prod->case_price) ?>.00</a>
															<br>
															<b><a class='Red'>Sale Price<br>$<? pv($prod->case_sale_price) ?>.00</a></b>
														<? }else{ ?>
															$<? pv($prod->case_price) ?>.00
														<? } ?>
														</b></TD>
													</TR>
												<? } ?>
												<TR>
													<TD align="center" class="Copy"><? if($SITE->ShowQuantityBox == "Yes") { ?>Qty:<input type="text" name="qty" size="1"><? } else { ?><input type="hidden" name="qty"><? } ?></TD>
													<th align="center" width="1%" bgcolor="<? pv($SITE->HeaderBannerColor) ?>"></TD>
													<TD align="center" class="Copy"><? if($SITE->CasePrices == "Yes") { ?>Case Qty:<input type="text" name="case_qty" size="1"><? } else { ?><input type="hidden" name="case_qty"><? } ?></TD>
												</TR>
											<? }else{ ?>
													<!-- <TR>
														<Th align="center" class="Copy" colspan=2>Single</TD>
													</TR> -->
													<? if($SITE->ShowPrice == "Yes") { ?>
														<TR>
															<TD align="center" class="Copy" colspan=2><b>
															<? if($prod->sale_price){ ?>
																<a class='RedLine'>$<? pv($prod->price) ?>.00</a>
																<br>
																<b><a class='Red'>Sale Price<br>$<? pv($prod->sale_price) ?>.00</a></b>
															<? }else{ ?>
																$<? pv($prod->price) ?>.00
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
												<TR><!--  height=100 -->
													<TD valign="top" align="center" class="Copy" style='text-align:left;'><? echo nl2br($prod->description) ?></TD>
												</TR>
											</TABLE>
										</td>
									</tr>
								<? } ?>
								<? if($SITE->ShowMoreInfo == "Yes") { ?>
									<tr>
										<td align="center" class="Copy"><b><?
										if($prod->long_desc != "") {
											echo "<a href=\"$_vars[ProductLink]\" title=\"$_vars[ProductTitle]\">More Info</a>";
										}
										?></b></td>
									</tr>
								<? } ?>
								<? if($SITE->ShowBuyButton == "Yes") { ?>
									<tr>
										<td align="center" class="Copy"><? Product_Attribute_List($prod->id); ?></td>
									</tr>
									<tr>
										<td align="center" class="Copy">
										<input type="hidden" name="product_id" value="<? echo $prod->id; ?>">
										<? if($SITE->CustomButtons == "Yes"){ ?>
											<input name='submitButtonName' type='image' src="<? echo $CFG->host ?>/images/site/Purchase.jpg" alt='Purchase'>&nbsp;
										<? }else{ ?>
											<input name='submitButtonName' type="submit" value="Purchase">
										<? } ?>
										</td>
									</tr>

								<? } ?>
								<TR>
									<TD height="9">&nbsp;</TD>
								</TR>
								<TR>
									<TD height="9" background="../images/site/lp_small_cross_stich.gif"></TD>
								</TR>
							</table>
						</form>
					</td>
					<? if(($i % $SITE->NumberOfColumns) == ($SITE->NumberOfColumns - 1) || ($i + 1) == $_vars[rowcount]) { ?>
				</tr>
			<? } ?>
		<? } ?>
	</table>
<? } ?>
<br>
<? if (db_num_rows($qid_count) > $SITE->ProductsPerPage) { ?>
<table border="0" cellpadding="2" cellspacing="0" width="50%">
	<tr>
		<td align="center"><? if ($_vars[StartAt] > 0) { ?><a class="CopyLink" href="<? echo $PHP_SELF; ?>?StartAt=<? echo $_vars[StartAt] - $SITE->ProductsPerPage; ?>&category=<? echo $_vars[category]; ?>&parent=<? echo $_vars[parent]; ?>" title="View the Previous <? echo $SITE->ProductsPerPage; ?> Items">Previous</a><? } ?></td>
		<td align="center"><? if (($_vars[StartAt] + $SITE->ProductsPerPage) < db_num_rows($qid_count)) { ?><a class="CopyLink" href="<? echo $PHP_SELF; ?>?StartAt=<? echo $_vars[StartAt] + $SITE->ProductsPerPage; ?>&category=<? echo $_vars[category]; ?>&parent=<? echo $_vars[parent]; ?>" title="View the Next <? echo $SITE->ProductsPerPage; ?> Items">Next</a><? } ?></td>
	</tr>
</table>
<br>
<? } ?>