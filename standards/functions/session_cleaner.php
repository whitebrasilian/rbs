<?
/*
Sitewide Session Garbage Collecton

USAGE:
1. Include this file.
2. Call the function with the name of the directory to be 'cleaned'
   -> NO TRAILING SLASH!!!
include($CFG->baseroot/standards/functions/session_cleaner.php");
cleanSessionFolder("/foldername/standards/sessions");
*/

function cleanSessionFolder($Path, $Debug=0) {
	global $DOCUMENT_ROOT;
	$FullPath = $Path;
	$files = array();
	$dir = opendir($FullPath);
	while($file = readdir($dir)) {
		if(strstr($file, "sess_")) {
			array_push($files, $file);
		}
	}
	$x = 0;
	$i = 0;
	$DebugMessage = "<b>Session Directory: $Path</b><br>";
	foreach($files as $key=>$file) {
		$i++;
		$Age = date('U') - filemtime($FullPath."/".$file);
		$DebugMessage .=  "File: ".$file."<br>";
		$DebugMessage .=  "Age(in Seconds): ".$Age."<br><br>";
		if($Age > 43200) { //12hr
			unlink($FullPath.'/'.$file);

			/*if(unlink($FullPath.'/'.$file)){
				echo"unlinked $FullPath/$file<p>";
				$x++;
			}else{
				echo"linked $FullPath/$file<p>";
			}*/
		}
	}
	clearstatcache();
	$DebugMessage .= "Session files found: $i<br>";
	$DebugMessage .= "Session files removed: $x<hr>";
	if($Debug==1) {
		echo $DebugMessage;
	}

}


/*
New and Improved Script (No reliance on document root)
USAGE:
1. Include this file.
2. Call the function with the name of the directory to be 'cleaned'
   -> NO TRAILING SLASH!!!
include($CFG->baseroot/standards/functions/session_cleaner.php");
cleanSessionFolder($DOCUMENT_ROOT."/foldername/standards/sessions");
*/
function ClearOldSessions($FullPath, $Debug=0) {
	$files = array();
	$dir = opendir($FullPath);
	while($file = readdir($dir)) {
		if(strstr($file, "sess_")) {
			array_push($files, $file);
		}
	}
	$x = 0;
	$i = 0;
	$DebugMessage = "<b>Session Directory: $FullPath</b><br>";
	foreach($files as $key=>$file) {
		$i++;
		$Age = date('U') - filemtime($FullPath."/".$file);
		$DebugMessage .=  "File: ".$file."<br>";
		$DebugMessage .=  "Age(in Seconds): ".$Age."<br><br>";
		if($Age > 43200) { //12hr
			unlink($FullPath.'/'.$file);
			$x++;
		}
	}
	clearstatcache();
	$DebugMessage .= "Session files found: $i<br>";
	$DebugMessage .= "Session files removed: $x<hr>";
	if($Debug==1) {
		echo $DebugMessage;
	}
}
?>