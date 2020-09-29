<!DOCTYPE html>
<?php
  $con = mysqli_connect("localhost", "root", "", "social");

  if(mysqli_connect_errno()){
    echo "failed to connect: " . mysqli_connect_errno();
  }

  
?>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>TheSocialNetwork</title>
  </head>
  <body>
    <h1>Hello Maiky</h1>
  </body>
</html>
