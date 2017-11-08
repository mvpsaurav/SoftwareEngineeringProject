<?php
    include "HelperFunctions.php";

    if(isset($_POST['username'])
            && isset($_POST['password'])) {
        // Initalize the DB connection.
        $db_host = "dbserver.engr.scu.edu";
        $db_user = "cwalther";
        $db_pass = "plaintextAF";
        $db_name = "sdb_cwalther";
        $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

        // Extract variables from post parameters.
        $username = $conn->real_escape_string($_POST['username']);
        $password = $conn->real_escape_string($_POST['password']);

        // Log in the user, if possible.
        $sql = "SELECT * FROM COEN174FacultyUsers WHERE username = '" . $username . "'";
        $result = $conn->query($sql);
        if ($result != false && ($row = $result->fetch_assoc()) != null) {
            $hashedAndSaltedPassword = hash("sha256", $password . $row['Salt']);
            if ($hashedAndSaltedPassword == $row['HashedPassword']) {
                session_start();
                $_SESSION["loggedIn"] = true;
                $_SESSION["username"] = $row['Username'];
                $_SESSION["realName"] = $row['RealName'];
                header("LoggedIn: true");
                EchoDismissableSuccess("Logging in...");
            } else {
                header("LoggedIn: false");
                EchoDismissableAlert("Password is invalid.");
            }
        } else {
            header("LoggedIn: false");
            EchoDismissableAlert("Username is invalid.");
        }

        $conn->close();
    } else {
        EchoDismissableAlert("Failed to log in.");
    }
?>
