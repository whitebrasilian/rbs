<?php
//error_reporting(E_ALL);
error_reporting(E_ERROR);

class object {};
$CFG = new object;

/* Load Base Vars */
if ($_SERVER['HTTP_HOST'] == 'localhost') {
	$CFG->host = "/safari/www";
	$windir = "C:/apache2triad/htdocs/ImageMagick-6.5.3-Q16";
	define("SECURE", $CFG->host);
	define("MAPS_API", "ABQIAAAA6vv53gLa8mRn_rX6w2ea4RT2yXp_ZAY8_ufC3CFXhHIE1NvwkxQsacPbWBoYV2AwcinPRcqadLAkYw");
}else{
	$CFG->host = "";
	define("SECURE", "https://secure.riverbanksafaris.com");
	define("MAPS_API", "ABQIAAAA6vv53gLa8mRn_rX6w2ea4RTNoeSVIeB8_ibW-Nf2RFLzECMKgBQR0l4BVF39OQCK2otaVzj2NafmbA");
}

$CFG->siteip    = $_SERVER['SERVER_ADDR'];
$CFG->siteurl   = $_SERVER['SERVER_NAME'];
$CFG->baseroot  = $_SERVER['DOCUMENT_ROOT'].$CFG->host;

define("SUBDOMAIN", 'dev');
define("HOST", $CFG->host);
define("UPLOADS", $CFG->baseroot."/images/galleries");
define("PRODUCT_UPLOADS", $CFG->baseroot."/images/products");
define("AJAX", $CFG->host."/standards/ajax");
define("IMAGES", $CFG->host."/images/site");
define("STANDARDS", $CFG->baseroot."/standards");
define("JS", $CFG->host."/js");
define("MAGICKPATH", $windir);
define('CC_SALT', "7c3536dc30a4c61e2c1c2ceea531205c");

/* define database error handling behavior */
$DB_DEBUG = true;
$DB_DIE_ON_FAIL = true;

/* load up standard libraries */
require($CFG->baseroot."/standards/functions/dblib.php");
require($CFG->baseroot."/standards/db_connect.php");
require($CFG->baseroot."/standards/functions.php");

/*   break php_self apart   */
$php_self_exploded = explode('/',$_SERVER['PHP_SELF']);

if (!in_array('cart',$php_self_exploded) && $_SERVER['HTTPS']=='on') {
	redirect_location(HOST.$_SERVER['REQUEST_URI']);
}

/* start up the sessions, to keep things clean and manageable we will just
 * use one array called SESSION to store our persistent variables.   */
include("$CFG->baseroot/standards/functions/session_cleaner.php");
cleanSessionFolder("$CFG->baseroot/standards/sessions");// add to debug ,1
session_save_path("$CFG->baseroot/standards/sessions/");
//if ($_SERVER['HTTP_HOST'] != 'localhost') { session_set_cookie_params(1500, '/', 'theyoungturks.cesarvillaca.com'); }
session_start();
$_SESSION['SessionID'] = session_id();

/* initialize the SESSION variable if necessary */
if (! isset($_SESSION)) { $_SESSION = array(); }

/* connect to the database */
db_connect($CFG->dbhost, $CFG->dbname, $CFG->dbuser, $CFG->dbpass);

/* check to see if user has cookie saved */
if (is_null($_SESSION['user'])) {

	if(isset($_COOKIE['cookname']) && isset($_COOKIE['cookpass'])){

		$qid = db_query("SELECT * FROM users WHERE (username = '".$_COOKIE['cookname']."' OR email = '".$_COOKIE['cookname']."') AND password = '".$_COOKIE['cookpass']."' AND activate_date <> ''");
		$user = db_fetch_array($qid);

		if ($user) {

			$_SESSION['user'] = $user;
			$_SESSION['ip'] = getenv('REMOTE_ADDR');

		}

	}

}


$_SESSION[ip] = getenv('REMOTE_ADDR');

define("BROWSER", browser_detection('browser'));
define("NUMBER", browser_detection('number'));
define("OS", browser_detection('os'));
define("OS_NUMBER", browser_detection('os_number'));
$mozversion = browser_detection('moz_version');
define("MOZ_VERSION", $mozversion[0]." ".$mozversion[1]);
define("IE_VERSION", browser_detection('ie_version'));
/*
pre(BROWSER);
pre(NUMBER);
pre(OS);
pre(OS_NUMBER);
pre(MOZ_VERSION);
pre(IE_VERSION);
*/

$browser_info = browser_detection('full');
//pre($browser_info);

/* load site settings into SITE object */
$qid = db_query("SELECT * FROM site_settings WHERE id = 1");
$SITE = db_fetch_object($qid);

if($SITE->ProductsPerPage < 1) { $SITE->ProductsPerPage = 10; }

$headers  = "MIME-Version: 1.0\n";
$headers .= "Content-type: text/html; charset=iso-8859-1\n";
$headers .= "From: $SITE->Email\n";
$headers .= "Reply-To: $SITE->Email\n";
$headers .= "X-Mailer: PHP"."\n";

/*   assign $_vars   */
if($_SERVER['REQUEST_METHOD']=="GET"){
	$_vars = $_GET;
}else if($_SERVER['REQUEST_METHOD']=="POST"){
	$_vars = $_POST;
}

/*   start tab index   */
$ti=2;


/*   do some mod rewrite if passed
if (!is_empty($_vars['mod'])) {

	unset($_vars);
	$_vars = setMod();
	pre($_vars,1);

}
*/

/*   prevent form injection   */
if (!in_array('manager',$php_self_exploded) && is_array($_POST)) {

	foreach ($_POST as $key => $value)
	{
		if (!is_array($_POST[$key])) {
			$key = get_clean_text_string($key);
			$value = get_clean_text_string($value);
			$clean_post["$key"] = mysql_real_escape_string($value);
		}else{
			$clean_post["$key"] = $_POST[$key];
		}
	}

	$_POST = $clean_post;

}
?>