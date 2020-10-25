<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
  </head>
<body>
  <div>
    <?php
      if (extension_loaded('gd') && function_exists('gd_info')) {
        echo "PHP GD library is installed on your web server";
      }
      else {
        echo "PHP GD library is NOT installed on your web server";
      }
    ?>
  </div>
  <br>
  <div>
    <img src="images/logo-ucss.png" alt="">
  </div>
  <div>
    <?php
      $imagen = new Imagick('images/logo-ucss.png');
      echo $imagen;
    ?>
  </div>
</body>
</html>