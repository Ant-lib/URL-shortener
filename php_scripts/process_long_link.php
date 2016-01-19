<?php
  require_once("../php_scripts/config.php");
  include "./shortener_processor.php";
   
  $url = $_REQUEST['url'];
   
  if(!preg_match("%^((https?://)|(https?://)|(www\.))([a-z0-9-].?)+(:[0-9]+)?(/.*)?$%i", $url)) {
    echo "url is not valid";
  } else {
    try {
      $pdo = new PDO('pgsql:host='.DB_HOST.';port='.DB_PORT.';dbname='.DB_DATABASE.';user='.DB_USERNAME.';password='.DB_PASSWORD);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch(PDOException $e) {
        echo 'ERROR: ' . $e->getMessage();
        exit;
    }

    $shortener_processor = new shortener_processor($pdo);
    try {
        $short_url = $shortener_processor->saveShortCode($url);
        echo "http://ant.directory/URL-shortener/php_scripts/process_short_link.php?short=" . $short_url;
        exit;
    } catch (\Exception $e) {
        echo 'ERROR: ' . $e->getMessage();
        exit;
    }
  }
?>