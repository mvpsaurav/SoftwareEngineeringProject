<?php
    include 'HelperFunctions.php';

    session_start();

    // Make sure that the variables are properly set and that the user is logged
    // in.
    if(isset($_GET['otherCourseCode'])
            && isset($_GET['otherSchoolName'])
            && isset($_GET['localCourseCode'])
            && isset($_GET['isApproved'])
            && isset($_GET['notes'])
            && isset($_SESSION['loggedIn'])
            && $_SESSION['loggedIn'] == true) {
        // Initalize the DB connection.
        $db_host = "dbserver.engr.scu.edu";
        $db_user = "cwalther";
        $db_pass = "plaintextAF";
        $db_name = "sdb_cwalther";
        $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

        // Validate variables.
        $otherCourseCode = $conn->real_escape_string($_GET['otherCourseCode']);
        $otherSchoolName = $conn->real_escape_string($_GET['otherSchoolName']);
        $localCourseCode = $conn->real_escape_string($_GET['localCourseCode']);
        $isApproved = $conn->real_escape_string($_GET['isApproved']);
        $notes = $conn->real_escape_string($_GET['notes']);

        // Insert the new equivalency.
        $sql = "INSERT INTO COEN174CourseEquivalencies
                (otherCourseCode, otherSchool, localCourseCode, isApproved, approvedBy, notes)
                VALUES('"
                . $otherCourseCode . "', '"
                . $otherSchoolName . "', '"
                . $localCourseCode . "', "
                . $isApproved . ", '"
                . $_SESSION['realName'] . "', '"
                . $notes . "')";
        $result = $conn->query($sql);

        // If the new equivalency was added successfully, display a success
        // message.  Otherwise, display an error message.
        if ($result == false) {
            EchoDismissableAlert("There was a problem adding the equivalency.  Make sure you entered your
            values correctly and try again.");
            http_response_code(500);
        } else {
            EchoDismissableSuccess("Equivalency successfully added.");

            // Create a header to indicate that the user was added successfully,
            // so the page that is sending a request to AddNewEquivalency.php
            // has an easy way to know that there was a success.
            header("Success: true");
        }

        $conn->close();
    } else {
        EchoDismissableAlert("There was a problem adding the equivalency.  Make sure you entered your
        values correctly and try again.");
        http_response_code(500);
    }
?>
