<?php
    session_start();

    // Initalize the DB connection.
    $db_host = "dbserver.engr.scu.edu";
    $db_user = "cwalther";
    $db_pass = "plaintextAF";
    $db_name = "sdb_cwalther";
    $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

    // If the submit button was clicked, add a new entry to the table.
    if(isset($_GET['otherCourseCode'])
            && isset($_GET['otherSchoolName'])
            && isset($_GET['localCourseCode'])
            && isset($_GET['isApproved'])
            && isset($_SESSION['loggedIn'])
            && $_SESSION['loggedIn'] == true) {
        $otherCourseCode = $conn->real_escape_string($_GET['otherCourseCode']);
        $otherSchoolName = $conn->real_escape_string($_GET['otherSchoolName']);
        $localCourseCode = $conn->real_escape_string($_GET['localCourseCode']);
        $isApproved = $conn->real_escape_string($_GET['isApproved']);

        // Get approver name from the users table.
        $sql = "INSERT INTO COEN174CourseEquivalencies
                (otherCourseCode, otherSchool, localCourseCode, isApproved, approvedBy)
                VALUES('"
                . $otherCourseCode . "', '"
                . $otherSchoolName . "', '"
                . $localCourseCode . "', "
                . $isApproved . ", '"
                . $_SESSION['realName'] . "')";
        if ($conn->query($sql) == false) {
            echo '<div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            There was a problem adding the entry.  Make sure you entered your values correctly and try again.
            </div>';
        } else {
            echo '<div class="alert alert-success alert=dismissible">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            Equivalency successfully added.
            </div>';
        }
    }

    $conn->close();
?>
