<?php
    include 'HelperFunctions.php';

    session_start();

    // Make sure the user is logged in as the administrator.
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
            EchoDismissableAlert("Failed to add user.");
        }

        // Determine salt.
        $salt = "";
        for ($i = 0; $i < 64; $i++) {
            $salt .= (string) rand(0, 9);
        }
        $hashedAndSaltedPassword = hash("sha256", $password . $salt);

        // Add the new user to the users table.
        $sql = "INSERT INTO COEN174FacultyUsers "
                . "(Username, HashedPassword, Salt, RealName) "
                . "VALUES('"
                . $username . "', '"
                . $hashedAndSaltedPassword . "', '"
                . $salt . "', '"
                . $realName . "')";
        $result = $conn->query($sql);

        // If the new user is added successfully, then display a success
        // message.  Otherwise, display an error message.
        if ($result != false) {
            EchoDismissableSuccess("Successfully added user.");
        } else {
            EchoDismissableAlert("Failed to add user.");
        }

        // Close the db connection and redirect to the home page.
        $conn->close();
    } else {
        EchoDismissableAlert("Failed to add user.");
    }
?>
