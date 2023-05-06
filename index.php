<?php
// starting the session
session_start();

// connecting to the database

require_once "configs/config.php";

// if the user is already logged in then redirect him to index page

if (isset($_SESSION['username'])) {
  header("location: welcome.php");
  exit;
}

$error = "";
$success = "";


if ($_SERVER["REQUEST_METHOD"] == "POST") {

  // Check if username is empty
  if (empty(trim($_POST["username"]))) {
    $error = "Please enter username.";
  } else {
    $username = trim($_POST["username"]);
  }
  // Check if password is empty
  if (empty(trim($_POST["password"]))) {
    $password_err = "Please enter your password.";
  } else {
    $password = trim($_POST["password"]);
  }


  if (empty($error)) {

    // loggin the user in

    $sql = "SELECT id, username, password FROM users WHERE username = ?";

    $statement = "SELECT * FROM users WHERE username = '$username'";

    $result = mysqli_query($conn, $statement);


    // check if the user exists or not
    if ($result) {
      if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        if (password_verify($password, $row['password'])) {
          session_start();
          $_SESSION["username"] = $username;
          $_SESSION["id"] = $row['id'];
          $_SESSION["loggedin"] = true;

          // redirect user to welcome page

          header("location: welcome.php");
        } else {
          $error = "Invalid password";
        }
      } else {
        $error = "Invalid username";
      }
    } else {
      $error = "Oops! Something went wrong. Please try again later.";
    }
  }
}

?>



<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

  <title>PHP login system!</title>
</head>

<body>


  <?php include 'layouts/navbar.php' ?>

  <div class="container mt-4">
    <h3>Please Login Here:</h3>
    <hr>
    <form action="" method="post">
      <div class="form-group">
        <label for="exampleInputEmail1">Username</label>
        <input type="text" name="username" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp"
          placeholder="Enter Username">
      </div>
      <div class="form-group">
        <label for="exampleInputPassword1">Password</label>
        <input type="password" name="password" class="form-control" id="exampleInputPassword1"
          placeholder="Enter Password">
      </div>
      <div class="form-group form-check">
        <input type="checkbox" class="form-check-input" id="exampleCheck1">
        <label class="form-check-label" for="exampleCheck1">Check me out</label>
      </div>
      <?php

      if (isset($error) && $error !== "") {
        echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
      }
      if (isset($succes) && $succes !== "") {
        echo '<div class="alert alert-success" role="alert">' . $succes . '</div>';
      }
      ?>


      <!-- Place for resume
    <div class="form-check">
      <input class="form-check-input" type="checkbox" id="gridCheck">
      <label class="form-check-label" for="gridCheck">
        Check me out
      </label>
    </div>-->

      <button type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>


  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
    integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
    crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
    integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
    crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
    integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
    crossorigin="anonymous"></script>
</body>

</html>