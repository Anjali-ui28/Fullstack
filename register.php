<?php
session_start();

if (isset($_SESSION["username"])) {
    header("location: ../welcome.php");
    exit();
}
require_once "configs/config.php";
$username = $password = $confirm_password = "";
$error = "";
$succes = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // check if username is empty

    if (empty(trim($_POST["username"]))) {
        $error = "Username cannot be blank";
    } elseif (empty(trim($_POST["password"]))) {
        $error = "Password cannot be blank";
    } elseif (empty(trim($_POST["confirm_password"]))) {
        $error = "Confirm Password cannot be blank";
    }
    // check if the file is empty or not
    elseif (empty($_FILES["pdf_file"]["name"])) {
        $error = "Please upload your CV in PDF format";
    } else {
        // check the file is pdf or not

        $file_type = $_FILES["pdf_file"]["type"];

        // check the file extension

        $file_extension = strtolower(
            pathinfo($_FILES["pdf_file"]["name"], PATHINFO_EXTENSION)
        );

        // check the file is pdf or not

        if ($file_extension != "pdf") {
            $error = "Please upload your CV in PDF format";
        } else {
            $username = trim($_POST["username"]);
            $password = trim($_POST["password"]);
            $confirm_password = trim($_POST["confirm_password"]);
            // checking the username is already in the database or not
            $sql = "SELECT * FROM users WHERE username = '$username'";
            $result = mysqli_query($conn, $sql);

            if ($result) {
                if (mysqli_num_rows($result) > 0) {
                    $error = "Username is already taken";
                } else {
                    if ($password == $confirm_password) {
                        //
                        // upload the files in uploads folder with time stamp

                        $file_name = time() . "_" . $_FILES["pdf_file"]["name"];
                        $destination = "../uploads/" . $file_name;
                        $file = $_FILES["pdf_file"]["tmp_name"];
                        $size = $_FILES["pdf_file"]["size"];

                        if (move_uploaded_file($file, $destination)) {
                            $password = password_hash($password, PASSWORD_DEFAULT);

                            $sql = "INSERT INTO `users` (`username`, `password`, `cv`) VALUES ('$username', '$password', '$file_name')";
                            $result = mysqli_query($conn, $sql);
                            if ($result) {
                                $succes =
                                    "Your account has been created successfully";
                            } else {
                                $error =
                                    "Something went wrong..! Please try again later";
                            }
                        } else {
                            $error = "Failed to upload file";
                        }

                        // hash the password before saving in the database
                    } else {
                        $error = "Password did not match";
                    }
                }
            } else {
                $error = "Something went wrong..! Please try again later";
            }
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

    <title>PHP Register system!</title>
</head>

<body>

    <!-- including navbar -->

    <?php include "./layouts/navbar.php"; ?>

    <div class="container mt-4">
        <h3>Please Register Here:</h3>
        <hr>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="inputEmail4">Username</label>
                    <input type="text" class="form-control" name="username" id="inputEmail4" placeholder="Email">
                </div>
                <div class="form-group col-md-6">
                    <label for="inputPassword4">Password</label>
                    <input type="password" class="form-control" name="password" id="inputPassword4"
                        placeholder="Password">
                </div>
            </div>
            <div class="form-group">
                <label for="inputPassword4">Confirm Password</label>
                <input type="password" class="form-control" name="confirm_password" id="inputPassword"
                    placeholder="Confirm Password">
            </div>
            <div class="form-group">
                <label for="pdf_file">Select PDF File:</label>
                <input type="file" class="form-control-file" id="pdf_file" name="pdf_file">
            </div>
            <?php
            if (isset($error) && $error !== "") {
                echo '<div class="alert alert-danger" role="alert">' .
                    $error .
                    "</div>";
            }
            if (isset($succes) && $succes !== "") {
                echo '<div class="alert alert-success" role="alert">' .
                    $succes .
                    "</div>";
            }
            ?>


            <button type="submit" class="btn btn-primary">Register</button>
        </form>
    </div>



    <?php require "./layouts/footer.php"; ?>

</body>

</html>