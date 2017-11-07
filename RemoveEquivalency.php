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

        // Get approver name from the users table.
        $sql = "DELETE FROM COEN174CourseEquivalencies "
            . "WHERE OtherCourseCode = '$otherCourseCode' "
            . "AND OtherSchool = '$otherSchoolName' "
            . "AND localCourseCode = '$localCourseCode' "
            . "AND isApproved = $isApproved "
            . "AND approvedBy = '" . $_SESSION['realName'] . "'";
        echo $sql;
        if ($conn->query($sql) == false) {
            EchoDismissableAlert("There was a problem removing the entry.  Make sure you entered your
            values correctly and try again.");
            http_response_code(500);
        } else {
            EchoDismissableSuccess("Equivalency successfully removed.");
        }

        $conn->close();
    } else {
        http_response_code(501);
        echo 'other course code set: ' . (string) isset($_GET['otherCourseCode']) . "\n";
        echo 'other school name set: ' . isset($_GET['otherSchoolName']) . "\n";
        echo 'local course code set: ' . isset($_GET['localCourseCode']) . "\n";
        echo 'is approved set: ' . isset($_GET['isApproved']) . "\n";
        echo 'is logged in set: ' . isset($_SESSION['loggedIn']) . "\n";
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
                <form action="RemoveEquivalency.php" method="GET">
                    <div class="form-group">
                        <input type="text" class="form-control" name="otherCourseCode" placeholder="Other Course Code">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="otherSchoolName" placeholder="Other School Name">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" name="localCourseCode" placeholder="Local Course Code">
                    </div>
                    <input id="isApproved" name="isApproved" type="hidden">
                    <div class="dropdown">
                        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" id="isApprovedDropdown" data-toggle="dropdown">
                            Select a value
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a onclick="$('#isApprovedDropdown').html('Yes<span class=\'caret\'></span>');$('#isApproved').val(1).change();">Yes</a></li>
                            <li><a onclick="$('#isApprovedDropdown').html('No<span class=\'caret\'></span>');$('#isApproved').val(0).change();">No</a></li>
                        </ul>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </body>
</html>
