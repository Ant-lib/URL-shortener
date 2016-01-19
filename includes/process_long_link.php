<?php
  session_start();
  include "../includes/db.class.php";
  include "../includes/shortener_processor.php";
   
  $url = $_REQUEST['url'];
  $key = mt_rand(10000, 99999);
   
  if(!preg_match("%^((https?://)|(https?://)|(www\.))([a-z0-9-].?)+(:[0-9]+)?(/.*)?$%i", $url)) {
    $_SESSION["err_msg_$key"] = "URL is not valid. Please try again.";
    header ("Location: http://ant.directory/URL-shortener/index.php?err_message=$key");
  } else {

    $dbconn = new DB();
    $shortener_processor = new shortener_processor($dbconn);

    try {
        $short_url = $shortener_processor->saveShortCode($url);
        $_SESSION["shortener_url_link_$key"] = "http://ant.directory/" . $short_url;
        header ("Location: http://ant.directory/URL-shortener/index.php?message=$key");
        exit;
    } catch (\Exception $e) {
        $_SESSION["err_msg_$key"] = "ERROR: " . $e->getMessage();
        header ("Location: http://ant.directory/URL-shortener/index.php?err_message=$key");
        exit;
    }

  }
?>