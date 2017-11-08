<?php
    include 'HelperFunctions.php';

    session_start();

    // If the submit button was clicked, add a new entry to the table.
    if(isset($_POST['otherCourseCode'])
            && isset($_POST['originalOtherCourseCode'])
            && isset($_POST['otherSchoolName'])
            && isset($_POST['originalOtherSchool'])
            && isset($_POST['localCourseCode'])
            && isset($_POST['originalLocalCourseCode'])
            && isset($_POST['isApproved'])
            && isset($_POST['originalIsApproved'])
            && isset($_POST['originalApprovedBy'])
            && isset($_POST['notes'])
            && isset($_SESSION['loggedIn'])
            && $_SESSION['loggedIn'] == true) {
        // Initalize the DB connection.
        $db_host = "dbserver.engr.scu.edu";
        $db_user = "cwalther";
        $db_pass = "plaintextAF";
        $db_name = "sdb_cwalther";
        $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

        // Validate variables.
        $otherCourseCode = $conn->real_escape_string($_POST['otherCourseCode']);
        $originalOtherCourseCode = $conn->real_escape_string($_POST['originalOtherCourseCode']);
        $otherSchoolName = $conn->real_escape_string($_POST['otherSchoolName']);
        $originalOtherSchool = $conn->real_escape_string($_POST['originalOtherSchool']);
        $localCourseCode = $conn->real_escape_string($_POST['localCourseCode']);
        $originalLocalCourseCode = $conn->real_escape_string($_POST['originalLocalCourseCode']);
        $isApproved = $conn->real_escape_string($_POST['isApproved']);
        $originalIsApproved = $conn->real_escape_string($_POST['originalIsApproved']);
        $notes = $conn->real_escape_string($_POST['notes']);
        $approvedBy = $_SESSION['realName'];
        if ($_SESSION['username'] == "admin") {
            $originalApprovedBy = $conn->real_escape_string($_POST['originalApprovedBy']);
        } else {
            $originalApprovedBy = $_SESSION['realName'];
        }

        // Get approver name from the users table.
        $sql = "UPDATE COEN174CourseEquivalencies "
            . "SET OtherCourseCode = '$otherCourseCode', "
            . "OtherSchool = '$otherSchoolName', "
            . "LocalCourseCode = '$localCourseCode', "
            . "ApprovedBy = '$approvedBy', "
            . "IsApproved = $isApproved, "
            . "Notes = '$notes' "
            . "WHERE OtherCourseCode = '$originalOtherCourseCode' "
            . "AND OtherSchool = '$originalOtherSchool' "
            . "AND LocalCourseCode = '$originalLocalCourseCode' "
            . "AND ApprovedBy = '$originalApprovedBy' "
            . "AND IsApproved = $originalIsApproved";
        if ($conn->query($sql) == false) {
            EchoDismissableAlert("Failed to modify the equivalency.");
            http_response_code(500);
        } else {
            EchoDismissableSuccess("Equivalency successfully modified.");
        }

        $conn->close();
    } else {
        http_response_code(500);
        EchoDismissableAlert("Failed to modify the equivalency.");
    }
?>
