<?
/*********************************************************
 * eCommerce Website Solution
 * Copyright 2008
 *********************************************************/

/*********************************************************
 * Cart / Purchasing Functions
 *********************************************************/

include('classes/browser_detection.php');

function AddToCart($SessionID, $ProductID, $Price, $Qty, $category_id, $confirmed="", $attributes="")
/* Check if item is already in cart, if so update, else insert */
{
	$qid = db_query("SELECT Qty FROM cart_items WHERE SessionID = '".$SessionID."' AND ProductID = '".$ProductID."'",0,0);
	$Count = db_fetch_assoc($qid);
	$CurrentCount = $Count['Qty'];
	if($CurrentCount > 0) {
		//$Qty += $CurrentCount;
		UpdateItemQuantity($SessionID, $ProductID, $Qty, $attributes);
	} else {
		db_query("INSERT INTO cart_items(SessionID, ProductID, Price, Qty, category_id, confirmed, attributes) VALUES ('$SessionID','$ProductID','$Price','$Qty','$category_id','$confirmed','".$attributes."')",0,0);
	}
}


function UpdateItemQuantity($SessionID, $ProductID, $Qty)
// Update quantity of item in cart
{
	if($Qty > 0) {
		db_query("UPDATE cart_items SET Qty = '$Qty' WHERE SessionID = '$SessionID' AND ProductID = '$ProductID'",0,0);
	} else {
		DeleteItem($SessionID, $ProductID);
	}
}

function DeleteItem($SessionID, $ProductID)
{
	db_query("DELETE FROM cart_items WHERE SessionID = '$SessionID' AND ProductID = '$ProductID'");
}


function EmptyCart($SessionID)
{
	db_query("DELETE FROM cart_items WHERE SessionID = '$SessionID'");
}

function CleanUpCartTable()
// Delete all items from cart_items table that have expired sessions.
// Called each time the cart is viewed.
{
	global $CFG;
	$qid = db_query("UPDATE cart_items SET DeleteFlag = 1");
	$dir = opendir("$CFG->baseroot/standards/sessions/");
	while ($file = readdir($dir)) {
		if($file != "." && $file !="..") {
			$file = str_replace("sess_", "", $file);
			$qid = db_query("UPDATE cart_items SET DeleteFlag = 0 WHERE SessionID = '$file'");
		}
	}
	closedir($dir);
	$qid = db_query("DELETE FROM cart_items WHERE DeleteFlag = 1");
}


function GetOrderItems($order_id)
// Returns the items in the cart for display in cart, checkout, receipt Pages
{
	return db_query("
	SELECT
	p.id,
	p.sku,
	p.name,
	p.unit_type,
	p.unit_value,
	oi.product_id,
	oi.qty,
	oi.PriceEach,
	oi.sendBackType,
	oi.ShipDate,
	oi.trackingNumber,
	oi.refund_qty,
	oi.attached_to,
	oi.attributes,
	Sum(oi.PriceEach * oi.qty) AS Linetotal,
	orders.Shipping,
	orders.Tax,
	orders.SubTotal,
	orders.amount,
	orders.DiscountValue,
	orders.comments
	FROM
	products AS p
	Inner Join order_items AS oi ON p.id = oi.product_id
	Inner Join orders ON oi.order_id = orders.id
	WHERE
	oi.order_id =  '".$order_id."'
	GROUP BY oi.product_id
	ORDER BY oi.ShipDate DESC
	",0,0);
}

function GetCartItems($SessionID)
// Returns the items in the cart for display in cart, checkout, receipt Pages
{
	return db_query("
	SELECT
	products.id,
	products.sku,
	products.name,
	products.PriceEach,
	products.page_text,
	products.CasePriceEach,
	products.single_inventory,
	products.unit_type,
	products.unit_value,
	products.points,
	cart_items.ProductID,
	cart_items.Qty,
	cart_items.Price,
	cart_items.category_id,
	cart_items.confirmed,
	cart_items.attributes,
	products_categories.category_id,
	categories.name as cname,
	cart_items.Price * cart_items.Qty as Linetotal
	FROM
	products
	Inner Join products_categories ON products.id = products_categories.product_id
	Inner Join categories ON categories.id = products_categories.category_id
	Inner Join cart_items ON products.id = cart_items.ProductID
	WHERE
	cart_items.SessionID =  '".$SessionID."'
	GROUP BY products.id
	",0,0);
}

function GetCartTotals($SessionID)
{
	global $SITE, $_SESSION;

	$qdb = db_query("
		SELECT
			SUM(cart_items.Price * cart_items.Qty) as Subtotal,
			SUM(products.s_price * cart_items.Qty) as S_Price,
			SUM(products.s_weight * cart_items.Qty) as S_Weight,
			SUM(products.s_length * cart_items.Qty) as S_Length,
			SUM(products.s_width * cart_items.Qty) as S_Width,
			SUM(products.s_height * cart_items.Qty) as S_Height,
			SUM(cart_items.Qty) as Quantity
		FROM products, cart_items
		WHERE
			cart_items.SessionID = '".$SessionID."' AND
			products.id = cart_items.ProductID
		GROUP BY SessionID");
	$CartTotal = db_fetch_assoc($qdb);

	if($CartTotal["Quantity"] == "") {  $CartTotal["Quantity"] = "0";}

	$CartTotal["Tax"] = DoTax($CartTotal);
	if (!is_empty($CartTotal["Tax"])) { $_SESSION["orderinfo"]["Tax"] = $CartTotal["Tax"]; }

	if (!is_empty($_SESSION["orderinfo"]["promo_code"])) { $Discount = DoDiscount($CartTotal); }

	$ShippingData = DoShipping($CartTotal);
	if ($Discount["DiscountType"]=="Free Shipping") { $CartTotal["Shipping"] = 0; }
	elseif ($_SESSION["Shipping"] <> "") { $CartTotal["Shipping"] = $_SESSION["Shipping"]; }
	else{ $CartTotal["Shipping"] = $ShippingData["Shipping"]; }
	if (!is_empty($CartTotal["Shipping"])) { $_SESSION["orderinfo"]["Shipping"] = $CartTotal["TaxShipping"]; }

	$CartTotal["ShippingExtra"]		= $ShippingData["ShippingExtra"];
	$CartTotal["ShippingExtraText"] = $ShippingData["ShippingExtraText"];
	$CartTotal["UPSErrorMsg"]		= $ShippingData["UPSErrorMsg"];
	$CartTotal["DiscountValue"]		= $Discount["DiscountValue"];
	$CartTotal["DiscountPercent"]	= $Discount["DiscountPercent"];
	$CartTotal["DiscountRate"]		= $Discount["DiscountRate"];
	$CartTotal["DiscountFreeShip"]	= $Discount["DiscountFreeShip"];
	$CartTotal["DiscountType"]		= $Discount["DiscountType"];

	$CartTotal["GrandTotal"] = ($CartTotal["Subtotal"] + $CartTotal["Tax"] + $CartTotal["Shipping"]) - $CartTotal["DiscountValue"];

	//pre($CartTotal);

	return $CartTotal;
	// (($CartTotal["ShippingExtra"]>0)?0:$CartTotal["Shipping"])
}

function DoTax($CartTotal)
{
	global $_SESSION, $SITE;

	if(($_SESSION["ShipToCountry"] != "") AND ($_SESSION["ShipToState"] != "")) {

		if(($_SESSION["ShipToCountry"] == "US") AND ($_SESSION["ShipToState"] == $SITE->State)) {

			$qid = db_query("SELECT * FROM taxes_states WHERE State = '".$_SESSION['ShipToState']."'");

		} else {

			$qid = db_query("SELECT * FROM taxes_countries WHERE Code = '".$_SESSION['ShipToCountry']."'");

		}

		$dbr = db_fetch_object($qid);

		if($dbr->TaxFlat > 0) {

			$Tax = $dbr->TaxFlat;

		} elseif($dbr->TaxPercent > 0) {

			$TaxRate = $dbr->TaxPercent * .01;
			$Tax = round($TaxRate * $CartTotal["Subtotal"],2);

		}

		return $Tax;

	}
}

function DoDiscount($CartTotal)
{
	global $_SESSION, $SITE;

	$q = db_query("SELECT * FROM promo_codes WHERE code = '".$_SESSION["orderinfo"]["promo_code"]."'",0,0);
	$qr = db_fetch_array($q);

	if ($CartTotal["Subtotal"] > $qr['threshold']) {

		if ($qr['promo_types'] == "Percentage Off") {

			$Discount["DiscountType"]		= "Percentage Off";
			$Discount["DiscountPercent"]	= $qr['value'];
			$Discount["DiscountValue"]		= $Discount["DiscountPercent"];

			$Discount["DiscountValue"] = ($Discount["DiscountPercent"] / 100) * $CartTotal["Subtotal"];

			$_SESSION["orderinfo"]["DiscountType"] = "Percentage Off";
			$_SESSION["orderinfo"]["DiscountValue"] = $Discount["DiscountValue"];

		}elseif($qr['promo_types'] == "Fate Rate"){

			$Discount["DiscountType"]		= "Fate Rate";
			$Discount["DiscountRate"]		= $qr['value'];
			$Discount["DiscountValue"]		= $Discount["DiscountRate"];

			$_SESSION["orderinfo"]["DiscountType"] = "Fate Rate";
			$_SESSION["orderinfo"]["DiscountValue"] = $Discount["DiscountValue"];

		}elseif($qr['promo_types'] == "Free Shipping"){

			$Discount["DiscountType"]		= "Free Shipping";
			$Discount["DiscountFreeShip"]	= "Free Promo";
			$Discount["DiscountValue"]		= 0;

			$_SESSION["orderinfo"]["DiscountType"] = "Free Shipping";
			$_SESSION["orderinfo"]["DiscountValue"] = $Discount["DiscountValue"];

		}

	}

	return $Discount;
}


function DoShipping($CartTotal)
// Returns array of data to GetCartTotals() function.
// Shipping, ShippingExtra, and UPSError
{
	global $SITE, $_SESSION, $SITE, $CFG;
	$ShippingCost = 0;

	if ($SITE->ShippingOptionChoice  == 1) { // Flat Rate Per Order
		$ShippingCost = $SITE->Option1RPO;

	} elseif ($SITE->ShippingOptionChoice  == 2) { // Base Rate plus Cost Per Item
		$ShippingCost = $SITE->Option2RPO + ($SITE->Option2CPI * ($CartTotal["Quantity"] - 1));

	} elseif ($SITE->ShippingOptionChoice  == 3) { // Variable Price Per Item
		$ShippingCost =  $CartTotal["S_Price"];

	} elseif ($SITE->ShippingOptionChoice  == 4) { // By Total Cost of Items in Cart
		if ($CartTotal["Subtotal"] > 0 && $CartTotal["Subtotal"] <= $SITE->Option4Max1) { $ShippingCost = $SITE->Option4Total1; }
		if ($CartTotal["Subtotal"] > $SITE->Option4Max1 && $CartTotal["Subtotal"] <= $SITE->Option4Max2) { $ShippingCost = $SITE->Option4Total2; }
		if ($CartTotal["Subtotal"] > $SITE->Option4Max2 && $CartTotal["Subtotal"] <= $SITE->Option4Max3) { $ShippingCost = $SITE->Option4Total3; }
		if ($CartTotal["Subtotal"] > $SITE->Option4Max3 && $CartTotal["Subtotal"] <= $SITE->Option4Max4) { $ShippingCost = $SITE->Option4Total4; }
		if ($CartTotal["Subtotal"] > $SITE->Option4Max4 && $CartTotal["Subtotal"] <= $SITE->Option4Max5) { $ShippingCost = $SITE->Option4Total5; }

	} elseif ($SITE->ShippingOptionChoice  == 5) { // By Total Number of Items in Cart
		if ($CartTotal["Quantity"] > 0 && $CartTotal["Quantity"] <= $SITE->Option5Max1) { $ShippingCost = $SITE->Option5Total1; }
		if ($CartTotal["Quantity"] > $SITE->Option5Max1 && $CartTotal["Quantity"] <= $SITE->Option5Max2) { $ShippingCost = $SITE->Option5Total2; }
		if ($CartTotal["Quantity"] > $SITE->Option5Max2 && $CartTotal["Quantity"] <= $SITE->Option5Max3) { $ShippingCost = $SITE->Option5Total3; }
		if ($CartTotal["Quantity"] > $SITE->Option5Max3 && $CartTotal["Quantity"] <= $SITE->Option5Max4) { $ShippingCost = $SITE->Option5Total4; }
		if ($CartTotal["Quantity"] > $SITE->Option5Max4 && $CartTotal["Quantity"] <= $SITE->Option5Max5) { $ShippingCost = $SITE->Option5Total5; }

	} elseif ($SITE->ShippingOptionChoice  == 6) { // By Total Weight of Items in Cart
		if ($CartTotal["S_Weight"] > 0 && $CartTotal["S_Weight"] <= $SITE->Option6Max1) { $ShippingCost = $SITE->Option6Total1; }
		if ($CartTotal["S_Weight"] > $SITE->Option6Max1 && $CartTotal["S_Weight"] <= $SITE->Option6Max2) { $ShippingCost = $SITE->Option6Total2; }
		if ($CartTotal["S_Weight"] > $SITE->Option6Max2 && $CartTotal["S_Weight"] <= $SITE->Option6Max3) { $ShippingCost = $SITE->Option6Total3; }
		if ($CartTotal["S_Weight"] > $SITE->Option6Max3 && $CartTotal["S_Weight"] <= $SITE->Option6Max4) { $ShippingCost = $SITE->Option6Total4; }
		if ($CartTotal["S_Weight"] > $SITE->Option6Max4 && $CartTotal["S_Weight"] <= $SITE->Option6Max5) { $ShippingCost = $SITE->Option6Total5; }

	} elseif ($SITE->ShippingOptionChoice  == 7) { // By Fixed Price Per Pound
		$ShippingCost =  $CartTotal["S_Weight"] * $SITE->Option7RPP;

	} elseif ($SITE->ShippingOptionChoice == 8 && $CFG->UPSShippingUpgrade == "Yes" && $SITE->Option8TermsAgree == "Yes") { // UPS Shipping Calculator

		if($CartTotal["S_Weight"] == 0) {
			$UPSErrorMsg = "Total weight is 0 pounds. No UPS charge can be calculated.";
		} elseif($CartTotal["S_Weight"] > 150) {
			$UPSErrorMsg = "Total weight is over 150 pounds.  We will contact you with charges.";
		} else {
			$UPSErrorMsg = "";

			//$ShippingCost = GetUPSRate($_SESSION["UPSChoice"], $SITE->Option8ZIP, $_SESSION["ShipToZip"], $CartTotal["S_Weight"], $SITE->Country, $_SESSION["ShipToCountry"]);
		}

	} elseif ($SITE->ShippingOptionChoice == 9) { // By Shipping Location

		if($_SESSION["ShipToCountry"] == 'US'){
			$ShippingCost = 0;
		}else if($_SESSION["ShipToCountry"] == 'PR'){
			$ShippingCost = 0;
		}else if($_SESSION["ShipToCountry"] == 'CA'){
			$ShippingCost = 20;
		}else if($_SESSION["ShipToCountry"] == ''){
			$ShippingCost = 0;
		}else{
			$ShippingCost = 35;
		}

	}


	if(($SITE->FreeIfOver != "") && ($CartTotal["Subtotal"] > $SITE->FreeIfOver)) {
		$ShippingCost = 0.00;
	}

	$ShippingExtra = 0;
	$ShippingExtraText = "";

	if($_SESSION["DoRushShipping"]==1) {
		$ShippingExtra = $SITE->RSC1Price;
		$ShippingExtraText = $SITE->RSC1Text;
	} elseif($_SESSION["DoRushShipping"]==2) {
		$ShippingExtra = $SITE->RSC2Price;
		$ShippingExtraText = $SITE->RSC2Text;
	} elseif($_SESSION["DoRushShipping"]==3) {
		$ShippingExtra = $SITE->RSC3Price;
		$ShippingExtraText = $SITE->RSC3Text;
	}
	$ShippingData["Shipping"] = $ShippingCost;
	$ShippingData["ShippingExtra"] = $ShippingExtra;
	$ShippingData["ShippingExtraText"] = $ShippingExtraText;
	$ShippingData["UPSErrorMsg"] = $UPSErrorMsg;
	return $ShippingData;
}



function GetUPSRate($upsProduct, $OriginPostalCode, $DestZipCode, $PackageWeight, $OrigCountry, $DestCountry)
/* Returns the Price from UPS for the specified package info as $UPSPriceQuote */
{
	// Set default shippng to UPS Ground
	if(($upsProduct) == "") {
		$upsProduct = "GND";
	}
	$action = "3";
	$product = "$upsProduct";
	$origCountry = "$OrigCountry";
	$origPostal = "$OriginPostalCode";
	$destPostal = "$DestZipCode";
	$destCountry = "$DestCountry";
	$weight = round($PackageWeight);
	$rateChart = "Regular+Daily+Pickup";
	$container = "00";
	$residential = "1";

	$port = 80;
	$them = "www.ups.com";
	$request = "/using/services/rave/qcostcgi.cgi?accept_UPS_license_agreement=yes&10_action=$action&13_product=$product&14_origCountry=$origCountry&15_origPostal=$origPostal&19_destPostal=$destPostal&22_destCountry=$destCountry&23_weight=$weight&47_rateChart=$rateChart&48_container=$container&49_residential=$residential";

	/* turn next line on for production. */
	//$fp = fsockopen("$them", $port, &$errno, &$errstr, 30);
	if(!$fp) {
		echo "$errstr ($errno)<br>\n";
	} else {
		fputs($fp,"GET $request HTTP/1.0\n\n");
			while(!feof($fp)) {
				$result = fgets($fp,500);
				$result = explode("%", $result);
				$errcode = substr("$result[0]", -1);
					if ($errcode == "3") {
						//echo "<!-- $result[0] - $UPSPriceQuote -->";
						return $result[8];
					}
					if ($errcode == "4") {
						echo "$result[8]";
					}
					if ($errcode == "5") {
						echo "$result[8]";
						//echo "<!-- Missing Consignee Zip Code -->";
					}
					if ($errcode == "6") {
						echo "$result[8]";
					}
			}
		fclose($fp);
	}
	return $result[8];
}


function save_orderinfo(&$frm)
{
/* Saves the order information into the session variable $_SESSION["orderinfo"]. */
	global $_SESSION;
	$_SESSION["orderinfo"]["first_name"] = $frm["first_name"];
	$_SESSION["orderinfo"]["last_name"] = $frm["last_name"];
	$_SESSION["orderinfo"]["b_address"] = $frm["b_address"];
	$_SESSION["orderinfo"]["b_address2"] = $frm["b_address2"];
	$_SESSION["orderinfo"]["b_city"] = $frm["b_city"];
	$_SESSION["orderinfo"]["b_state"] = $frm["b_state"];
	$_SESSION["orderinfo"]["b_zip"] = $frm["b_zip"];
	$_SESSION["orderinfo"]["b_country"] = $frm["b_country"];

	$_SESSION["orderinfo"]["s_first_name"] = $frm["s_first_name"];
	$_SESSION["orderinfo"]["s_last_name"] = $frm["s_last_name"];
	$_SESSION["orderinfo"]["s_address"] = $frm["s_address"];
	$_SESSION["orderinfo"]["s_address2"] = $frm["s_address2"];
	$_SESSION["orderinfo"]["s_city"] = $frm["s_city"];
	$_SESSION["orderinfo"]["s_state"] = $frm["s_state"];
	$_SESSION["orderinfo"]["s_zip"] = $frm["s_zip"];
	$_SESSION["orderinfo"]["s_country"] = $frm["s_country"];

	$_SESSION["orderinfo"]["email"] = $frm["email"];
	$_SESSION["orderinfo"]["phone"] = $frm["phone"];
	$_SESSION["orderinfo"]["phone2"] = $frm["phone2"];
	$_SESSION["orderinfo"]["fax"] = $frm["fax"];
	$_SESSION["orderinfo"]["comments"] = $frm["comments"];
	$_SESSION["orderinfo"]["newsletter"] = $frm["newsletter"];
	$_SESSION["orderinfo"]["promo_code"] = $frm["promo_code"];

	$_SESSION["orderinfo"]["cc_type"] = $frm["cc_type"];
	$_SESSION["orderinfo"]["cc_number"] = $frm["cc_number"];
	$_SESSION["orderinfo"]["cc_security_code"] = $frm["cc_security_code"];
	$_SESSION["orderinfo"]["cc_month"] = $frm["cc_month"];
	$_SESSION["orderinfo"]["cc_year"] = $frm["cc_year"];

}


function load_orderinfo(){
	/* Counterpart to save_orderinfo.  Used to retrieve the order information in /cart/index.php */
	global $_SESSION;

	if (empty($_SESSION["orderinfo"])) {
		return false;
	} else {
		return $_SESSION["orderinfo"];
	}
}


function chop_ccnum($ccnum)
{
/* Returns the the first and last 4 digits of the credit card number */
	return substr($ccnum, 0, 4) . "..." . substr($ccnum, -4);
}


function SaveFinalOrder($SessionID)
{
	global $_SESSION, $CartTotal, $SITE;

	/*
	$order = $_SESSION["orderinfo"];

	$_qid = db_query("SELECT id FROM users WHERE email = '".$order['email']."'");
	if (db_num_rows($_qid)==0) {

		$password = get_rand_id(5);

		db_query("INSERT INTO users (first_name, last_name, email, phone, newsletter, b_address, b_address2, b_city, b_state, b_zip, b_country, password, activate_date) VALUES ('".$order['first_name']."', '".$order['last_name']."', '".$order['email']."', '".$order['phone']."', '".$order['newsletter']."', '".$order['b_address']."', '".$order['b_address2']."', '".$order['b_city']."', '".$order['b_state']."', '".$order['b_zip']."', '".$order['b_country']."', '".md5($password)."', NOW())");

		$message = "
		Hello ".$order['first_name'].",<p>

		As part of your order with us we have set you up with a ".$SITE->Company." website user account. Having a user account with us will allow for fast checkout on your next purchase. Next time you return to our site, login at the bottom of our page. You will need this email address (<b>".$order['email']."</b>), and the password (<b>".$password."</b>) to login.<p>

		Thank you and we look forward to your next visit.<p>

		Best Regards,<p>


		".$SITE->Company." Staff<br>
		<a href='mailto:
		".$SITE->Email."'>".$SITE->Email."</a><br>
		<a href='http://www.".$SITE->URL."'>http://www.".$SITE->URL."</a><br>
		";

		$headers  = "";

		$headers  = "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\n";
		$headers .= "From: $SITE->Email\n";
		$headers .= "Reply-To: $SITE->Email\n";
		$headers .= "X-Mailer: PHP"."\n";

		mail($order['email'], "Frock Boutique User Account", $message, $headers);

	}else{

		db_query("UPDATE users SET first_name = '".$order['first_name']."', last_name = '".$order['last_name']."', b_address = '".$order['b_address']."', b_address2 = '".$order['b_address2']."', b_city = '".$order['b_city']."', b_state = '".$order['b_state']."', b_zip = '".$order['b_zip']."', b_country = '".$order['b_country']."', phone = '".$order['phone']."', newsletter = '".$order['newsletter']."' WHERE email = '".$order['email']."'");

	}
	*/

	$qid = db_query("
	INSERT INTO orders (
		o_timestamp,
		c_first_name, c_last_name, c_email, c_telephone,
		b_address, b_address2, b_city, b_state, b_zip, b_country,
		s_address, s_address2, s_city, s_state, s_zip, s_country,
		ShippingChoice, ShippingWeight, UPSMethod, UPSErrorMsg,
		comments, newsletter,
		SubTotal, Tax, Shipping, ShippingExtra, amount, DiscountType, DiscountValue, promo_code,
		s_first_name, s_last_name, user_id
	) VALUES (
		NOW(),
		'".$_SESSION['orderinfo']['first_name']."', '".$_SESSION['orderinfo']['last_name']."', '".$_SESSION['orderinfo']['email']."', '".$_SESSION['orderinfo']['phone']."',
		'".$_SESSION['orderinfo']['b_address']."', '".$_SESSION['orderinfo']['b_address2']."', '".$_SESSION['orderinfo']['b_city']."', '".$_SESSION['orderinfo']['b_state']."', '".$_SESSION['orderinfo']['b_zip']."', '".$_SESSION['orderinfo']['b_country']."',
		'".$_SESSION['orderinfo']['s_address']."', '".$_SESSION['orderinfo']['s_address2']."', '".$_SESSION['orderinfo']['s_city']."', '".$_SESSION['orderinfo']['s_state']."', '".$_SESSION['orderinfo']['s_zip']."', '".$_SESSION['orderinfo']['s_country']."',
		'".$CartTotal['ShippingExtraText']."', '".$CartTotal['S_Weight']."', '".$_SESSION['UPSChoice']."', '".$CartTotal['UPSErrorMsg']."',
		'".$_SESSION['orderinfo']['comments']."', '".$_SESSION['orderinfo']['newsletter']."',
		'".$CartTotal['Subtotal']."', '".$CartTotal['Tax']."', '".$CartTotal['Shipping']."', '".$CartTotal['ShippingExtra']."', '".$CartTotal['GrandTotal']."', '".$CartTotal['DiscountType']."', '".$CartTotal['DiscountValue']."', '".$_SESSION['orderinfo']['promo_code']."',
		'".$_SESSION['orderinfo']['s_first_name']."', '".$_SESSION['orderinfo']['s_last_name']."', '".$_SESSION['user']['id']."'
	)");
	$order_id = db_insert_id();

	/* add the shopping cart items into the order_items table */
	$qid = GetCartItems($SessionID);
	while ($item = db_fetch_object($qid)) {

		if ($item->SalePriceEach > 0) { $item->Price = $item->SalePriceEach; }else{ $item->Price = $item->PriceEach; }

		db_query("INSERT INTO order_items (order_id, product_id, PriceEach, qty, attributes) VALUES ('".$order_id."','".$item->ProductID."','".$item->Price."','".$item->Qty."','".$item->attributes."')");

	}

	/*
	$_SESSION['cc']['cc_type']			= $order['cc_type'];
	$_SESSION['cc']['cc_number']		= $order['cc_number'];
	$_SESSION['cc']['cc_security_code'] = $order['cc_security_code'];
	$_SESSION['cc']['cc_month']			= $order['cc_month'];
	$_SESSION['cc']['cc_year']			= $order['cc_year'];
	*/

	return $order_id;
}


function ClearOutOrder($SessionID)
{
	global $_SESSION;

	/* Delete items from cart_items, and unset saved shopping session */
	db_query("DELETE FROM cart_items WHERE SessionID = '".$SessionID."'",0,0);

	unset($_SESSION["orderinfo"],$_SESSION["cc"]);
}


function GetOrderBalance($id)
{
	$qid = db_query("SELECT amount FROM orders WHERE id = '$id'");
	$dbr = db_fetch_object($qid);
	$Balance['OrderTotal'] = $dbr->amount;

	$qid = db_query("SELECT payment_gross FROM payment_manual WHERE invoice = '$id'");
	$dbr = db_fetch_object($qid);
	$Balance['PaymentManual'] = $dbr->payment_gross;

	//$qid = db_query("SELECT payment_gross FROM payment_paypal WHERE invoice = '$id'");
	//$dbr = db_fetch_object($qid);
	//$Balance['PaymentPaypal'] = $dbr->payment_gross;

	$Balance['PaymentTotal'] = $Balance['PaymentManual'] + $Balance['PaymentPaypal'];
	$Balance['BalanceDue'] = $Balance['OrderTotal'] - $Balance['PaymentTotal'];
	return $Balance;
}


/*********************************************************
 * User / Login Related Functions
 *********************************************************/

function is_accepted(){
/* check to see if user has been accepted to use website*/

	global $_SESSION;
	$qix = db_query("SELECT u.priv, u.email FROM users as u WHERE u.email = '".$_SESSION["user"]["email"]."'");
	$frx = db_fetch_object($qix);
	if($frx->priv == "priv"){
		unset($_SESSION["user"]);
		redirect("$CFG->host/members/login.php?priv=$frx->priv&email=$frx->email");
		}
}

function load_userinfo() {
/* returns an object containing user information */

	global $_SESSION;

	$qid = db_query("SELECT first_name, last_name, email, phone, phone2, fax, b_address, b_address2, b_city, b_state, b_zip, b_country, s_company, s_address, s_address2, s_city, s_state, s_zip, s_country, discount FROM users WHERE email = '".$_SESSION["user"]["email"]."'");
	return db_fetch_array($qid);
}


function is_logged_in()
{
/* A user is logged in if the $_SESSION["user"] is set by login.php and also if the remote IP address matches what we saved in the session ($_SESSION["ip"]) from login.php */
	global $_SESSION;
	return isset($_SESSION)
		&& isset($_SESSION["user"]);
}


function require_login()
{
/* Checks to see if user is logged in.  Will show the login screen before allowing the user to continue */
	global $CFG, $_SESSION, $_SERVER;
	//pre($_SERVER); die;
	if (! is_logged_in()) {
		//$_SESSION["wantsurl"] = $_SERVER["PHP_SELF"];
		header("Location:$CFG->host/members/login.php");
		die;
	}
}


function require_priv($priv)
{
/* Checks to see if the user has the privilege $priv.  if not, will display the Insufficient Privileges page and stop */
	global $CFG, $_SESSION;
	if ($_SESSION["user"]["priv"] != $priv) {
		header("Location:".$CFG->host."/members/login.php");
		die;
	}
}


function has_priv($priv)
{
/* returns true if the user has the privilege $priv */
	global $_SESSION;
	return $_SESSION["user"]["priv"] == $priv;
}



function password_valid($password) {
/* return true if the user's password is valid */
	global $_SESSION;
	$qid = db_query("SELECT 1 FROM users WHERE email = '{$_SESSION["user"]["email"]}' AND password = '". md5($password) ."'");
	return db_num_rows($qid);
}

function priv_level($num)
{
/* returns true if the user has the privilege $Priv or greater */
	global $_SESSION, $CFG;

	unset($privilege);
	$privilege = array();

	if ($_SESSION["user"]["priv"] == "admin") {

		$privilege[0] = 0;
		$privilege[1] = 1;
		$privilege[2] = 2;

	}elseif ($_SESSION["user"]["priv"] == "member") {

		$privilege[1] = 1;
		$privilege[2] = 2;

	}elseif ($_SESSION["user"]["priv"] == "free") {

		$privilege[3] = 2;

	}
	if (in_array($num,$privilege)) {

		return true;

	}else {

		unset($_SESSION["user"]);
		header("Location:".$CFG->host."/index.php");
		die;
	}

}

function generate_password($maxlen=10)
{
/* returns a randomly generated password of length $maxlen. */

	global $CFG;

	$fillers = "1234567890!@#$%&*-_=+^";
	$wordlist = file("$CFG->baseroot/standards/wordlist.txt");

	srand((double) microtime() * 1000000);
	$word1 = trim($wordlist[rand(0, count($wordlist) - 1)]);
	$word2 = trim($wordlist[rand(0, count($wordlist) - 1)]);
	$filler1 = $fillers[rand(0, strlen($fillers) - 1)];

	return substr($word1 . $filler1 . $word2, 0, $maxlen);

}

function username_exists($username)
{
/* returns true if the username exists */
	$qid = db_query("SELECT 1 FROM users WHERE username = '$username'");
	return db_num_rows($qid);
}


function email_exists($email)
{
/* returns true if the email address exists */
	$qid = db_query("SELECT 1 FROM users WHERE email = '".$email."'");
	return db_num_rows($qid);
}


function isEmailValid($email)
{
	$pattern = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$^";
	if (preg_match($pattern, $email)) {
		return true;
	} else {
		return false;
	}
}


function reset_user_password($email)
{
/* resets the password for the user with the email $email, and sends it
 * to him/her via email */

	global $CFG, $SITE;

	/* load up the user record */
	$qid = db_query("SELECT first_name, last_name, username, email FROM users WHERE email = '".$email."'");
	$user = db_fetch_array($qid);

	$mailto = array();
	$mailto[0][0] = $user['email'];
	$mailto[0][1] = $user['first_name'];
	$mailto[0][2] = $user['last_name'];

	/* reset the password */
	$newpassword = get_rand_id(10);
	$qid = db_query("UPDATE users SET password = '" . md5($newpassword) ."' WHERE email = '".$email."'",0,0);

	/* email the user with the new account information */
	$var = new Object;
	$var->name = $user[first_name]." ".$user[last_name];
	$var->newpassword = $newpassword;
	$var->support = $SITE->Email;
	$var->Company = $SITE->Company;
	$var->email = $email;
	$var->username = $user[username];
	$var->URL = $SITE->URL;

	$emailbody = read_template($CFG->baseroot."/standards/email/reset_password.php", $var);

	if (smtp_mail($mailto, $var->Company." Password Recovery", $emailbody)) { Return 1; }

}


/*********************************************************
 * Standard Website Functions
 *********************************************************/


function DisplayCategories($category=0, $parent=0) {
 	global $SITE, $CFG;
	$qid = db_query("SELECT id, name, page_title FROM categories WHERE parent_id = '".$parent."' AND archive = '0' AND (id = 1 OR id = 2) ORDER BY display_order, name");
	while ($row = db_fetch_object($qid)) { ?>

		<div id="navi" class="BlueWhite"><img src="<? echo $CFG->host ?>/images/site/blue_arrow.png" alt="" class="l_arrow"><div style=" float:left;"><b><?php pv(strtoupper($row->name)); ?></b></div><br class="clear"></div>

		<?php
		if(($category == $row->id) OR ($parent == $row->id) OR ($parent == 0)) {
			DisplaySubCategories($row->id, $parent, "&nbsp;&nbsp;&nbsp;&nbsp;");
		}
	}
}

function DisplaySubCategories($category, $parent, $indent="",$sub=0) {
 	global $SITE, $CFG;
	if($parent == "") { $parent = 0; }
	$qid = db_query("SELECT id, parent_id, name, page_title FROM categories WHERE parent_id = '".$category."' AND id > 0 AND archive = '0' ORDER BY display_order, name");
	while ($row =  db_fetch_object($qid)) {

		if ($sub == 1) { $css = "13px;"; }else{ $css = "15px;"; } ?>

		<SCRIPT language="JavaScript">
		<!--
		if (document.images){
			a<?php pv($row->id); ?>_on= new Image(12,12);
			a<?php pv($row->id); ?>_on.src="<? echo $CFG->host ?>/images/site/s_white_arrow.png";
			a<?php pv($row->id); ?>_off= new Image(12,12);
			a<?php pv($row->id); ?>_off.src="<? echo $CFG->host ?>/images/site/dot_clear.gif";
		}
		//-->
		</SCRIPT>

		<div id="sub_navi" onclick="top.location.replace('<?php pv($CFG->host); ?>/cart/index.php?category=<?php pv($row->id); ?>&parent=<?php pv($parent); ?>');" onmouseover="this.id='sub_navi_trans';lightup('a<?php pv($row->id); ?>_');" onmouseout="this.id='sub_navi';turnoff('a<?php pv($row->id); ?>_');" class="<?php pv($css); ?>"><div style=" float:left;"><?php echo $indent; ?><a title="<?php pv($row->page_title); ?>" href="<?php pv($CFG->host); ?>/cart/index.php?category=<?php pv($row->id); ?>&parent=<?php pv($parent); ?>" class="WhiteBlueLink" style="font-size:<?php pv($css); ?>"><?php echo $b.$row->name.$c; ?></a></div><img name="a<?php pv($row->id); ?>_" src="<? echo $CFG->host ?>/images/site/dot_clear.gif" alt="" class="s_arrow"><br class="clear"></div>

		<?php if ($row->id != $category) {
			DisplaySubCategories($row->id, $row->parent_id, $indent."&nbsp;&nbsp;&nbsp;&nbsp;",$sub+1);
		}
	}
}

function build_category_tree(&$output, &$preselected, $parent=0, $indent="") {
/* recursively go through the category tree, starting at a parent, and
 * drill down, printing options for a selection list box.  preselected
 * items are marked as being selected.  */

	$qid = db_query("SELECT id, name FROM categories WHERE parent_id = $parent AND status <> 'archive' ORDER BY name ASC");
	$count = db_num_rows($qid);
	while ($cat =  db_fetch_object($qid)) {
		$selected = in_array($cat->id, $preselected) ? "selected" : "";
		$output .= "<option value=\"" . ov($cat->id) . "\" $selected>$indent" . ov($cat->name);
		if ($cat->id != $parent) {
			build_category_tree($output, $preselected, $cat->id, $indent."&nbsp;&nbsp;");
		}
	}
}

function build_related_products_tree(&$output, &$preselected, $parent=0, $indent="") {
/* recursively go through the category tree, starting at a parent, and
 * drill down, printing options for a selection list box.  preselected
 * items are marked as being selected.  */

	$qid = db_query("SELECT id, name FROM products WHERE status <> 'archive' ORDER BY name ASC");
	$count = db_num_rows($qid);
	while ($prod =  db_fetch_object($qid)) {
		$selected = in_array($prod->id, $preselected) ? "selected" : "";
		$output .= "<option value=\"" . ov($prod->id) . "\" $selected>$indent" . ov($prod->name);
	}
}


function build_jump_to_category_tree(&$output, $preselected='', $parent=0, $indent="", $page="category_update") {
/* recursively go through the category tree, starting at a parent, and
 * drill down, printing options for a selection list box.  preselected
 * items are marked as being selected.  */

	$qid = db_query("SELECT id, name FROM categories WHERE parent_id = $parent  AND status <> 'archive' ORDER BY name ASC");
	while ($cat =  db_fetch_object($qid)) {
		$output .= "<option value=\"$page.php?category_id=$cat->id\">$indent" . ov($cat->name);
		if ($cat->id != $parent) {
			build_jump_to_category_tree($output, $preselected, $cat->id, $indent."&nbsp;&nbsp;", $page);
		}
	}
}

function get_category_tree($id=0)
{
/* returns a tree of the product categories, starting from the top to the category specified by $id */
	global $CFG, $parent;
	$qid = db_query("SELECT parent_id, name FROM categories WHERE id = $id AND archive=0 ORDER BY id");
	if (db_num_rows($qid)) {
		list($parentc, $name) = db_fetch_row($qid);
		$name = "$name";//<a href='/store/cart/index.php?category=$id&parent=$parent'></a>
	} else {
		$parentc = 0;
		$name = "";
	}
	if ($parentc > 0) {
		return print_category_tree($parentc) . "" . $name;
	} elseif ($id > 0) {
		return "" . $name;
	}
}


function print_category_tree($id=false)
{
/* prints the category tree by calling get_category_tree */
	echo get_category_tree($id);
}


function splicein($Tidbit, $Prefix="", $Suffix="")
/* Use this function to echo out a variable with an optional prefix and suffix
   ($, #, -, etc.).  If variable is empty, prints nothing.  Intended for adding
   just a few additional characters to output code. */
{
if(($Tidbit != "") && ($Tidbit > 0))
	{
	echo $Prefix.$Tidbit.$Suffix;
	}
}


function err(&$errorvar)
{
/* if $errorvar is set, then print an error mark (<<) */
	if (isset($errorvar)) {
		echo "<font color=#a56161>!</font>";
	}
}


function err2(&$errorvar)
{
/* like err(), but prints the mark (>>) */
	if (isset($errorvar)) {
		echo "<font color=#a56161>!!</font>";
	}

}


function dayOfWeek($val)
{
/* format MySQL DATETIME value into a more readable string (May 21, 1999) */
	$arr = explode("-", $val);
	return date("l", mktime(0,0,0, $arr[1], $arr[2], $arr[0]));
}

function month_year($val)
{
/* format MySQL DATETIME value into a more readable string (May 21, 1999) */
	$arr = explode("-", $val);
	return date("F Y", mktime(0,0,0, $arr[1], 0, $arr[0]));
}

function formatDate($val)
{
/* format MySQL DATETIME value into a more readable string (May 21, 1999) */
	$arr = explode("-", $val);
	return date("M d, Y", mktime(0,0,0, $arr[1], $arr[2], $arr[0]));
}

function formatDateNum($val)
{
/* format MySQL DATETIME value into a more readable string (04.15.10) */
	$arr = explode("-", $val);
	return date("m.d.y", mktime(0,0,0, $arr[1], $arr[2], $arr[0]));
}

function formatDateNumDash($val)
{
/* format MySQL DATETIME value into a more readable string (04.15.10) */
	$arr = explode("-", $val);
	return date("m-d-y", mktime(0,0,0, $arr[1], $arr[2], $arr[0]));
}

function formatDateNum2($val)
{
/* format MySQL DATETIME value into a more readable string (04.30.10 @ 12:46 pm) */
	$arr = explode("-", $val);
	$arr2 = explode(" ",$arr[2]);
	$arr3 = explode(":",$arr2[1]);
	return date("m.d.y @ G:i", mktime($arr3[0], $arr3[1], $arr3[2], $arr[1], $arr[2], $arr[0]));
}

function formatDateLong($val)
{
/* format MySQL DATETIME value into a more readable string (May 21, 1999 @ 5:31 pm) */
	$arr = explode("-", $val);
	$arr2 = explode(" ",$arr[2]);
	$arr3 = explode(":",$arr2[1]);
	return date("F j, Y @ g:i a", mktime($arr3[0], $arr3[1], $arr3[2], $arr[1], $arr[2], $arr[0]));
}

function formatDateLong2($val)
{
/* format MySQL DATETIME value into a more readable string (May 21, 1999 @ 5:31 pm) */
	$arr = explode("-", $val);
	$arr2 = explode(" ",$arr[2]);
	$arr3 = explode(":",$arr2[1]);
	return date("M j, Y g:i a", mktime($arr3[0], $arr3[1], $arr3[2], $arr[1], $arr[2], $arr[0]));
}

function setdefault(&$var, $default="")
{
/* if $var is undefined, set it to $default.  otherwise leave it alone */
	if (! isset($var)) {
		$var = $default;
	}
}


function nvl(&$var, $default="")
{
/* if $var is undefined, return $default, otherwise return $var */
	return isset($var) ? $var : $default;
}


function ov(&$var)
{
/* returns $var with the HTML characters (like "<", ">", etc.) properly quoted, or if $var is undefined, will return an empty string.  Must be called with a variable, for normal strings or functions use o() */
	return isset($var) ? htmlSpecialChars(stripslashes($var)) : "";
}


function o($var)
{
/* returns $var with HTML characters (like "<", ">", etc.) properly quoted,
 * or if $var is empty, will return an empty string. */
	return empty($var) ? "" : htmlSpecialChars(stripslashes($var));
}



function pv(&$var)
{
/* prints $var with the HTML characters (like "<", ">", etc.) properly quoted,
 * or if $var is undefined, will print an empty string.  note this function
 * must be called with a variable, for normal strings or functions use p() */
	echo isset($var) ? htmlSpecialChars(stripslashes($var)) : "";
}

function p($var)
{
/* prints $var with HTML characters (like "<", ">", etc.) properly quoted,
 * or if $var is empty, will print an empty string. */
	echo empty($var) ? "" : htmlSpecialChars(stripslashes($var));
}


function redirect($url, $message="", $delay=0)
{
/* redirects to a new URL using meta tags */
	echo "<meta http-equiv='Refresh' content='$delay;' url='$url'>";
	if (!empty($message)) echo "<div style='font-family: Arial, Sans-serif; font-size: 12pt;' align=center>$message</div>";

}


function read_template($filename, &$var)
{
/* return a (big) string containing the contents of a template file with all
 * the variables interpolated.  all the variables must be in the $var[] array or
 * object (whatever you decide to use). WARNING: do not use on big files!! */
	$temp = str_replace("\\", "\\\\", implode(file($filename), ""));
	$temp = str_replace('"', '\"', $temp);
	eval("\$template = \"$temp\";");
	return $template;
}


function checked(&$var, $set_value = 1, $unset_value = 0)
{
/* if variable is set, set it to the set_value otherwise set it to the
 * unset_value.  used to handle checkboxes when you are expecting them from
 * a form */
	if (empty($var)) {
		$var = $unset_value;
	} else {
		$var = $set_value;
	}
}



function frmchecked(&$var, $true_value = "checked", $false_value = "")
{
/* prints the word "checked" if a variable is true, otherwise prints nothing,
 * used for printing the word "checked" in a checkbox form input */

	if ($var) {
		echo $true_value;
	} else {
		echo $false_value;
	}
}


function DeleteFileifExists($File)
{
	if (is_file("$File")) {
		unlink($File);
		if (is_file("$File")) {
			echo "The file, <b>".basename($File)."</b>, still exists.<BR>";
		}
	}
}


function ImageOrText($Image, $Text="", $Align="")
/* Display Image or echo category name */
{
	global $CFG;
	if(file_exists ("$CFG->baseroot/images".$Image.".jpg")) {
		$size = getimagesize("$CFG->baseroot/images".$Image.".jpg");
		echo "<img src=\"/images$Image.jpg\" border=\"1\" bordecolor=\"AFBAC6\" align=\"$Align\" alt=\"$Text\" $size[3]>";

	} elseif(file_exists ("$CFG->baseroot/images".$Image.".png")) {
		$size = getimagesize("$CFG->baseroot/images".$Image.".png");
		echo "<img src=\"/images$Image.png\" border=\"1\" bordecolor=\"AFBAC6\" align=\"$Align\" alt=\"$Text\" $size[3]>";

	} elseif(file_exists ("$CFG->baseroot/images".$Image.".gif")) {
		$size = getimagesize("$CFG->baseroot/images".$Image.".gif");
		echo "<img src=\"/images$Image.gif\" border=\"1\" bordecolor=\"AFBAC6\" align=\"$Align\" alt=\"$Text\" $size[3]>";
	} else {
		echo $Text;
	}
}

function CheckForFile($path)
/* Check for a file named $file. This is not complete, and may not work. 10.03.04
	// basename() strips path from '/product/index.php' to 'index.php', or just 'index'.
	*/
{
	header("Pragma: public");
	header("Expires: 0");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Content-Type: application/force-download");
	// Send binary filetype HTTP header
	header("Content-Type: application/octet-stream");
	header("Content-Type: application/download");
	// Send content-disposition with save file name HTTP header
	header("Content-Disposition: attachment; filename=".basename($path).";");
	//header("Content-Disposition: attachment; filename=\"Song_Request.mp3\"");
	header("Content-Transfer-Encoding: binary");
	header("Content-Length: ".filesize($path));
	// Output file
	readfile("$path");
}

/*********************************************************
 * Form Related Functions - Dropdowns
 *********************************************************/

function PricesDD($Default='')
/* Display A List of all prices in a list box, with the current choice selected. */
{
	$x=0;
	$temp_array = array();
	$dbp = db_query("SELECT price FROM prices ORDER BY price ASC");
	echo "<option value=\"\">&nbsp;</option>";
	while ($_dbp = db_fetch_array($dbp)) {

		if (!in_array($_dbp['price'],$temp_array)) {

			$temp_array[$x] = $_dbp['price'];
			$Selected = $_dbp['price'] == $Default ? " selected" : ""; ?>
			<option value="<? pv($_dbp['price']) ?>" <? pv($Selected) ?>><? pv($_dbp['price']) ?></option>
		<? }
	$x++;
	}
}

function CategoriesDD()
/* Display list of all states in a list box, with full state names, and with the current choice selected */
{
	$catsq = db_query("SELECT id, name FROM categories WHERE archive != 1 AND Display = 1 ORDER BY name");
	echo "<option value=\"\">&nbsp;</option>";
	while ($catz = db_fetch_object($catsq)) {
		echo "<option value=\"$catz->id\">$catz->name</option>\n";
	}
}

function StatesDD($Default='')
/* Display list of all states in a list box, with the current choice selected */
{
	if($Default == "")
	{
	$Default = "";
	}

	$statesq = db_query("SELECT Code, State FROM taxes_states ORDER BY Code");
	echo "<option value=\"\">&nbsp;</option>";
	//echo "<option value=\"\">Non-US</option>";
	while ($states = db_fetch_object($statesq)) {
		$Selected = $states->Code == $Default ? " selected" : "";
		echo "<option value=\"$states->Code\"$Selected>$states->Code</option>\n";
	}
}

function StatesFull($Default='')
/* Display list of all states in a list box, with full state names, and with the current choice selected */
{
	if($Default == "")
	{
	$Default = "";
	}

	$statesq = db_query("SELECT Code, State FROM taxes_states ORDER BY State");
	echo "<option value=\"\">&nbsp;</option>";
	echo "<option value=\"Non-US\">Non-US</option>";
	while ($states = db_fetch_object($statesq)) {
		$Selected = $states->State == $Default ? " selected" : "";
		echo "<option value=\"$states->State\"$Selected>$states->State</option>\n";
	}
}

function CountriesDD($Default='')
/* Display A List of all countries in a list box, with the current choice selected.
 * defaults to US if nothing is passed in. */
{
if($Default == "")
	{
	$Default = "";
	}
	$countriesq = db_query("SELECT Country, Code FROM taxes_countries ORDER BY Country");
	echo "<option value=\"\"></option>";
	while ($countries = db_fetch_object($countriesq)) {
		$Selected = $countries->Code == $Default ? " selected" : "";
		echo "<option value=\"$countries->Code\"$Selected>$countries->Country</option>\n";
	}
}

function CountriesFull($Default='')
/* Display A List of all countries in a list box, with the current choice selected.
 * defaults to US if nothing is passed in. */
{
if($Default == "")
	{
	$Default = "";
	}
	$countriesq = db_query("SELECT Country, Code FROM taxes_countries ORDER BY Country");
	echo "<option value=\"\"></option>";
	echo "<option value=\"United States\">United States</option>";
	while ($countries = db_fetch_object($countriesq)) {
		$Selected = $countries->Country == $Default ? " selected" : "";
		echo "<option value=\"$countries->Country\"$Selected>$countries->Country</option>\n";
	}
}


function CCDays()
/* display the 31 days in a list box */
{
	echo "<option value=\"\">DD</option>";
	for($i=1;$i<32;$i++) {
	printf("<option value=\"%02.0f\">%02.0f</option>", $i, $i);
	}
}

function CCMonths()
/* display the 12 months in a list box */
{
	echo "<option value=\"\">MM</option>";
	for($i=1;$i<13;$i++) {
	printf("<option value=\"%02.0f\">%02.0f</option>", $i, $i);
	}
}

function CCMonths_noMM()
/* display the 12 months in a list box */
{
	echo "<option value=\"\">0</option>";
	for($i=1;$i<13;$i++) {
	printf("<option value=\"%02.0f\">%02.0f</option>", $i, $i);
	}
}



function CCYears()
/* display the next 10 years in a list box */
{
	$Year = date(Y);
	echo "<option value=\"\">YY</option>";
	for($i=0;$i<10;$i++) {
	printf("<option value=\"%02.0f\">%02.0f</option>", $Year, $Year);
	$Year++;
	}
}


function PullInPageText($File)
/* Displays test from include file in location in page */
{
	$PageText = file("$File");
	$Lines = count($PageText);
	for($i=0; $i<$Lines; $i++) {
		echo stripslashes($PageText[$i])."<br>";
	}
}



function GetPageText($PageName)
/* Gets Page Title and Text from DB */
{
	$qid = db_query("SELECT * FROM website_text WHERE PageName = '".mrClean($PageName)."'");
	return db_fetch_object($qid);
}

function GetFaceboxContent($PageName)
/* Gets Page Title and Text from DB */
{
	$qid = db_query("SELECT * FROM website_text WHERE PageName = '".mrClean($PageName)."'");
	return db_fetch_array($qid);
}

function GetMeta($table,$id)
/* Gets Page Title and Text from DB */
{
	$qid = db_query("SELECT * FROM ".$table." WHERE PageName = '".$id."'");
	return db_fetch_object($qid);
}



function DisplayErrorPage($errorList)
{
	global $CFG;
	$PageText->PageTitle = "Oops!!!";
	//include("$CFG->baseroot/standards/admin_area/header.php");
	echo "<br><font size=+1 color=red>$PageText->PageTitle</font><br><br>The following errors were encountered: <br><font size=-1>";
	for ($x=0; $x<sizeof($errorList); $x++) {
		echo "<br>$errorList[$x]";
	}
	echo "</font><br><br><input type=\"button\" name=\"Cancel\" value=\"Go Back\" onclick=\"javascript:history.go(-1);\">";
	//include("$CFG->baseroot/standards/admin_area/footer.php");
	die;
}

function Copyright()
{
	global $SITE;
	if($SITE->CopyrightYear == date("Y")) {
		$CopyrightPeriod = date("Y");
	} else {
		$CopyrightPeriod = "$SITE->CopyrightYear - ".date("Y");
	}
	echo "&copy; $CopyrightPeriod $SITE->Company ALL RIGHTS RESERVED.";
}



function row_color($cnt, $even="ffffff", $odd="F2F2F2") {
	print($cnt%2) ? "bgcolor=\"$odd\"" : "bgcolor=\"$even\"";
}

function row_text_color($cnt, $even="000000", $odd="666666") {
	print($cnt%2) ? " style='font:12pt verdana; color:\"$odd\"'" : " style='font:12pt verdana; color:\"$even\"'";
}



function TextOrHTML($Text, $Format="h")
{
	if($Format == "t") {
		echo nl2br($Text);
	} else {
		echo $Text;
	}
}

function label_states($short_state){

	$qix = db_query("SELECT * FROM taxes_states WHERE Code = '$short_state'"); //AND product = '$frm[product]'
	$dbr = db_fetch_array($qix);
	$pass['new_state'] = $dbr['State'];
	return $pass;

}

function EmailNames($Default='')
/* Display A List of all user emails in a list box, with the current choice selected.
 * defaults to blank if nothing is passed in. */
{

	$emailq = db_query("SELECT first_name, last_name, email FROM users ORDER BY first_name");
	echo "<option value=\"\"></option>";
	while ($email = db_fetch_object($emailq)) {
		$Selected = $email->email == $Default ? " selected" : "";
		echo "<option value=\"$email->email\"$Selected>$email->first_name $email->last_name  [ $email->email ]</option>\n";
	}
}

function pree($var)
/* Echo's $var onto page with a $name */
{
	global $_SESSION;

	if ($_SESSION['user']['priv'] == 'admin') {

		echo "<pre>*";
		print_r($var);
		echo "*</pre>";

	}
}

function pre($var,$action="")
/* Echo's $var onto page with a $name */
{
	global $_SESSION;

	//if (getenv('REMOTE_ADDR') == '76.105.243.172') {

		echo "<pre>*";
		print_r($var);
		echo "*</pre>";
		if ($action == 1) { die; }

	//}

}

function OddOrEven($intNumber)
{
	if ($intNumber % 2 == 0 )
	{
	//your number is even
	$OddOrEven = 0;
	} else {
	//your number is odd
	$OddOrEven = 1;
	}
	return $OddOrEven;
}

function getElapsedString($originalDate, $roundTo=0) {
   $elapsedTime =  time() - $originalDate;

   if($elapsedTime==1) {
       // One second
       $elapsedString = $elapsedTime . " second";
   } else if($elapsedTime<(60*2)) {
       // Seconds
       $elapsedString = $elapsedTime . " seconds";
   } else if($elapsedTime<(60*60*2)) {
       // Minutes
       $elapsedString = round($elapsedTime/60, $roundTo) . " minutes";
   } else if($elapsedTime<(60*60*24*2)) {
       // Hours
       $elapsedString = round($elapsedTime/60/60, $roundTo) . " hours";
   } else if($elapsedTime<(60*60*24*7*2)) {
       // Days
       $elapsedString = round($elapsedTime/60/60/24, $roundTo) . " days";
   } else if($elapsedTime<(60*60*24*30*2)) {
       // Weeks
       $elapsedString = round($elapsedTime/60/60/24/7, $roundTo) . " weeks";
   } else if($elapsedTime<(60*60*24*365*2)) {
       // Months
       $elapsedString = round($elapsedTime/60/60/24/12, $roundTo) . " months";
   } else {
       // Years
       $elapsedString = round($elapsedTime/60/60/24/365, $roundTo) . " years";
   }

   return $elapsedString;
	//echo getElapsedString(time()-2592000);    // Gives "4 weeks"
	//echo getElapsedString(time()-424242, 2);    // Gives "4.91 days"
}

// random alpah numerical generator. works with below function
function assign_rand_value($num)
{
// accepts 1 - 36
  switch($num)
  {
    case "1":
     $rand_value = "a";
    break;
    case "2":
     $rand_value = "b";
    break;
    case "3":
     $rand_value = "c";
    break;
    case "4":
     $rand_value = "d";
    break;
    case "5":
     $rand_value = "e";
    break;
    case "6":
     $rand_value = "f";
    break;
    case "7":
     $rand_value = "g";
    break;
    case "8":
     $rand_value = "h";
    break;
    case "9":
     $rand_value = "i";
    break;
    case "10":
     $rand_value = "j";
    break;
    case "11":
     $rand_value = "k";
    break;
    case "12":
     $rand_value = "l";
    break;
    case "13":
     $rand_value = "m";
    break;
    case "14":
     $rand_value = "n";
    break;
    case "15":
     $rand_value = "o";
    break;
    case "16":
     $rand_value = "p";
    break;
    case "17":
     $rand_value = "q";
    break;
    case "18":
     $rand_value = "r";
    break;
    case "19":
     $rand_value = "s";
    break;
    case "20":
     $rand_value = "t";
    break;
    case "21":
     $rand_value = "u";
    break;
    case "22":
     $rand_value = "v";
    break;
    case "23":
     $rand_value = "w";
    break;
    case "24":
     $rand_value = "x";
    break;
    case "25":
     $rand_value = "y";
    break;
    case "26":
     $rand_value = "z";
    break;
    case "27":
     $rand_value = "0";
    break;
    case "28":
     $rand_value = "1";
    break;
    case "29":
     $rand_value = "2";
    break;
    case "30":
     $rand_value = "3";
    break;
    case "31":
     $rand_value = "4";
    break;
    case "32":
     $rand_value = "5";
    break;
    case "33":
     $rand_value = "6";
    break;
    case "34":
     $rand_value = "7";
    break;
    case "35":
     $rand_value = "8";
    break;
    case "36":
     $rand_value = "9";
    break;
  }
return $rand_value;
}

// random alpah numerical generator. works with above function
function get_rand_id($length)
{
  if($length>0)
  {
  $rand_id="";
   for($i=1; $i<=$length; $i++)
   {
   mt_srand((double)microtime() * 1000000);
   $num = mt_rand(1,36);
   $rand_id .= assign_rand_value($num);
   }
  }
return $rand_id;
}


/* Credit card LUHN checker - coded '05 shaman - www.planzero.org        *
 * This code has been released into the public domain, however please    *
 * give credit to the original author where possible.                    */

  function validatecard($cardnumber) {
    $cardnumber=preg_replace("/\D|\s/", "", $cardnumber);  # strip any non-digits
    $cardlength=strlen($cardnumber);
    $parity=$cardlength % 2;
    $sum=0;
    for ($i=0; $i<$cardlength; $i++) {
      $digit=$cardnumber[$i];
      if ($i%2==$parity) $digit=$digit*2;
      if ($digit>9) $digit=$digit-9;
      $sum=$sum+$digit;
    }
    $valid=($sum%10==0);
    return $valid;
  }

function redirect_location($url,$die="")
{
	if ($die <> "") { pre($die); die; }

	/* redirects to a new URL using location */
	echo"<script language='javascript'>";
	echo"top.location.replace('".$url."');";
	echo"</script>";
	die;

}


/*
 *    Function to check recursively if dirname is exists in directory's tree
 *
 *    @param string $dir_name
 *    @param string [$path]
 *    @return bool
 *    @author FanFataL
 */
function dir_exists($dir_name = false, $path = './') {
   if(!$dir_name) return false;

   if(is_dir($path.$dir_name)) return true;

   $tree = glob($path.'*', GLOB_ONLYDIR);
   if($tree && count($tree)>0) {
       foreach($tree as $dir)
           if(dir_exists($dir_name, $dir.'/'))
               return true;
   }

   return false;
}

/*	MakeDirectory **
how it works: line one attempts to make the directory, and returns TRUE if it works or if it already exists.  That's the easy case if the parent directories all exist.

Line two trims off the last directory name using dirname(), and calls MakeDirectory recursively on that shorter directory.  If that fails, it returns FALSE, but otherwise we come out of it knowing that the parent directory definitely exists.

Finally, presuming the recursive call worked, once we get to line three we can create the requested directory.

Note the use of @ to suppress warning messages from mkdir.

The beauty of this is that if, say, the great-grandparent directory exists but the grandparent and parent directories don't, the function will simply call itself recursively until it gets high enough up the tree to do some work, then carry on unwinding back until all the new directories have been created.
*/
function MakeDirectory($dir, $mode = 0755) { //pre($dir); die;
  if (is_dir($dir) || @mkdir($dir,$mode)) return TRUE;
  if (!MakeDirectory(dirname($dir),$mode)) return FALSE;
  return @mkdir($dir,$mode);
}


function recursiveRemoveDirectory($path)
{
   $dir = new RecursiveDirectoryIterator($path);

   //Remove all files
   foreach(new RecursiveIteratorIterator($dir) as $file)
   {
	   unlink($file);
   }

   //Remove all subdirectories
   foreach($dir as $subDir)
   {
	   //If a subdirectory can't be removed, it's because it has subdirectories, so recursiveRemoveDirectory is called again passing the subdirectory as path
	   if(!@rmdir($subDir)) //@ suppress the warning message
	   {
		   recursiveRemoveDirectory($subDir);
	   }
   }

   //Remove main directory
   rmdir($path);
}

/*   properly escape quotes from an array   */
function CleanArray($array) {
foreach ($array as $key => $value) {
$array[$key] = mysql_real_escape_string($value);
}
return $array;
}

/*   returns a string from the needle and back.   */
function str_from_last_occurrence($haystack,$needle) {
   $pos = strrpos($haystack, $needle);
   if($pos===false) {
       return false;
   } else {
       return substr($haystack, $pos+1);
   }
}

/*   returns a string from the needle to the occurance.   */
function str_to_first_occurrence($haystack,$needle) {
   $pos = strrpos($haystack, $needle);
   if($pos===false) {
       return false;
   } else {
       return substr($haystack,0,$pos);
   }
}

/*   find and replace all   */
function findReplaceAll($needle) {
	return implode('_',explode(' ',strtolower($needle)));
}

/*   replace spaces with underscores andlowercase string   */
function uncap_underscore($value1,$value2="") {
	return $value2.implode('_',explode(' ',strtolower($value1)));
}

/*   format a number with correct comma placement, dollarsign, and decimal length   */
function format_number($value,$haystack='1',$needle='0',$decimal='2') {

	if (find_needle($needle, $haystack)) { $ds = "\$"; }else{ $ds=""; }
	return $ds.number_format($value,$decimal);
}

/*   check for upper or lower case   */
function isupper($i) { return (strtoupper($i) === $i);}
function islower($i) { return (strtolower($i) === $i);}

/*   preload function used by the AJAX Rich Text Editor   */
function freeRTE_Preload($content) {
	// strip newline characters.
	$content = str_replace(chr(10), " ", $content);
	$content = str_replace(chr(13), " ", $content);
	// Replace single quotes.
	$content = str_replace(chr(145), chr(39), $content);
	$content = str_replace(chr(146), chr(39), $content);
	// Return the result.
	return $content;
}

/*   This is a useful script for displaying MySQL results in an HTML table.   */
function array2table($arr,$width){
   $count = count($arr);
   if($count > 0){
       reset($arr);
       $num = count(current($arr));
       echo "<table align=\"center\" border=\"1\"cellpadding=\"5\" cellspacing=\"0\" width=\"$width\">\n";
       echo "<tr>\n";
       foreach(current($arr) as $key => $value){
           echo "<th>";
           echo $key."&nbsp;";
           echo "</th>\n";
           }
       echo "</tr>\n";
       while ($curr_row = current($arr)) {
           echo "<tr>\n";
           $col = 1;
           while ($curr_field = current($curr_row)) {
               echo "<td>";
               echo $curr_field."&nbsp;";
               echo "</td>\n";
               next($curr_row);
               $col++;
               }
           while($col <= $num){
               echo "<td>&nbsp;</td>\n";
               $col++;
           }
           echo "</tr>\n";
           next($arr);
       }
       echo "</table>\n";
       }
  }

 /*   this function parses an url, then makes it into a readable array   */
/*   ****************************************************************   */
/*   $input = "listContainer1[]=2&listContainer1[]=6&listContainer2[]=11&listContainer2[]=22&listContainer2[]=33";   */
/*   $listname = array("listContainer1","listContainer2");   */
/*   ****************************************************************   */
function getOrderArray($input,$listname,$itemKeyName = 'element',$orderKeyName = 'order') {
	parse_str($input,$inputArray);

	/*   $listname can possibly be an array. check for this.  */
	if (is_array($listname)) {
		for ($j = 0; $j < count($listname); $j++) {
			$inputNumArray = $inputArray[$listname[$j]];
			$orderArray[$j] = array();
			for($i=0;$i<count($inputNumArray);$i++) {
				$orderArray[$j][] = array($itemKeyName => $inputNumArray[$i], $orderKeyName => $i +1);
			}
		}
		return $orderArray;
	}else{
		$inputNumArray = $inputArray[$listname];
		$orderArray = array();
		for($i=0;$i<count($inputNumArray);$i++) {
			$orderArray[] = array($itemKeyName => $inputNumArray[$i], $orderKeyName => $i +1);
		}
		return $orderArray;
	}
}

/*   finds the count of days between two dates */
function days_between($fyear, $fmonth, $fday, $tyear, $tmonth, $tday)
{
  return @abs((mktime ( 0, 0, 0, $fmonth, $fday, $fyear) - mktime ( 0, 0, 0, $tmonth, $tday, $tyear))/(60*60*24));
}

function day_before($fyear, $fmonth, $fday, $subtract)
{
  return date ("Y-m-d", mktime (0,0,0,$fmonth,$fday-$subtract,$fyear));
}

function next_day($fyear, $fmonth, $fday)
{
  return date ("Y-m-d", mktime (0,0,0,$fmonth,$fday+1,$fyear));
}

function dateafter ( $a ){
	$hours = $a * 24;
	$added = ($hours * 3600)+time();
	$day = gmdate("d", $added);
	$month = gmdate("m", $added);
	$year = gmdate("Y", $added);
	$result = $year."-".$month."-".$day;
	return ($result);
}

function dateafter_meta ($a)
{
    $hours = $a * 24;
    $added = ($hours * 3600)+time();
    $days = date("D", $added);
    $month = date("M", $added);
    $day = date("j", $added);
    $year = date("Y", $added);
    $result = $days.", ".$day." ".$month." ".$year." 00:00:00 GMT";
    return ($result);
}


function weekday($fyear, $fmonth, $fday) //0 is monday
{
  return (((mktime ( 0, 0, 0, $fmonth, $fday, $fyear) - mktime ( 0, 0, 0, 7, 17, 2006))/(60*60*24))+700000) % 7;
}

function prior_monday($fyear, $fmonth, $fday)
{
  return date ("Y-m-d", mktime (0,0,0,$fmonth,$fday-weekday($fyear, $fmonth, $fday),$fyear));
}

function next_monday()
{

	$monday = date('Y-m-d', strtotime('last Monday',strtotime('Sunday')));
	$unixtime_monday = strtotime($monday);
	$next_monday = date('Y-m-d', strtotime('+1 week', $unixtime_monday));

	return $next_monday;
}

function formated_date($date, $sep="-") {

	$year = substr($date,2,2);
	$month = substr($date,5,2);
	$day = substr($date,8,2);

	$formated_date = $month.$sep.$day.$sep.$year;
	Return $formated_date;

}

function timer($token,$on='') {

	if ($token == 1) {

		$mtime = microtime();
		$mtime = explode(' ', $mtime);
		$mtime = $mtime[1] + $mtime[0];
		unset($_SESSION['timer']);
		$_SESSION['timer']['starttime'] = $mtime;

	}elseif ($token == 0) {

		$mtime = microtime();
		$mtime = explode(" ", $mtime);
		$mtime = $mtime[1] + $mtime[0];
		$_SESSION['timer']['endtime'] = $mtime;
		$totaltime = ($_SESSION['timer']['endtime'] - $_SESSION['timer']['starttime']);
		unset($_SESSION['timer']);

		if ($on == 1) { timer(1); }

		Return substr($totaltime,0,5).' Seconds.';

	}

}

function find_needle($needle, $haystack){
	$pos = strpos($haystack, $needle);
	if ($pos === false) { return false; }
	else { return true; }
}

function ucwords_ext($needle){
	return ucwords(strtolower($needle));
}

function filter_sql($input) {
	$reg = "(delete)|(update)|(union)|(insert)";
	return(eregi_replace($reg, "", $input));
}

function stripslashes2($string) {
	$string = str_replace("\\\"", "\"", $string);
	$string = str_replace("\\'", "'", $string);
	$string = str_replace("\\\\", "\\", $string);
	return $string;
}

function mrClean($var) {

	// prevent SQL comands
	//$var = filter_sql($var);

	//
	$var = stripslashes2($var);

	// clean with add slaches tags
	$var = addslashes($var);

	// change & to &amp;
	$var = str_replace('&', '&amp;', $var);

	Return $var;

}

function prep_var($var,$form="") {

	$var = stripslashes($var);

	// change &amp; to &
	//$var = html_entity_decode($var);

	$var = str_replace('&amp;', '&', $var);

	if ($form == "") {

		// change <br> to /n
		$var = str_replace(chr(13), '<br>', $var); //"\n"  chr(13)


	}else{

		// change /n to <br>
		$var = str_replace('<br>', chr(13), $var); //"\n"  chr(13)

	}

	Return $var;

}

function removeWhitespace($string)
{
    if (!is_string($string))
        return false;

    $string = preg_quote($string, '|');
    return preg_replace('|  +|', ' ', $string);
}

function is_empty($val) {
    $val = trim($val);
    return empty($val) && $val !== 0;
}

function rm($fileglob,$files_only='1')
{
    if (is_string($fileglob)) {
        if (is_file($fileglob)) {
            return unlink($fileglob);
        } else if (is_dir($fileglob) && $files_only==0) {
            $ok = rm("$fileglob/*");
            if (! $ok) {
                return false;
            }
            return rmdir($fileglob);
        } else {
            $matching = glob($fileglob);
            if ($matching === false) {
                trigger_error(sprintf('No files match supplied glob %s', $fileglob), E_USER_WARNING);
                return false;
            }
            $rcs = array_map('rm', $matching);
            if (in_array(false, $rcs)) {
                return false;
            }
        }
    } else if (is_array($fileglob)) {
        $rcs = array_map('rm', $fileglob);
        if (in_array(false, $rcs)) {
            return false;
        }
    } else {
        trigger_error('Param #1 must be filename or glob pattern, or array of filenames or glob patterns', E_USER_ERROR);
        return false;
    }

    return true;
}

function da_copy($title, $block, $val='', $no_edit='') {

	global $_SESSION, $CFG;

	$_qid = db_query("SELECT PageID, PageTitle, PageText, content_block, gallery_id, video_id FROM website_text WHERE PageName = '".$title."' AND content_block = '".$block."'");
	$qid = db_fetch_assoc($_qid);

	if ($val == 1) { // copy

		$r = $qid['PageTitle'];

		if ($_SESSION['user']['priv'] == 'admin') {

			$r .= "&nbsp;&nbsp;<a href=\"".$CFG->host."/admin/pages/pagetext_update.php?id=".$qid['PageID']."\" style=\"font-size:11px;\" target=\new\"><u>edit</u></a>";

		}

	}elseif ($val == 2) { // images

		$r = $qid['gallery_id'];

	}elseif ($val == 4) { // videos

	$_qix = db_query("SELECT embed_code FROM videos WHERE id = '".$qid['video_id']."'");
	$qix = db_fetch_assoc($_qix);

	$r = $qix['embed_code'];

	}elseif ($val == 3) { // blog

		$r = $qid['PageTitle'];

		if ($_SESSION['user']['priv'] == 'admin') {

			$r .= "&nbsp;&nbsp;<a href=\"".$CFG->host."/admin/blog/index.php\" style=\"font-size:11px;\" target=\new\"><u>edit</u></a>";

		}

	}else{

		$r = $qid['PageText'];

	}

	Return $r;

}

function content($title) {

	global $_SESSION, $CFG;

	if (is_numeric($title)) { $clause = "PageID = '".$title."'"; }
	else{ $clause = "PageName = '".$title."'"; }

	$_qid = db_query("SELECT PageID, PageTitle, PageText, content_block, gallery_id, video_id FROM website_text WHERE ".$clause." LIMIT 1",0,0);
	$qid = db_fetch_assoc($_qid);

	$block[0][0] = $qid['PageTitle'];

	if ($_SESSION['user']['priv'] == 'admin') {

		$block[0][0] .= "&nbsp;&nbsp;<a href=\"".$CFG->host."/admin/pages/pagetext_update.php?id=".$qid['PageID']."\" style=\"font-size:11px;\" target=\"new\"><u>edit</u></a>";

	}

	$block[0][1] = $qid['PageText'];
	$block[0][2] = $qid['PageTitle'];

	if (!is_empty($qid['gallery_id'])) {

		$_qix = db_query("SELECT name FROM galleries WHERE id = '".$qid['gallery_id']."'");
		$qix = db_fetch_assoc($_qix);

		$block[1][0] = $qix['name'];
		$block[1][1] = $qid['gallery_id'];

	}

	if (!is_empty($qid['video_id'])) {

		$_qix = db_query("SELECT name, embed_code FROM videos WHERE id = '".$qid['video_id']."'");
		$qix = db_fetch_assoc($_qix);

		$block[2][0] = $qix['name'];
		$block[2][1] = $qix['embed_code'];

	}

	Return $block;

}

/*   ModRewrite fucntion for unlimited params. Modified from http://www.webdesign.org/web/web-programming/php/rewritten-urls-with-unlimited-parameters.10144.html   */
function setMod(){

	$keys = array('dirs','ukey','file');
	$input = explode( '/', $_GET['mod'] );

	for ($i = 0; $i < count($input); $i++) {
		$input_array[$keys[$i]]=$input[$i];
	}

	//$input_array[$keys[0]]=$input[0];
	//if (count($input)==2) { $input_array[$keys[2]]=$input[1]; }

	//if (count($input)==3) {
	//	$input_array[$keys[1]]=$input[1];
	//	$input_array[$keys[2]]=$input[2];
	//}

	Return $input_array;

}

/*   make slug   */
function slugMaker($slug,$table) {

	$slug = strtolower(trim($slug));
	$slug = preg_replace( '/[^a-z0-9- ]/', '', $slug);
	$slug = str_replace(" ", "-", $slug);
	$slug = str_replace("--", "-", $slug);

	$i = ''; // start with no appended value
	$x = '';

	while (slugExists($slug.$x,$table)) { // if the slug already exists..
		if ($i == '') { $i=1; }
		$i++;
		$x = '-'.$i; // increment the appended value
	}
	if ($i>0) { $slug .= '-'.$i; } // append the value to the real slug

	Return $slug;


}

function slugExists($slug,$table) {

	$_qdb = db_query("SELECT slug FROM ".$table." WHERE slug = '".$slug."'");
	if ( db_num_rows( $_qdb ) ) { // if a post with this slug was found..
		return TRUE; // ..this slug exists
	} else { // if not..
		return FALSE; // ..this slug does not exist
	}

}

/*   make slug   */
function usernameMaker($username) {

	$username = strtolower(trim($username));
	$username = preg_replace( '/[^a-z0-9- ]/', '', $username);
	$username = str_replace(" ", "-", $username);
	$username = str_replace("--", "-", $username);

	$i = ''; // start with no appended value
	$x = '';

	while (usernameExists($username.$x)) { // if the username already exists..
		if ($i == '') { $i=1; }
		$i++;
		$x = '-'.$i; // increment the appended value
	}
	if ($i>0) { $username .= '-'.$i; } // append the value to the real username

	Return $username;


}

function usernameExists($username) {

	$_qdb = db_query("SELECT username FROM users WHERE username = '".$username."'");
	if ( db_num_rows( $_qdb ) ) { // if a post with this username was found..
		return TRUE; // ..this username exists
	} else { // if not..
		return FALSE; // ..this username does not exist
	}

}

function facebox($action='') {

	if (!is_empty($action)) {

		global $_SESSION;

		if ($action == 'reg-payment-canceled') {
			$copy = GetFaceboxContent("Registration Payment Canceled");
		}elseif ($action == 'donation-canceled') {
			$copy = GetFaceboxContent("Donation Canceled");
		}elseif ($action == 'donation-thank-you') {
			$copy = GetFaceboxContent("Donation Thank You");
		}elseif ($action == 'order-successful') {
			$copy = GetFaceboxContent("Order Successful");
		}elseif ($action == 'order-canceled') {
			$copy = GetFaceboxContent("Payment Not Successful");
		}elseif ($action == 'unsubscribed') {
			$copy = GetFaceboxContent("Unsubscribed From Comments");
		}elseif ($action == 'alogin') {
			$copy = GetFaceboxContent("Admin Login Successful");
		}elseif ($action == 'registered' || $action == 'reg-error' || $action == 'reg-activated') {
			$copy[PageTitle] = "Registration Status";
			$copy[PageText] = $_SESSION['facenote'];
		}elseif ($action == 'contact') {
			$copy[PageTitle] = "Mail Status";
			$copy[PageText] = $_SESSION['facenote'];
		}elseif ($action == 'login-fail-activation') {
			$copy = GetFaceboxContent("Activation Required");
		}elseif ($action == 'login-fail') {
			$copy = GetFaceboxContent("Login Failed");
		}elseif ($action == 'login') {
			$copy = GetFaceboxContent("Login Successful");
		}elseif ($action == 'logout') {
			$copy = GetFaceboxContent("Logout Successful");
		}elseif ($action == 'reg-pass') {
			$copy = GetFaceboxContent("Registration Successful");
		}elseif ($action == 'reg-fail') {
			$copy = GetFaceboxContent("Registration Failed");
		}elseif ($action == 'request-submitted') {
			$copy = GetFaceboxContent("Special Request Submitted");
		}elseif ($action == 'password-reset-failed') {
			$copy = GetFaceboxContent("Password Reset Failed");
		}elseif ($action == 'no-email-exists') {
			$copy = GetFaceboxContent("No Email Exists");
		} ?>

		<?php if (!is_empty($copy['PageTitle'])) { ?>

			<div id="facebox">

				<div class="facebox_border">

					<div><h2 class="faceboxh2" <?php if (!is_empty($copy['error'])) { ?>style="background-color:#C60707;"<?php } ?>><? pv($copy['PageTitle']); ?></h2></div>
					<div style="margin:10px 0 15px 0; font-size:13px;"><? TextOrHTML($copy['PageText']); ?></div>
					<div style="color:#666; font-size:10px; float:left; padding-top:10px;">To close, click the Close button or hit the ESC key.</div>
					<div style="float:right;"><button class="close"> Close </button></div>
					<br class="clear">

				</div>

			</div>

		<?php } ?>

	<?php } ?>

<?php }

function ripcurl($url,$data="") {


	/*
	if (!is_empty($data)) { $data = "?".$data; }
	pre($url.$data);
	$handle = fopen($url.$data, "r");
	$response = fread($handle,8192);
	*/

	/*
	*/
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_FAILONERROR, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_AUTOREFERER, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	if (!is_empty($data)) { curl_setopt($ch, CURLOPT_POSTFIELDS, $data); }
	$response= curl_exec($ch);
	curl_close($ch);

	Return $response;

}

function smtp_mail($to, $subject, $message, $replyto="") {

	global $CFG, $_SESSION;

	define('DISPLAY_XPM4_ERRORS', true); // display XPM4 errors
	require_once ($CFG->baseroot.'/standards/classes/smtp_mail/MAIL.php'); // path to 'MAIL.php' file from XPM4 package
	mb_internal_encoding("utf-8");

	$m = new MAIL; // initialize MAIL class

	if (!is_empty($replyto)) { $m->AddHeader('Reply-To', $replyto); }

	$m->From('no-reply@theyoungturks.com', 'The Young Turks', 'UTF-8'); // set from address

	/*   remove 2 lines when live   */
	//unset($to);
	//$to[] = array('cesar@cesarvillaca.com','Cesar','Villaca');

	if (is_empty($to[0][1])) { $to[0][1] = str_to_first_occurrence($to[0][0],'@'); }
	if (is_empty($to[0][2])) { $to[0][2] = "@".str_from_last_occurrence($to[0][0],'@'); }

	/*   make inviote email into an array   */
	if (!is_array($to)) {

		$m->AddTo($to,str_to_first_occurrence($to,'@')."@".str_from_last_occurrence($to,'@'), 'UTF-8');

	}elseif (count($to)==1) {

		if (find_needle("@",$to[0][2])) { $name = $to[$i][1].$to[$i][2]; }
		else{ $name = $to[$i][1]." ".$to[$i][2]; }

		$m->AddTo($to[0][0], $name, 'UTF-8');

	}elseif (count($to)>1) {

		for ($i = 0; $i < count($to); $i++) {

			if (find_needle("@",$to[0][2])) { $name = $to[$i][1].$to[$i][2]; }
			else{ $name = $to[$i][1]." ".$to[$i][2]; }

			if ($i == 0){ $m->AddTo($to[$i][0], $name, 'UTF-8'); }
			else		{ $m->AddCc($to[$i][0], $name, 'UTF-8'); }
		}

	}

	/*   send an email to cesar   */
	//$m->AddBcc('cesar@cesarvillaca.com','Cesar Villaca', 'UTF-8');

	$m->Subject($subject, 'UTF-8'); // set subject
	$m->Html($message, 'UTF-8'); // set text message

	// connect to MTA server 'smtp.hostname.net' port '25' with authentication: 'username'/'password'
	$c = $m->Connect('smtp.cesarvillaca.com', 25, 'sb@cesarvillaca.com', 'zxcv123') or die(print_r($m->Result));

	// send mail relay using the '$c' resource connection
	return $m->Send($c) ? '1' : '0';

	$m->Disconnect(); // disconnect from server

}

function get_clean_text_string($string) { // FORCE IT TO ACCEPTABLE CHARACTERS ONLY
   $new = trim(ereg_replace('[^\' a-zA-Z0-9&!#$%()"+:?/@,_\.\-]', '', $string));
   return ereg_replace(' +', ' ', $new);
}

function unixToMySQL($timestamp){
    return date('Y-m-d H:i:s', $timestamp);
}

/*   http://www.the-art-of-web.com/php/sortarray/   */
function orderBy($data, $field, $reverse_sort='') {
	$code = "return strnatcmp(\$a['$field'], \$b['$field']);";
	usort($data, create_function('$a,$b', $code));
	if ($reverse_sort==1) { krsort($data); }
	elseif ($reverse_sort==2) { krsort($data); $data = array_values($data); } // reset keys
	return $data;
}


/*	A time difference function that outputs the time passed in facebook's style: 1 day ago, or 4 months ago. I took andrew dot macrobert at gmail dot com function and tweaked it a bit. On a strict enviroment it was throwing errors, plus I needed it to calculate the difference in time between a past date and a future date.

$date = "2009-03-04 17:45";
$result = nicetime($date); // 2 days ago
*/
function nicetime($date){

    if(empty($date)) {
        return "No date provided";
    }

    $periods         = array("second", "minute", "hour", "day", "week", "month", "year", "decade");
    $lengths         = array("60","60","24","7","4.35","12","10");

    $now             = time();
    $unix_date       = strtotime($date);

       // check validity of date
    if(empty($unix_date)) {
        return "Bad date";
    }

    // is it future date or past date
    if($now > $unix_date) {
        $difference     = $now - $unix_date;
        $tense         = "ago";

    } else {
        $difference     = $unix_date - $now;
        $tense         = "from now";
    }

    for($j = 0; $difference >= $lengths[$j] && $j < count($lengths)-1; $j++) {
        $difference /= $lengths[$j];
    }

    $difference = round($difference);

    if($difference != 1) {
        $periods[$j].= "s";
    }

    return "$difference $periods[$j] {$tense}";
}

function dl_file_resumable($file, $valpath, $is_resume=TRUE){

    //First, see if the file exists
    if (!fopen($valpath, "r"))
    {
        die("<b>404 File not found!</b>");
    }

    //Gather relevent info about file
    $size = filesize($valpath);
    $fileinfo = pathinfo($file);

    //workaround for IE filename bug with multiple periods / multiple dots in filename
    //that adds square brackets to filename - eg. setup.abc.exe becomes setup[1].abc.exe
    $filename = (strstr($_SERVER['HTTP_USER_AGENT'], 'MSIE')) ?
                  preg_replace('/\./', '%2e', $fileinfo['basename'], substr_count($fileinfo['basename'], '.') - 1) :
                  $fileinfo['basename'];

    $file_extension = strtolower($path_info['extension']);

    //This will set the Content-Type to the appropriate setting for the file
    switch($file_extension)
    {
        case 'exe': $ctype='application/octet-stream'; break;
        case 'zip': $ctype='application/zip'; break;
        case 'mp3': $ctype='audio/mpeg'; break;
        case 'mpg': $ctype='video/mpeg'; break;
        case 'mp4': $ctype='video/mpeg'; break;
        case 'avi': $ctype='video/x-msvideo'; break;
        default:    $ctype='application/force-download';
    }

    //check if http_range is sent by browser (or download manager)
    if($is_resume && isset($_SERVER['HTTP_RANGE']))
    {
        list($size_unit, $range_orig) = explode('=', $_SERVER['HTTP_RANGE'], 2);

        if ($size_unit == 'bytes')
        {
            //multiple ranges could be specified at the same time, but for simplicity only serve the first range
            //http://tools.ietf.org/id/draft-ietf-http-range-retrieval-00.txt
            list($range, $extra_ranges) = explode(',', $range_orig, 2);
        }
        else
        {
            $range = '';
        }
    }
    else
    {
        $range = '';
    }

    //figure out download piece from range (if set)
    list($seek_start, $seek_end) = explode('-', $range, 2);

    //set start and end based on range (if set), else set defaults
    //also check for invalid ranges.
    $seek_end = (empty($seek_end)) ? ($size - 1) : min(abs(intval($seek_end)),($size - 1));
    $seek_start = (empty($seek_start) || $seek_end < abs(intval($seek_start))) ? 0 : max(abs(intval($seek_start)),0);

    //add headers if resumable
    if ($is_resume)
    {
        //Only send partial content header if downloading a piece of the file (IE workaround)
        if ($seek_start > 0 || $seek_end < ($size - 1))
        {
            header('HTTP/1.1 206 Partial Content');
        }

        header('Accept-Ranges: bytes');
        header('Content-Range: bytes '.$seek_start.'-'.$seek_end.'/'.$size);
    }

    //headers for IE Bugs (is this necessary?)
    //header("Cache-Control: cache, must-revalidate");
    //header("Pragma: public");

    header('Content-Type: ' . $ctype);
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Length: '.($seek_end - $seek_start + 1));

    //open the file
    $fp = fopen($valpath, 'rb');

    //seek to start of missing part
    fseek($fp, $seek_start);

    //start buffered download
    while(!feof($fp))
    {
        //reset time limit for big files
        set_time_limit(0);
        print(fread($fp, 1024*8));
        flush();
        ob_flush();
    }

    fclose($fp);
    exit;
}

//gets the data from a URL
function get_isgd_url($url)
{
	//get content
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch,CURLOPT_URL,'http://is.gd/api.php?longurl='.$url);
	curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
	curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
	$content = curl_exec($ch);
	curl_close($ch);

	//return the data
	return $content;
}

function countdown($date="", $time="", $datetime="") {

	$date = array();

	if (!is_empty($date)) {

		$exp_date = explode("-",$date);
		$date['year'] = $exp_date[0];
		$date['month'] = $exp_date[1];
		$date['day'] = $exp_date[2];

	}

	if (!is_empty($time)) {

		if (find_needle("AM",$time)) {

			$exp_time = explode(":",str_replace("AM","",$time));
			$date['hour'] = $exp_time[0]-5; // -5 is for UTC offset to USA East Coast
			$date['minute'] = $exp_time[1];

		}elseif (find_needle("PM", $time)) {

			$exp_time = explode(":",str_replace("PM","",$time));
			$date['hour'] = ($exp_time[0]+12)-5; // -5 is for UTC offset to USA East Coast
			$date['minute'] = $exp_time[1];

		}

		$date['second'] = 0;

	}

	if (!is_empty($datetime)) {

		$exp = explode(" ",$datetime);

		$exp_date = explode("-",$exp[0]);
		$date['year'] = $exp_date[0];
		$date['month'] = $exp_date[1];
		$date['day'] = $exp_date[2];

		$exp_time = explode(":",$exp[1]);
		$date['hour'] = $exp_time[0]-5; // -5 is for UTC offset to USA East Coast
		$date['minute'] = $exp_time[1];
		$date['second'] = $exp_time[2];

	}

	$now = time();
	$target = mktime($date['hour'], $date['minute'], $date['second'], $date['month'], $date['day'], $date['year']);
	//pre($now);
	//pre($target);

	$diffSecs = $target - $now;

	$date['secs'] = $diffSecs % 60;
	$date['mins'] = floor($diffSecs/60)%60;
	$date['hours'] = floor($diffSecs/60/60)%24;
	$date['days'] = floor($diffSecs/60/60/24)%7;
	$date['weeks']	= floor($diffSecs/60/60/24/7);

	foreach ($date as $i => $d) {
		$d1 = $d%10;
		$d2 = ($d-$d1) / 10;
		$date[$i] = array(
			(int)$d2,
			(int)$d1,
			(int)$d
		);
	}

	Return $date;

}

function encrypt($text)
{
	return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, CC_SALT, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
}

function decrypt($text)
{
	return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, CC_SALT, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
}

//$encryptedmessage = encrypt($val_to_encrypt);
//pre($encryptedmessage);
//pre(decrypt($encryptedmessage));

function leadingZeros($num,$numDigits) {
   return sprintf("%0".$numDigits."d",$num);
}

function latlon_from_address($address="", $zip="") {

	$loc = urlencode($address.", ".$zip);
	//pre($loc);

	$url = 'http://maps.google.com/maps/geo?q='.$loc.'&output=xml&oe=utf8&key='.MAPS_API;
	$dom = new DomDocument();
	$dom->load($url);

	$xpath = new DomXPath($dom);
	$xpath->registerNamespace('ge', 'http://earth.google.com/kml/2.0');

	$statusCode = $xpath->query('//ge:Status/ge:code');

	// Check if statusCode = 200
	if ($statusCode->item(0)->nodeValue == '200') {

		$pointStr = $xpath->query('//ge:coordinates');
		$point = explode(",", $pointStr->item(0)->nodeValue);

		$latlon['lat'] = $point[1];
		$latlon['lon'] = $point[0];

		Return $latlon;

		//db_query("UPDATE locations SET lat = '".$lat."', lon = '".$lon."' WHERE id = '".$qdc['id']."'",1,0);

	}else{

		latlon_from_address($address, $zip);

	}

}

function url_exists($url) {
    // Version 4.x supported
    $handle   = curl_init($url);
    if (false === $handle)
    {
        return false;
    }
    curl_setopt($handle, CURLOPT_HEADER, false);
    curl_setopt($handle, CURLOPT_FAILONERROR, true);  // this works
    curl_setopt($handle, CURLOPT_HTTPHEADER, Array("User-Agent: Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.15) Gecko/20080623 Firefox/2.0.0.15") ); // request as if Firefox
    curl_setopt($handle, CURLOPT_NOBODY, true);
    curl_setopt($handle, CURLOPT_RETURNTRANSFER, false);
    $connectable = curl_exec($handle);
    curl_close($handle);
    return $connectable;
}

function getFileSize($filename) {

	$starttime = getmicrotime();

	if (isset($assumeFormat)) {
		$MP3fileInfo = GetAllMP3info($filename, $assumeFormat);
	} else {
		$MP3fileInfo = GetAllMP3info($filename, '');
		if (!isset($MP3fileInfo['fileformat']) || ($MP3fileInfo['fileformat'] == '')) {
			$formatExtensions = array('mp3'=>'mp3', 'ogg'=>'ogg', 'zip'=>'zip', 'wav'=>'riff', 'avi'=>'riff', 'mid'=>'midi', 'mpg'=>'mpeg', 'mp4'=>'mpeg', 'jpg'=>'image', 'gif'=>'image', 'png'=>'image');
			if (isset($formatExtensions[fileextension($filename)])) {
				$MP3fileInfo = GetAllMP3info($filename, $formatExtensions[fileextension($filename)]);
			}
		}
	}

	//pre($MP3fileInfo);

	Return $MP3fileInfo;

}

function buildPath($show,$type,$date,$filename,$clip_name) {

	global $_SESSION;

	if ($filename == 1) { // build path for XML file name

		if ($type == "free") {

			Return "young-turks-free-hour.xml";

		}else{

			$tytshows = $_SESSION['shows'];
			if ($show == "TYT Network") { $tytshows["TYT Network"] = "tyt-network"; }

			Return $tytshows[$show]."-".$free.$type.".xml";

		}

		//pre($show);
		//pre($_SESSION['shows'][$show]."-".$free.$type.".xml");

	}else{ // build path for CDN location

		if ($type == "free") {

			Return "/young-turks/free-audio/young-turks-free-".$date.".mp3";

		}else{

			if ($type == "audio") { $ext =  ".mp3"; }
			elseif ($type == "video") { $ext =  ".mp4"; }

			if ($type == "clips") {

				Return "/".$_SESSION['shows'][$show]."/".$type."/".$clip_name;

			}else{

				Return "/".$_SESSION['shows'][$show]."/".$free.$type."/".$_SESSION['shows'][$show]."-".$free.$date.$ext;

			}

		}

	}

}

function SetCookieLive($name, $value='', $expire = 0, $path = '', $domain='', $secure=false, $httponly=false)
{
	$_COOKIE[$name] = $value;
	return setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);
}
?>