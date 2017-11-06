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

    $handle = fopen('debug.log', 'w');
    fwrite($handle, $sql);
    $result = $conn->query($sql);
    DisplayResults($result);
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
</html>
