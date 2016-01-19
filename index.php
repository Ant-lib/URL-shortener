<!doctype html>
<html class="no-js" lang="en">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ant.directory | URL shortener</title>
    <link rel="stylesheet" href="css/foundation.min.css" />
    <link rel="stylesheet" type="text/css" href="css/main.css"/>
  </head>
  <body>

    <?php
      session_start();
      if (isset($_GET['message'])) {
        $key = $_GET["message"];
      } else if(isset($_GET['err_message'])) {
        $key = $_GET["err_message"];
      }
    ?>

    <div class="container">
      <form action="./includes/process_long_link.php" method="post" id="main">
        <input name="url" type="text" class="url" />
        <button class="centered button" name="Send" type="submit">Shorten URL</button>
      </form>
      <div class="message">
        <?php if (isset($_GET['message'])) { echo "<span>SHORT URL: </span>"; } ?>
        <a href="<?php if (isset($_GET['message'])) { echo $_SESSION["shortener_url_link_$key"]; } ?>" target="_blank">
          <?php if (isset($_GET['message'])) { echo $_SESSION["shortener_url_link_$key"]; } ?>
        </a>
        <span id="err"><?php if (isset($_GET['err_message'])) { echo $_SESSION["err_msg_$key"]; } ?></span>
      </div>
    </div>

    <script src="js/vendor/jquery.min.js"></script>
    <script src="js/vendor/what-input.min.js"></script>
    <script src="js/foundation.min.js"></script>
    <script src="js/app.js"></script>
  </body>
</html>
