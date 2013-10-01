<?
// 8/27/03 - pp - changed from pconnect connection
// 2/6/04 - pp - Added db_ez_insert() and db_ez_update()

if(!isset($DB_DIE_ON_FAIL)) $DB_DIE_ON_FAIL = true;
if(!isset($DB_DEBUG)) $DB_DEBUG = false;

function db_connect($dbhost, $dbname, $dbuser, $dbpass) {
	global $DB_DIE_ON_FAIL, $DB_DEBUG;
	if(!$dbh = mysql_connect($dbhost, $dbuser, $dbpass)) {
		if($DB_DEBUG) {
			echo "<h2>Can't connect to $dbhost as $dbuser</h2>";
			echo "<p><b>MySQL Error</b>: ", mysql_error();
		} else {
			echo "<h2>Database error encountered</h2>";
		}
		if($DB_DIE_ON_FAIL) {
			echo "<p>This script cannot continue, terminating.";
			die();
		}
	}
	if(! mysql_select_db($dbname)) {
		if($DB_DEBUG) {
			echo "<h2>Can't select database $dbname</h2>";
			echo "<p><b>MySQL Error</b>: ", mysql_error();
		} else {
			echo "<h2>Database error encountered</h2>";
		}
		if ($DB_DIE_ON_FAIL) {
			echo "<p>This script cannot continue, terminating.";
			die();
		}
	}
	return $dbh;
}

function db_disconnect() {
	mysql_close();
}

function db_query($query, $debug=false, $die_on_debug=true, $silent=false) {

	global $DB_DIE_ON_FAIL, $DB_DEBUG;
	if($debug) {
		echo "<pre>" . $query . "</pre>";
		if($die_on_debug) die;
	}
	
	$qid = mysql_query($query);

	if(!$qid && ! $silent) {
		if($DB_DEBUG) {
			echo "<h2>Can't execute query</h2>";
			echo "<pre>" . htmlspecialchars($query) . "</pre>";
			echo "<p><b>MySQL Error</b>: ", mysql_error();
		} else {
			echo "<h2>Database error encountered</h2>";
		}
		if($DB_DIE_ON_FAIL) {
			echo "<p>This script cannot continue, terminating.";
			die();
		}
	}
	return $qid;
}

function db_fetch_array($qid) {
	return mysql_fetch_array($qid);
}

function db_fetch_assoc($qid) {
	return mysql_fetch_assoc($qid);
}

function db_fetch_row($qid) {
	return mysql_fetch_row($qid);
}

function db_fetch_object($qid) {
	return mysql_fetch_object($qid);
}

function db_num_rows($qid) {
	return @mysql_num_rows($qid);
}

function db_affected_rows() {
	return mysql_affected_rows();
}

function db_insert_id() {
	return mysql_insert_id();
}

function db_free_result($qid) {
	mysql_free_result($qid);
}

function db_num_fields($qid) {
	return mysql_num_fields($qid);
}

function db_field_name($qid, $fieldno) {
	return mysql_field_name($qid, $fieldno);
}

function db_data_seek($qid, $row) {
	if(db_num_rows($qid)) return mysql_data_seek($qid, $row);
}

function db_query_loop($query, $prefix, $suffix, $found_str, $default="") {
	$output = "";
	$result = db_query($query);
	while(list($val, $label) = db_fetch_row($result)) {
		if(is_array($default))
			$selected = empty($default[$val]) ? "" : $found_str;
		else
			$selected = $val == $default ? $found_str : "";
		$output .= "$prefix value='$val' $selected>$label$suffix";
	}
	return $output;
}

function db_listbox($query, $default="", $suffix="\n") {
	return db_query_loop($query, "<option", $suffix, "selected", $default);
}

function strip_querystring($url) {
	if($commapos = strpos($url, '?')) {
		return substr($url, 0, $commapos);
	} else {
		return $url;
	}
}

// Dynamically add values to a MYSQL database table using the $_POST vars
function db_ez_insert($tbl)
{
	// Set the arrays we'll need
	$sql_columns = array();
	$sql_columns_use = array();
	$sql_value_use = array();
	
	// Pull the column names from the table $tbl and put them into a non-associative array
	$pull_cols = db_query("SHOW COLUMNS FROM $tbl");
	while($columns = db_fetch_assoc($pull_cols)) {
		$sql_columns[] = $columns[Field];
	}
	foreach($_POST as $key => $value) {
		// Check to see if the variables match up with the column names
		if(in_array($key, $sql_columns) && trim($value)) {
			// If this variable contains the string "DATESTAMP" then use MYSQL function $date 
			if ($value == "DATESTAMP") {
				$sql_value_use[] = "$date";
			} else {
				// If variable contains a number, don't add single quotes, 
				// otherwise check get_magic_quotes_gpc() and use addslashes if it isn't on
				if (is_numeric($value)) {
					$sql_value_use[] = $value;
				} else {
					$sql_value_use[] = (get_magic_quotes_gpc()) ? "'".$value."'" : "'".addslashes($value)."'";
				}
			}
			// Put the column name into the array
			$sql_columns_use[] = $key;
		}
	}
				
	// If $sql_columns_use or $sql_value_use are empty then that means no values matched
	if ((sizeof($sql_columns_use) == 0) || (sizeof($sql_value_use) == 0)) {
		return false;
	} else {
		// Implode $sql_columns_use and $sql_value_use into an SQL insert sqlstatement
		$SQLStatement = "INSERT INTO ".$tbl." (".implode(",",$sql_columns_use).") VALUES (".implode(",",$sql_value_use).")";
			
		// Execute the newly created statement
		db_query($SQLStatement);
	}
}


// Dynamically update values in a MYSQL database table using the $_POST vars
function db_ez_update($tbl, $id, $id_name)
{
	// Set the arrays we'll need
	$sql_columns = array();
	$sql_value_use = array();
		
	// Pull the column names from the table $tbl and put them into a non-associative array
	$pull_cols = db_query("SHOW COLUMNS FROM $tbl");
	while($columns = db_fetch_assoc($pull_cols)) {
		$sql_columns[] = $columns[Field];
	}
	foreach($_POST as $key => $value) {
		// Check to see if the variables match up with the column names
		if(in_array($key, $sql_columns) && isset($value)) {
			// If this variable contains the string "DATESTAMP" then use MYSQL function $date 
			if ($value == "DATESTAMP") {
				$sql_value_use[] = $key . "=$date";
			} else {
				// If variable contains a number, don't add single quotes, 
				// otherwise check get_magic_quotes_gpc() and use addslashes if it isn't on
				if (is_numeric($value)) {
					$sql_value_use[] = $key."=".$value;
				} else {
					$sql_value_use[] = (get_magic_quotes_gpc()) ? $key."='".$value."'" : $key."='".addslashes($value)."'";
				}
			}
		}
	}
		
	// If $sql_value_use is empty then that means no values matched
	if (sizeof($sql_value_use) == 0) {
		return false;
	} else {
		// Implode $sql_value_use into an SQL insert sqlstatement
		$SQLStatement = "UPDATE ".$tbl." SET ".implode(",",$sql_value_use)." WHERE ".$id_name."=".$id;
		db_query($SQLStatement);
	}
}
?>