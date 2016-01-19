<?php
  require_once("../php_scripts/config.php");
  include "../php_scripts/shortener_processor.php";
 
  $short_url = $_REQUEST['short'];

  try {
    $pdo = new PDO('pgsql:host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_DATABASE.';user='.DB_USERNAME.';password='.DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  } catch(PDOException $e) {
      echo 'ERROR: ' . $e->getMessage();
  }

  $shortener_processor = new shortener_processor($pdo);
  try {
      $url = $shortener_processor->getShortCode($short_url);
      if(!empty($url)) {
        Header("HTTP/1.1 301 Moved Permanently");
        header("Location: " . $url . "");
      } else {
       echo "Cannot find short URL";
      }
  } catch (\Exception $e) {
      echo 'ERROR: ' . $e->getMessage();
      exit;
  }

?>