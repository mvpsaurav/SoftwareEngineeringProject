<?php
    include 'HelperFunctions.php';

    session_start();

    if(isset($_POST['username'])
            && isset($_POST['password'])
            && isset($_POST['realName'])
            && isset($_SESSION['loggedIn'])
            && $_SESSION['loggedIn'] == true
            && isset($_SESSION['username'])
            && $_SESSION['username'] == "admin") {
        // Initalize the DB connection.
        $db_host = "dbserver.engr.scu.edu";
        $db_user = "cwalther";
        $db_pass = "plaintextAF";
        $db_name = "sdb_cwalther";
        $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

        // Extract variables from post parameters.
        $username = $conn->real_escape_string($_POST['username']);
        $password = $conn->real_escape_string($_POST['password']);
        $realName = $conn->real_escape_string($_POST['realName']);

        // Do some data validation.
        if (strlen($username) < 1
                || strlen($password) < 1
                || strlen($realName) < 1) {
            echo 'check your inputs';
            header("Location: SoftwareEngineeringProject.php");
        }

        // Determine salt.
        $salt = "";
        for ($i = 0; $i < 64; $i++) {
            $salt .= (string) rand(0, 9);
        }
        $hashedAndSaltedPassword = hash("sha256", $password . $salt);

        // Log in the user, if possible.
        $sql = "INSERT INTO COEN174FacultyUsers "
                . "(Username, HashedPassword, Salt, RealName) "
                . "VALUES('"
                . $username . "', '"
                . $hashedAndSaltedPassword . "', '"
                . $salt . "', '"
                . $realName . "')";
        $result = $conn->query($sql);
        if ($result != false) {
            EchoDismissableSuccess("Successfully added user.");
        } else {
            EchoDismissableAlert("Failed to add user.");
        }

        $conn->close();
        header("Location: SoftwareEngineeringProject.php");
    } else {
        EchoDismissableAlert("Failed to add user.");
    }
?>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="The final project for COEN 174L: Software Engineering Lab.  A group project between Collin Walther and Phi Lam.">
        <meta name="author" content="Collin Walther, Phi Lam">
        <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">

        <title>Course Equivalency Lookup</title>

        <!-- Bootstrap core CSS -->
        <link href="bootstrap.css" rel="stylesheet">
        <!-- Bootstrap theme -->
        <link href="bootstrap-theme.css" rel="stylesheet">
        <!-- JQuery -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <!-- Bootstrap JS -->
        <script src="bootstrap.min.js"></script>
        <!-- Some dumb custom css to make the top of the page look nicer -->
        <style>
            body {
                margin-top: 15px;
            }
            </style>
        </head>

    <body>
        <div class="container-fluid">
            <div class="row">
                <form action="AddFacultyUser.php" method="POST">
                    <div class="form-group">
                        <input type="text" class="form-control" name="username" id="username" placeholder="Username">
                    </div>
                    <div class="form-group">
                        <input type="hidden" class="form-control" name="hashedPassword" id="hashedPassword">
                        <input type="text" class="form-control" name="password" id="password" placeholder="Password">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="realName" id="realName" placeholder="Real Name">
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </body>
    <script>
    hash = function(s){
        return s.split("").reduce(function(a,b){a=((a<<5)-a)+b.charCodeAt(0);return a&a},0);
    }

    $('#password').change(function() {
        $('#hashedPassword').val(hash($('#password').val())).change();
    });
    </script>
</html>
