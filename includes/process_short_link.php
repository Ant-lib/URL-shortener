<?php
  session_start();
  include "../includes/db.class.php";
  include "../includes/shortener_processor.php";
 
  $short_url = $_REQUEST['short'];
  $key = mt_rand(10000, 99999);

  $dbconn = new DB();
  $shortener_processor = new shortener_processor($dbconn);

  try {
      $url = $shortener_processor->getShortCode($short_url);

      if(!empty($url)) {
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: http://" . preg_replace("(^https?://)", "", $url ) . "");
      } else {
        throw new \Exception("Cannot find short URL.");
      }
  } catch (\Exception $e) {
      $_SESSION["err_msg_$key"] = "ERROR: " . $e->getMessage();
      header ("Location: http://ant.directory/URL-shortener/index.php?err_message=$key");
      exit;
  }

?>