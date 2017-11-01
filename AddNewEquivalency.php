<?php
    include 'HelperFunctions.php';
    
    session_start();

    // If the submit button was clicked, add a new entry to the table.
    if(isset($_GET['otherCourseCode'])
            && isset($_GET['otherSchoolName'])
            && isset($_GET['localCourseCode'])
            && isset($_GET['isApproved'])
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

        // Insert the new equivalency.
        $sql = "INSERT INTO COEN174CourseEquivalencies
                (otherCourseCode, otherSchool, localCourseCode, isApproved, approvedBy)
                VALUES('"
                . $otherCourseCode . "', '"
                . $otherSchoolName . "', '"
                . $localCourseCode . "', "
                . $isApproved . ", '"
                . $_SESSION['realName'] . "')";
        if ($conn->query($sql) == false) {
            EchoDismissableAlert("There was a problem adding the entry.  Make sure you entered your
            values correctly and try again.");
        } else {
            EchoDismissableSuccess("Equivalency successfully added.");

        }

        $conn->close();
    }
?>
