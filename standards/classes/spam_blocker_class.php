<?php 
/*
Copyright (c) 2009, Alec Scaresbrook (www.scaresbrooks.co.uk)
All rights reserved.

Redistribution and use in source and binary forms, with or without modification, are permitted provided that the following conditions are met:

1. Redistributions of source code must retain the above copyright notice, this list of conditions and the following disclaimer.

2. Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the following disclaimer in the documentation and/or other materials provided with the distribution.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
*/

/*
Read readme.txt file about how to use this class.
*/
class spam_blocker {
  //Put your http:BL API key in the single quotes.
	var $apikey = 'cvwhhswkpubh';
  /*
	Set write_data to 'true' to write to files or tables.
	Set write_data to 'false' to stop the process.
	*/
  var $write_data = true;	

	function spam_blocker_reference()
  {
/*
Don't change the values for type; this class won't work if you do. 
Also, it's not necessary to alter the search engine section (type 0).	

Change the activity and threat values (type 1-7) as you see fit from analysing
the data written in your spam_blocker files or tables from the class. Read the latest information about this on the Project Honey Pot website (http://www.projecthoneypot.org).

It's not wise to block all these types of visit because threats from IP addresses change over time, so you could block a genuine user (using a proxy server) as they use an IP address that is slowly being de-listed from the blacklist database.
*/
		
	$this->spam_blocker = array(
  array("datfile" => "search_engine","activity" => 0, "threat" => 0,"type" => 0),
  array("datfile" => "suspicious","activity" => 30, "threat" => 50,"type" => 1),
	array("datfile" => "harvester","activity" => 30, "threat" => 30,"type" => 2),
	array("datfile" => "suspicious_harvester","activity" => 30, "threat" => 25,"type" => 3),
	array("datfile" => "comment_spammer","activity" => 30, "threat" => 20,"type" => 4),
	array("datfile" => "suspicious_comment_spammer","activity" => 30, "threat" => 10,"type" => 5),
	array("datfile" => "harvester_comment_spammer","activity" => 30, "threat" => 10,"type" => 6),
	array("datfile" => "suspicious_harvester_comment_spammer","activity" => 30, "threat" => 10,"type" => 7)					
 );
}

	//Get IP address.
	function getRealIpAddr()
  {
   if (!empty($_SERVER['HTTP_CLIENT_IP']))
   {$this->ip_address=$_SERVER['HTTP_CLIENT_IP'];
	 }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
      //Check IP is passed from a proxy.
      {$this->ip_address=$_SERVER['HTTP_X_FORWARDED_FOR'];
	    }else{
        $this->ip_address=$_SERVER['REMOTE_ADDR'];}
 }	 

function spam_blocker_querybl()
{
 	//Get the the IP address.
  $this->getRealIpAddr();	

/*
127.0.0.1 is the IP address for development on a localhost.
The function dns_get_record() is PHP 5.
You could try function gethostbyname() which is PHP 4, PHP 5, but you would have to change explode( '.', $result[0][ip]); because it dosen't return an associative array.
*/
  if ($this->ip_address != '127.0.0.1')
    {
      // Build a DNS lookup.
      $lookup = $this->apikey . "." . implode('.', array_reverse(explode ('.', $this->ip_address))) . '.dnsbl.httpbl.org';
      $result = dns_get_record($lookup,DNS_A);
	
      $this->return_data = explode( '.', $result[0][ip]);
     }else{
 		  //Test data for use on a localhost.
      $this->return_data[0] = "127";//prefix (127)- always the same if query successful.
      $this->return_data[1] = "10";//Activity - days last seen
      $this->return_data[2] = "50";//Threat level
      $this->return_data[3] = "7";//Type 0 - 7
		}
  }

  function spam_blocker_process_data($folder)
  {
/*
	See if query has data.
	If a value is found in http:bl database, the first value is always 127 
	else allow user access.
*/
  if ($this->return_data[0] == 127) {
    //The query is successful!
		if ($this->return_data[3] == 0)
		{
		 //Search engine - type 0.
		 //Allow user access - set the global var to continue.
     $_SESSION['spam_blocker'] = 'continue';
		 //Create file name.
		 $this->file_or_table_name = "0" . $this->return_data[3] . "_" . $this->spam_blocker[$this->return_data[3]][datfile];	
		 //Write to file.
	   $this->spam_blocker_write_file($folder);
		}else{
		/*
		Process other types 1 - 7.
 		Deal with the activity level.
		*/
		/*
		If the activity value you've set is greater then the one returned from the database, proceed to the next test else allow user access.
		*/
    if ($this->spam_blocker[$this->return_data[3]][activity] > $this->return_data[1])
		{
	    /*
		  If the threat value is greater than the value you've set, block the user
		  else allow user access.
		  */
 		  if ($this->return_data[2] > $this->spam_blocker[$this->return_data[3]][threat])
		    {
 				//Block user - set global var to block.
        $_SESSION['spam_blocker'] = 'block';
        //Create file name.
		    $this->file_or_table_name = "0"	. $this->return_data[3]	. "_blocked_" . $this->spam_blocker[$this->return_data[3]][datfile];	
		    //Write to file.
	      $this->spam_blocker_write_file($folder);
	     }else{
				//Allow user access = set the global var to continue.
        $_SESSION['spam_blocker'] = 'continue';  	
	      //Create file name.
		    $this->file_or_table_name = "0"	. $this->return_data[3]	. "_allowed_" . $this->spam_blocker[$this->return_data[3]][datfile];	
		    //Write to file.
	      $this->spam_blocker_write_file($folder); 
		   }
		}else{
			//Allow user access = set the global var to continue.
      $_SESSION['spam_blocker'] = 'continue';    
      //Create file name.
		  $this->file_or_table_name = "0"	. $this->return_data[3]	. "_allowed_" .        $this->spam_blocker[$this->return_data[3]][datfile];	
		  //Write to file.
	    $this->spam_blocker_write_file($folder); 
		}
	 }
	}else{
      //Allow user access = set the global var to continue.
	    $_SESSION['spam_blocker'] = 'continue';    
      //Create file named 00_visitor.dat
		  $this->file_or_table_name = "00_visitor";	
		  //Write to file.
	    $this->spam_blocker_write_file($folder); 
  }
 }

  /*
  Write the visitor information to a file or a table (See: readme.txt file).
  The value set in the var $folder is passed from 
	the code you place in the web pages that use this class.
	*/
	function spam_blocker_write_file($folder)
  {
	  //If set to 'true' write to files or tables
	  if ($this->write_data){
      //Get date and time.
      $tmestamp = time();
      $datum = date("d-m-Y (D) H:i:s",$tmestamp); 

      //Get the mysql connection data
      include ("spam_blocker_connection.php"); 
      //Make sure that $server, $user and $db have values
      //else write to files instead
        if ($server != "" && $user != "" && $db != "" )
         {
         $link = mysql_connect($server, $user, $password) or die(mysql_error());
           if($link){
             $db_selected = mysql_select_db($db, $link);
              if ($db_selected) {
                $sql = sprintf("CREATE TABLE IF NOT EXISTS %s (
                %s %s( %s ) NULL,
                %s %s( %s ) NULL,
                %s %s( %s ) NULL,
                %s %s( %s ) NULL,
                %s %s( %s ) NULL,
                %s %s( %s ) NULL,
                %s %s( %s ) NULL,
                %s %s( %s ) NULL,
                %s %s( %s ) NULL,
                %s %s( %s ) NULL )",
                $this->file_or_table_name,
                'ip','varchar','20',
                'type','varchar','40',
                'threat','varchar','3',
                'days_last_seen','varchar','3',
                '_REQUEST_METHOD','varchar','100',
                '_REQUEST_URI','varchar','100',
                '_SERVER_PROTOCOL','varchar','100',
                '_HTTP_REFERER','varchar','100',
                '_HTTP_USER_AGENT','varchar','100',
                'Date','varchar','40'
              );

              $result = mysql_query($sql);

              if ($result) 
                { 
                $query = sprintf("INSERT INTO %s (`ip`,`type`,`threat`,`days_last_seen`,`_REQUEST_METHOD`,`_REQUEST_URI`,`_SERVER_PROTOCOL`,`_HTTP_REFERER`,`_HTTP_USER_AGENT`,`Date`) 
	              VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')",
                mysql_real_escape_string($this->file_or_table_name),
                mysql_real_escape_string($this->ip_address),
	              mysql_real_escape_string($this->return_data[3]),
	              mysql_real_escape_string($this->return_data[2]),
                mysql_real_escape_string($this->return_data[1]),
	              mysql_real_escape_string($_SERVER['REQUEST_METHOD']),	 
                mysql_real_escape_string($_SERVER['REQUEST_URI']),
                mysql_real_escape_string($_SERVER['SERVER_PROTOCOL']),
	              mysql_real_escape_string($_SERVER['HTTP_REFERER']),
	              mysql_real_escape_string($_SERVER['HTTP_USER_AGENT']),
	              mysql_real_escape_string($datum));
              //Insert record
	            $result = mysql_query($query);
             } 
         }
      }
   }else{
 	   if ($this->return_data[0] == "127") {
     //Write to iles
	 		//Include the visitor info for those found in the http:bl database.
			$visitor_info = " Type (" . $this->return_data[3] . ") "
	    . " " . $this->spam_blocker[$this->return_data[3]][datfile]
	    . ". Threat: " . $this->return_data[2]
	    . ". Activity " . $this->return_data[1] . " days ago. ";
	   }else{
	    //Just a visitor - there's no information.
	    $visitor_info = " ";
	   }
   
	 $info = $this->ip_address
	 . $visitor_info
	 . $_SERVER['REQUEST_METHOD'] . " "
   . $_SERVER['REQUEST_URI'] . " "
	 . $_SERVER['SERVER_PROTOCOL'] . " "
	 . $_SERVER['HTTP_REFERER'] . " "
	 . $_SERVER['HTTP_USER_AGENT'] . " Date: "
	 . $datum . " \n\r\n\r";

	  //Append to a file.
    $fp = fopen($folder . $this->file_or_table_name . ".dat",'a+');
    fwrite($fp, $info);
    fclose($fp);	 
    }
   }//write_data flag true or false
	}	

//Show the banned visitor a message and then exit.
	function spam_blocker_message_exit()
  {	
   echo "<html><head>";
   echo "<title>This site is unavailable to you, sorry.</title>";
   echo "</head><body>";
   echo "<div style=\"text-align:center\">";
   echo "<h1>Welcome...</h1>";
   echo "<p>Unfortunately, due to abuse, this site is temporarily unavailable ...</p>";
   echo "<p>It seems that your IP address is linked with abuse on the Internet.</p>";
   echo "<p>If you feel this is an error, visit the Project Honey Pot website to find out how to deal with this problem.</p>";
   echo "</div>";
   echo "</body></html>";
   exit;
	}
	
	function spam_blocker_control($folder)
  {
	 	/*
		The $_SESSION['spam_blocker'] var is empty for a first-time visit to the site.
		The following functions decide if the visitor is to be allowed to continue or
		to be blocked from the site.
		*/
		if ($_SESSION['spam_blocker'] == '')
		{
      $this->spam_blocker_querybl();
 		  $this->spam_blocker_reference();
		  $this->spam_blocker_process_data($folder);	
	  }
		if ($_SESSION['spam_blocker'] == 'block')
		{
			$this->spam_blocker_message_exit();
		}
	}
}

$ip = new spam_blocker();

?>