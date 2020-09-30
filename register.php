<!DOCTYPE html>
<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "social");

if(mysqli_connect_errno()){
  echo "failed to connect: " . mysqli_connect_errno();
}
// Declaring variables to prevent errors

$fname = "";//First name
$lname = "";// Last Name
$em = ""; //Email
$em2 = "";// Email Confirmation
$password = ""; //Password
$password2 = "";//Confirm Password
$date = "";//Sign up date
$error_array = array();//Holds error messages

if(isset($_POST['register_button'])){

  //Registration for values

  $fname = strip_tags($_POST['reg_fname']);//Remove anything next to the name
  $fname = str_replace(" ","",$fname); //Remove spaces
  $fname = ucfirst(strtolower($fname)); //Capitalized the first letter
  $_SESSION['reg_fname']=$fname;

  $lname = strip_tags($_POST['reg_lname']);//Remove anything next to the name
  $lname = str_replace(" ","",$lname); //Remove spaces
  $lname = ucfirst(strtolower($lname)); //Capitalized the first letter
  $_SESSION['reg_lname']=$lname;

  $em = strip_tags($_POST['reg_email']);//Remove anything next to the name
  $em = str_replace(" ","",$em); //Remove spaces
   //Capitalized the first letter
  $_SESSION['reg_email']=$em;

  $em2 = strip_tags($_POST['reg_email2']);//Remove anything next to the name
  $em2 = str_replace(" ","",$em2); //Remove spaces
   //Capitalized the first letter
  $_SESSION['reg_email2']=$em2;

  $password = strip_tags($_POST['reg_password']);//Remove anything next to the name

  $password2 = strip_tags($_POST['reg_password2']);//Remove anything next to the name

  $date = date("Y-m-d"); //This gets the current date

  if($em == $em2){
    if(filter_var($em,FILTER_VALIDATE_EMAIL)){
      $em = filter_var($em,FILTER_VALIDATE_EMAIL);

      //Check if email already exists

      $e_check = mysqli_query($con,"SELECT email FROM USERS WHERE email='$em'");

      $num_rows = mysqli_num_rows($e_check);
      if($num_rows>=1){
        array_push($error_array, "Email already exists<br>");
      }else{

      }


    }else{
      array_push($error_array, "Email format is incorrect<br>");
    }
  }else{
    array_push($error_array, "Emails dont match<br>");
  }

  if(strlen($fname)>35 || strlen($fname)<2){
    array_push($error_array, "Your first name must be between 2 and 35 characters<br>");
  }

  if(strlen($lname)>45 || strlen($lname)<2){
    array_push($error_array, "Your last name must be between 2 and 45 characters<br>");
  }

  if($password != $password2){
    array_push($error_array, "Your passwords do not match<br>");
  }else{
    if(preg_match('/[^A-Za-z0-9]/',$password)){
      array_push($error_array, "Your password only takes letter or numbers<br>");
    }
  }

  if(strlen($password)>30 || strlen($password)<5){
    array_push($error_array, "Your password must be between 5 and 30 characters<br>");
  }

  if(empty($error_array)){
    $password = md5($password); //This encripts the password
    $username = strtolower(substr($fname,0,1)) . strtolower($lname);

    $u_check = mysqli_query($con,"SELECT * FROM USERS WHERE user_name = '$username'");

    $i=0;

    while(mysqli_num_rows($u_check)!=0){
      $i++;
      $username = $username . $i;
      $u_check = mysqli_query($con,"SELECT * FROM USERS WHERE user_name = '$username'");
    }

    $rand = rand(1,2); // Creating random number to select the profile picture

    if($rand == 1){
      $profile_pic = "assets/images/profile_pics/defaults/profile_1.jpg";
    }else{
      $profile_pic = "assets/images/profile_pics/defaults/profile_2.jpg";
    }

    $query = mysqli_query($con,"INSERT INTO users VALUES ('','$fname','$lname','$username','$em','$password','$date','$profile_pic','0','0','no',',')");

    array_push($error_array,"<span style='color:#14C800'>You are all set! Go ahead and login!</span>");

    $_SESSION['reg_fname'] = "";
    $_SESSION['reg_lname'] ="";
    $_SESSION['reg_email'] ="";
    $_SESSION['reg_email2']= "";
  }


}

?>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Welcome to TheSocialNetwork</title>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Pacifico&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="assets/css/register_style.css">
  </head>
  <body>
    <div class="register_box">
      <div class="header_box">
        <h1>TheFacebook</h1>
        Register your information
      </div>
      <form class="" action="register.php" method="post">
        <input type="text" name="reg_fname" placeholder="First Name" value="<?php
          if(isset($_SESSION['reg_fname'])){
            echo $_SESSION['reg_fname'];
          };
        ?>" required>
        <?php if(in_array("Your first name must be between 2 and 35 characters<br>",$error_array)) echo "Your first name must be between 2 and 35 characters"?>
        <br>
        <input type="text" name="reg_lname" placeholder="Last Name" value="<?php
          if(isset($_SESSION['reg_lname'])){
            echo $_SESSION['reg_lname'];
          }
        ?>" required>
        <?php if(in_array("Your last name must be between 2 and 45 characters<br>",$error_array)) echo "Your last name must be between 2 and 45 characters"?>
        <br>
        <input type="email" name="reg_email" placeholder="Email" value="<?php
          if(isset($_SESSION['reg_email'])){
            echo $_SESSION['reg_email'];
          }
        ?>" required>
        <br>
        <input type="email" name="reg_email2" placeholder="Confirm Email" value="<?php
          if(isset($_SESSION['reg_email2'])){
            echo $_SESSION['reg_email2'];
          }
        ?>" required>
        <?php if(in_array("Email already exists<br>",$error_array)) {echo "Email already exists";}
        elseif(in_array("Email format is incorrect<br>",$error_array)) echo "Email format in incorrect";
        elseif(in_array("Emails dont match<br>",$error_array)) echo "Emails dont match";?>
        <br>
        <input type="password" name="reg_password" placeholder="Password" value="" required>
        <br>
        <input type="password" name="reg_password2" placeholder="Confirm Password" value="" required>
        <?php if(in_array("Your passwords do not match<br>",$error_array)) {echo "Your passwords do not match";}
        elseif(in_array("Your password only takes letter or numbers<br>",$error_array)) echo "Your password only takes letter or numbers";
        elseif(in_array("Your password must be between 5 and 30 characters<br>",$error_array)) echo "Your password must be between 5 and 30 characters";?>
        <br>
        <input type="submit" class="btn btn-lg btn-danger" name="register_button" value="Register">
        <?php
        if(in_array("<span style='color:#14C800'>You are all set! Go ahead and login!</span>",$error_array)) echo "<br><span style='color:#14C800'>You are all set! Go ahead and login!</span>"
        ?>
      </form>
    </div>

  </body>
</html>
