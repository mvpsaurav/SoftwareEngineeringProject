<?php
    include 'HelperFunctions.php';

    session_start();

    // Initalize the DB connection.
    $db_host = "dbserver.engr.scu.edu";
    $db_user = "cwalther";
    $db_pass = "plaintextAF";
    $db_name = "sdb_cwalther";
    $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

    if ($conn != false) {
        // Get as many search terms as are set.
        if (isset($_GET['otherSchoolName'])) {
            $otherSchoolName = $conn->real_escape_string($_GET['otherSchoolName']);
        } else {
            $otherSchoolName = "";
        }
        if (isset($_GET['otherCourseCode'])) {
            $otherCourseCode = $conn->real_escape_string($_GET['otherCourseCode']);
        } else {
            $otherCourseCode = "";
        }
        if (isset($_GET['localCourseCode'])) {
            $localCourseCode = $conn->real_escape_string($_GET['localCourseCode']);
        } else {
            $localCourseCode = "";
        }
        if (isset($_GET['approvedBy'])) {
            $approvedBy = $conn->real_escape_string($_GET['approvedBy']);
        } else {
            $approvedBy = "";
        }

        // Get the results from the table.
        $query = "SELECT * FROM COEN174CourseEquivalencies "
                . "WHERE otherSchool LIKE '%" . $otherSchoolName . "%' "
                . "AND otherCourseCode LIKE '%" . $otherCourseCode . "%' "
                . "AND localCourseCode LIKE '%" . $localCourseCode . "%' "
                . "AND approvedBy LIKE '%" . $approvedBy . "%'";
        $result = $conn->query($query);
        DisplayResults($result);
    } else {
        EchoDismissableAlert('Could not retrieve results.');
    }
?>
