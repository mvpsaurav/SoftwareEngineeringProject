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
                <div class="col-md-12">
                    <div class="well">
<?php

    include 'HelperFunctions.php';

    session_start();

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
    $approvedBy = $conn->real_escape_string($_GET['approvedBy']);

    $sql = "SELECT * FROM COEN174CourseEquivalencies "
        . "WHERE OtherCourseCode = '$otherCourseCode' "
        . "AND OtherSchool = '$otherSchoolName' "
        . "AND LocalCourseCode = '$localCourseCode' "
        . "AND IsApproved = '$isApproved' "
        . "AND ApprovedBy = '$approvedBy'";

    $result = $conn->query($sql);
    if (isset($_SESSION['loggedIn'])
            && $_SESSION['loggedIn'] == true) {
        DisplayResults($result, true, true);
    } else {
        DisplayResults($result, false, true);
    }
?>
                    </div>
                    <div class="well">

                    </div>
                </div>
            </div>
        </div>
    </body>

    <script>

    /**
    * Deletes an equivalency.
    */
    deleteEquivalency = function(occ, os, lcc, ia, ab) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == XMLHttpRequest.DONE && this.status == 200) {
                window.location = "SoftwareEngineeringProject.php";
            }
        }
        xhttp.open("GET", "RemoveEquivalency.php?otherCourseCode=" + occ
            + "&otherSchoolName=" + os
            + "&localCourseCode=" + lcc
            + "&isApproved=" + ia
            + "&approvedBy=" + ab);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send();
        console.log("otherCourseCode=" + occ
            + "&otherSchoolName=" + os
            + "&localCourseCode=" + lcc
            + "&isApproved=" + ia
            + "&approvedBy=" + ab);
    }

    </script>
</html>
