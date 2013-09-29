<?php

define('DISPLAY_XPM4_ERRORS', true); // display XPM4 errors
require_once 'MAIL.php'; // path to 'MAIL.php' file from XPM4 package

$m = new MAIL; // initialize MAIL class
$m->From('sb@cesarvillaca.com'); // set from address
$m->AddTo('cesar@cesarvillaca.com'); // add to address
$m->Subject('Hello World!'); // set subject
$m->Text('Text message.'); // set text message

// connect to MTA server 'smtp.hostname.net' port '25' with authentication: 'username'/'password'
$c = $m->Connect('smtp.cesarvillaca.com', 25, 'sb@cesarvillaca.com', 'zxcv123') or die(print_r($m->Result));

// send mail relay using the '$c' resource connection
echo $m->Send($c) ? 'Mail sent !' : 'Error !';

$m->Disconnect(); // disconnect from server
?>
<pre>History - <?php print_r($m->History); ?></pre>