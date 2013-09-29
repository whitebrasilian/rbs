<?
include("../../starter.php");
require_login();
require_priv("admin");

if($_vars[done] == "Yes") {

	if($_vars[Company] == "") $errorList[] = "Company field left blank";
	if($_vars[Email] == "") $errorList[] = "Email field left blank";
	if($_vars[URL] == "") $errorList[] = "URL field left blank";
	if (sizeof($errorList) > 0) DisplayErrorPage($errorList);

	if($_SESSION[user][email] == 'xwebmaster@'.$SITE->URL){

		if(!isset($_POST[ShowImage])) $_POST[ShowImage] = "";
		if(!isset($_POST[ShowProductID])) $_POST[ShowProductID] = "";
		if(!isset($_POST[ShowProductName])) $_POST[ShowProductName] = "";
		if(!isset($_POST[ShowShortDesc])) $_POST[ShowShortDesc] = "";
		if(!isset($_POST[ShowMoreInfo])) $_POST[ShowMoreInfo] = "";
		if(!isset($_POST[ShowPrice])) $_POST[ShowPrice] = "";
		if(!isset($_POST[ShowQuantityBox])) $_POST[ShowQuantityBox] = "";
		if(!isset($_POST[ShowBuyButton])) $_POST[ShowBuyButton] = "";
		if(!isset($_POST[ShowCartOnAdd])) $_POST[ShowCartOnAdd] = "";

		if(!isset($_POST[PaymentModules])) $_POST[PaymentModules] = "";
		if(!isset($_POST[ShippingCharges])) $_POST[ShippingCharges] = "";
		if(!isset($_POST[ProductRequest])) $_POST[ProductRequest] = "";
		if(!isset($_POST[OrderMan])) $_POST[OrderMan] = "";
		if(!isset($_POST[ProductMan])) $_POST[ProductMan] = "";
		if(!isset($_POST[ChargeMan])) $_POST[ChargeMan] = "";
		if(!isset($_POST[CustomerMan])) $_POST[CustomerMan] = "";
		if(!isset($_POST[ReturnProcessing])) $_POST[ReturnProcessing] = "";
		if(!isset($_POST[Newsletter])) $_POST[Newsletter] = "";
		if(!isset($_POST[Testimonials])) $_POST[Testimonials] = "";
		if(!isset($_POST[EditableContent])) $_POST[EditableContent] = "";
		if(!isset($_POST[ImageUploading])) $_POST[ImageUploading] = "";
		if(!isset($_POST[CustomButtons])) $_POST[CustomButtons] = "";
		if(!isset($_POST[InventoryMan])) $_POST[InventoryMan] = "";
		if(!isset($_POST[BuyersClub])) $_POST[BuyersClub] = "";
		if(!isset($_POST[Press])) $_POST[Press] = "";
		if(!isset($_POST[Archive])) $_POST[Archive] = "";
		if(!isset($_POST[CasePrices])) $_POST[CasePrices] = "";
		if(!isset($_POST[HtmlEditor])) $_POST[HtmlEditor] = "";
		if(!isset($_POST[ProductAttributes])) $_POST[ProductAttributes] = "";
		if(!isset($_POST[Taxes])) $_POST[Taxes] = "";
		if(!isset($_POST[ShipTracking])) $_POST[ShipTracking] = "";
		if(!isset($_POST[CurrencyConverter])) $_POST[CurrencyConverter] = "";
		if(!isset($_POST[CacheControl])) $_POST[CacheControl] = "";
		if(!isset($_POST[CC_StatementInfo])) $_POST[CC_StatementInfo] = "";
		if(!isset($_POST[SSL_Info])) $_POST[SSL_Info] = "";
		if(!isset($_POST[News])) $_POST[News] = "";

	}

	db_ez_update("site_settings", 1, "id");
	header("Location:".$CFG->host."/manager/settings/index.php?Complete=Yes");
}

$PageText = GetPageText("manager/settings/index.php");
include($CFG->baseroot."/header.php");

 ?>
<TABLE border="0" cellpadding="5" width=<? pv($SITE->ManagerWidth); ?> cellspacing="1">
	<tr align='center'>

		<? if($_SESSION[user][email] == 'webmaster@'.$SITE->URL){ ?>

			<TD>
				<a href="index.php" class="managerCopyLink">WEBSITE SETTINGS</a>
			</TD>
			<TD>
				<a href="payments.php" class="managerCopyLink">PAYMENT MODULES</a>
			</TD>

		<? } ?>

		<? if($SITE->ShippingCharges == "Yes"){ ?>

			<TD>
				<a href="shipping.php" class="managerCopyLink">SHIPPING CHARGES</a>
			</TD>

		<? } ?>

		<? if($SITE->Taxes == "Yes"){ ?>

			<TD>
				<a href="taxes_states.php" class="managerCopyLink">TAXES</a>
			</TD>

		<? } ?>

	</TR>
</TABLE>

<div id="" class="" style=""><? TextOrHTML($PageText->PageText, $PageText->PageFormat); ?></div>
<div id="" class="" style="font-size:18px; font-weight:bold; text-align:center; margin:15px 0 0 0;">COMPANY DETAILS</div>

<? if($_vars[Complete] == "Yes"){ ?>

		<div class="Good" style="border:2px solid #38B549; padding:10px; margin:10px; text-align:center;">Update Complete</div>

<? } ?>

<FORM METHOD='POST' ACTION="<? echo $PHP_SELF; ?>">

	<div id="" class="managerCopy" style="float:left; width:250px; margin:10px 0 20px 0;">

		<div id="" class="managerCopy" style="float:left; margin:15px 20px 0 0; width:65px;">Company</div>
		<div id="" class="" style="float:left; margin:15px 0 0 0;"><input type="text" name="Company" style="width:160px"  value="<? pv($SITE->Company); ?>"></div>
		<br class="clear">

		<div id="" class="" style="float:left; margin:15px 20px 0 0; width:65px;">Address</div>
		<div id="" class="" style="float:left; margin:15px 0 0 0;"><input type="text" name="Address1" style="width:160px" value="<? pv($SITE->Address1); ?>"></div>
		<br class="clear">

		<div id="" class="" style="float:left; margin:15px 20px 0 0; width:65px;">Line 2</div>
		<div id="" class="" style="float:left; margin:15px 0 0 0;"><input type="text" name="Address2" style="width:160px" value="<? pv($SITE->Address2); ?>"></div>
		<br class="clear">

		<div id="" class="" style="float:left; margin:15px 20px 0 0; width:65px;">City</div>
		<div id="" class="" style="float:left; margin:15px 0 0 0;"><input type="text" name="City" style="width:160px" value="<? pv($SITE->City); ?>"></div>
		<br class="clear">

		<div id="" class="" style="float:left; margin:15px 20px 0 0; width:65px;">State</div>
		<div id="" class="" style="float:left; margin:15px 0 0 0;"><select name="State" style="width:160px"><? StatesFull($SITE->State); ?></select></div>
		<br class="clear">

		<div id="" class="" style="float:left; margin:15px 20px 0 0; width:65px;">Zipcode</div>
		<div id="" class="" style="float:left; margin:15px 0 0 0;"><input type="text" name="Zip" style="width:160px" value="<? pv($SITE->Zip); ?>"></div>
		<br class="clear">

		<div id="" class="" style="float:left; margin:15px 20px 0 0; width:65px;">Country</div>
		<div id="" class="" style="float:left; margin:15px 0 0 0;"><select name="Country" style="width:160px"><? CountriesDD($SITE->Country); ?></select></div>
		<br class="clear">

	</div>
	<div id="" class="managerCopy" style="float:right; width:250px; margin:10px 0 20px 0;">

		<div id="" class="" style="float:left; margin:15px 20px 0 0; width:65px;">Tele. I</div>
		<div id="" class="" style="float:left; margin:15px 0 0 0;"><input type="text" name="Telephone1" style="width:160px" value="<? pv($SITE->Telephone1); ?>"></div>
		<br class="clear">

		<div id="" class="" style="float:left; margin:15px 20px 0 0; width:65px;">Tele. II</div>
		<div id="" class="" style="float:left; margin:15px 0 0 0;"><input type="text" name="Telephone2" style="width:160px" value="<? pv($SITE->Telephone2); ?>"></div>
		<br class="clear">

		<div id="" class="" style="float:left; margin:15px 20px 0 0; width:65px;">FAX</div>
		<div id="" class="" style="float:left; margin:15px 0 0 0;"><input type="text" name="FAX" style="width:160px" value="<? pv($SITE->FAX); ?>"></div>
		<br class="clear">

		<div id="" class="" style="float:left; margin:15px 20px 0 0; width:65px;">Email</div>
		<div id="" class="" style="float:left; margin:15px 0 0 0;"><input type="text" name="Email" style="width:160px" value="<? pv($SITE->Email); ?>"></div>
		<br class="clear">

		<div id="" class="" style="float:left; margin:15px 20px 0 0; width:65px;">URL</div>
		<div id="" class="" style="float:left; margin:15px 0 0 0;"><input type="text" name="URL" style="width:160px" value="<? pv($SITE->URL); ?>"></div>
		<br class="clear">

		<div id="" class="" style="float:left; margin:15px 20px 0 0; width:65px;">©</div>
		<div id="" class="" style="float:left; margin:15px 0 0 0;"><input type="text" name="CopyrightYear" style="width:160px" value="<? pv($SITE->CopyrightYear); ?>"></div>
		<br class="clear">

		<div id="" class="" style="float:left; margin:15px 20px 0 0; width:65px;250-65250">Map Link</div>
		<div id="" class="" style="float:left; margin:15px 0 0 0;"><input type="checkbox" name="MapLink" value="Yes" <? if($SITE->MapLink == "Yes") echo "checked" ?>></div>
		<br class="clear">

		<div id="" class="" style="float:left; margin:15px 20px 0 0; width:65px;250-65250">Pickup Days</div>
		<div id="" class="" style="float:left; margin:15px 0 0 0;"><input type="text" name="pickup" style="width:160px" value="<? pv($SITE->pickup); ?>"></div>
		<br class="clear">

	</div>
	<br class="clear">

	<div id="" class="" style="text-align:center; margin:20px 0 20px 0;"><input type="submit" value="     S a v e   C h a n g e s   "></div>

	<? if($_SESSION[user][email] == 'xwebmaster@'.$SITE->URL){ ?>

		<TABLE border="0" cellpadding="5" width=<? pv($SITE->ManagerWidth); ?> cellspacing="1" class="BlueWhite">
			<tr align="center" class="BlueWhite">
				<td width="50%">LAYOUT FOR PRODUCT LISTS</td>
				<td width="50%">ORDER PRODUCTS BY</td>
			</tr>
			<tr>
				<td><input type="checkbox" name="ShowImage" value="Yes" <? if($SITE->ShowImage == "Yes") echo "checked" ?>> - Show Image</td>
				<td><input type="radio" value="prod_id" name="OrderProductsBy" id="Product ID" <? if ($SITE->OrderProductsBy=="prod_id") echo "checked"; ?>> - Product ID</td>
			</tr>
			<tr>
				<td><input type="checkbox" name="ShowProductID" value="Yes" <? if($SITE->ShowProductID == "Yes") echo "checked" ?>> - Show Product ID</td>
				<td><input type="radio" value="name" name="OrderProductsBy" <? if ($SITE->OrderProductsBy=="name") echo "checked"; ?>> - Product Name</td>
			</tr>
			<tr>
				<td><input type="checkbox" name="ShowProductName" value="Yes" <? if($SITE->ShowProductName == "Yes") echo "checked" ?>> - Show Product Name</td>
				<td><input type="radio" value="price ASC" name="OrderProductsBy" <? if ($SITE->OrderProductsBy=="price ASC") echo "checked"; ?>> - Price - $ &gt; $$$</td>
			</tr>
			<tr>
				<td><input type="checkbox" name="ShowShortDesc" value="Yes" <? if($SITE->ShowShortDesc == "Yes") echo "checked" ?>> - Show Short Description</td>
				<td><input type="radio" value="price DESC" name="OrderProductsBy" <? if ($SITE->OrderProductsBy=="price DESC") echo "checked"; ?>> - Price - $$$ &gt; $</td>
			</tr>
			<tr>
				<td><input type="checkbox" name="ShowMoreInfo" value="Yes" <? if($SITE->ShowMoreInfo == "Yes") echo "checked" ?>> - Show More Info Link</td>
				<td><input type="checkbox" name="ShowCartOnAdd" value="Yes" <? if($SITE->ShowCartOnAdd == "Yes") echo "checked" ?>>&nbsp;-&nbsp;Display in Cart After Adding Product </td>
			</tr>
			<tr>
				<td><input type="checkbox" name="ShowPrice" value="Yes" <? if($SITE->ShowPrice == "Yes") echo "checked" ?>> - Show Price</td>
				<td><select name="ProductsPerPage" style="width:75px">

					<? for($i=1;$i<101;$i++) {
						if($i == $SITE->ProductsPerPage) $Selected = "SELECTED";
						echo "<option value=\"$i\" $Selected>$i</option>";
						$Selected = "";
					} ?>

					</select>&nbsp;-&nbsp;Number of Products Per Page</td>
			</tr>
			<tr>
				<td><input type="checkbox" name="ShowQuantityBox" value="Yes" <? if($SITE->ShowQuantityBox == "Yes") echo "checked" ?>> - Show Quantity Box</td>
				<td><select name="NumberOfColumns" style="width:75px">

					<? for($i=1;$i<5;$i++) {

						if($i == $SITE->NumberOfColumns) $Selected = "SELECTED";
						echo "<option value=\"$i\" $Selected>$i</option>";
						$Selected = "";

					} ?>

					</select>&nbsp;-&nbsp;Number of Columns </td>
			</tr>
			<tr>
				<td><input type="checkbox" name="ShowBuyButton" value="Yes" <? if($SITE->ShowBuyButton == "Yes") echo "checked" ?>> - Show Buy Button</td>
				<td><select name="OrdersPerPage" style="width:75px">

					<? for($i=1;$i<101;$i++) {
						if($i == $SITE->OrdersPerPage) $Selected = "SELECTED";
						echo "<option value=\"$i\" $Selected>$i</option>";
						$Selected = "";
					} ?>

					</select>&nbsp;-&nbsp;Number of Orders Per Page</td>
			</tr>
		</table>

		<TABLE border="0" cellpadding="5" width=<? pv($SITE->ManagerWidth); ?> cellspacing="1" class="BlueWhite">
			<tr class="BlueWhite">
				<td align="center" colspan=2><B>MODULES</B></td>
			</tr>
			<tr>
				<td width="50%"><input type="checkbox" name="ShippingCharges" value="Yes" <? if($SITE->ShippingCharges == "Yes") echo "checked" ?>> - <a href="shipping.php" class="BlueWhite">Shipping Charges</a></td>
				<td width="50%"><input type="checkbox" name="ProductRequest" value="Yes" <? if($SITE->ProductRequest == "Yes") echo "checked" ?>> - Product Request</td>
			</tr>
			<tr>
				<td><input type="checkbox" name="OrderMan" value="Yes" <? if($SITE->OrderMan == "Yes") echo "checked" ?>> - Order Manager</td>
				<td><input type="checkbox" name="ProductMan" value="Yes" <? if($SITE->ProductMan == "Yes") echo "checked" ?>> - Product Manager</td>
			</tr>
			<tr>
				<td><input type="checkbox" name="ChargeMan" value="Yes" <? if($SITE->ChargeMan == "Yes") echo "checked" ?>> - Charge Manager</td>
				<td><input type="checkbox" name="CustomerMan" value="Yes" <? if($SITE->CustomerMan == "Yes") echo "checked" ?>> - Customer Manager</td>
			</tr>
			<tr>
				<td><input type="checkbox" name="ReturnProcessing" value="Yes" <? if($SITE->ReturnProcessing == "Yes") echo "checked" ?>> - Return Processing</td>
				<td><input type="checkbox" name="Newsletter" value="Yes" <? if($SITE->Newsletter == "Yes") echo "checked" ?>> - Newsletter</td>
			</tr>
			<tr>
				<td><input type="checkbox" name="Testimonials" value="Yes" <? if($SITE->Testimonials == "Yes") echo "checked" ?>> - Testimonials</td>
				<td><input type="checkbox" name="EditableContent" value="Yes" <? if($SITE->EditableContent == "Yes") echo "checked" ?>> - Editable Content</td>
			</tr>
			<tr>
				<td><input type="checkbox" name="ImageUploading" value="Yes" <? if($SITE->ImageUploading == "Yes") echo "checked" ?>> - Image Uploading</td>
				<td><input type="checkbox" name="CustomButtons" value="Yes" <? if($SITE->CustomButtons == "Yes") echo "checked" ?>> - Custom Buttons</td>
			</tr>
			<tr>
				<td><input type="checkbox" name="InventoryMan" value="Yes" <? if($SITE->InventoryMan == "Yes") echo "checked" ?>> - Inventory Manager</td>
				<td><input type="checkbox" name="Press" value="Yes" <? if($SITE->Press == "Yes") echo "checked" ?>> - Press Releases</td>
			</tr>
			<tr>
				<td><input type="checkbox" name="BuyersClub" value="Yes" <? if($SITE->BuyersClub == "Yes") echo "checked" ?>> - Buyers Club</td>
				<td><input type="checkbox" name="Archive" value="Yes" <? if($SITE->Archive == "Yes") echo "checked" ?>> - Archives</td>
			</tr>
			<tr>
				<td><input type="checkbox" name="CasePrices" value="Yes" <? if($SITE->CasePrices == "Yes") echo "checked" ?>> - Case Prices</td>
				<td><input type="checkbox" name="HtmlEditor" value="Yes" <? if($SITE->HtmlEditor == "Yes") echo "checked" ?>> - HTML Editor</td>
			</tr>
			<tr>
				<td><input type="checkbox" name="ProductAttributes" value="Yes" <? if($SITE->ProductAttributes == "Yes") echo "checked" ?>> - Product Attributes</td>
				<td><input type="checkbox" name="Taxes" value="Yes" <? if($SITE->Taxes == "Yes") echo "checked" ?>> - Taxes</td>
			</tr>
			<tr>
				<td><input type="checkbox" name="CurrencyConverter" value="Yes" <? if($SITE->CurrencyConverter == "Yes") echo "checked" ?>> - Currency Converter</td>
				<td><input type="checkbox" name="CacheControl" value="Yes" <? if($SITE->CacheControl == "Yes") echo "checked" ?>> - Cache Control</td>
			</tr>
			<tr>
				<td><input type="checkbox" name="ShipTracking" value="Yes" <? if($SITE->ShipTracking == "Yes") echo "checked" ?>> - ShipTracking</td>
				<td></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="SSL_Info" value="Yes" <? if($SITE->SSL_Info == "Yes") echo "checked" ?>> - SSL Info</td>
				<td></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="CC_StatementInfo" value="Yes" <? if($SITE->CC_StatementInfo == "Yes") echo "checked" ?>> - CC Statement Info</td>
				<td></td>
			</tr>
			<tr>
				<td><input type="checkbox" name="News" value="Yes" <? if($SITE->News == "Yes") echo "checked" ?>> - News</td>
				<td></td>
			</tr>
			<tr>
				<td></td>
				<td></td>
			</tr>
		</table>

		<div id="" class="" style="text-align:center; margin:20px 0 20px 0;"><input type="submit" value="     S a v e   C h a n g e s   "></div>

	<? } ?>

<input type="hidden" name="done" value="Yes">
</FORM>

<? include("$CFG->baseroot/footer.php"); ?>