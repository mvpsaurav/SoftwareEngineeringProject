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
    if (isset($_GET['otherCourseCode'])
            && isset($_GET['otherSchoolName'])
            && isset($_GET['localCourseCode'])
            && isset($_GET['isApproved'])
            && isset($_GET['approvedBy'])) {
        $otherCourseCode = $conn->real_escape_string($_GET['otherCourseCode']);
        $otherSchoolName = $conn->real_escape_string($_GET['otherSchoolName']);
        $localCourseCode = $conn->real_escape_string($_GET['localCourseCode']);
        $isApproved = $conn->real_escape_string($_GET['isApproved']);
        $approvedBy = $conn->real_escape_string($_GET['approvedBy']);

        $sql = "SELECT * FROM COEN174CourseEquivalencies "
            . "WHERE OtherCourseCode = '$otherCourseCode' "
            . "AND OtherSchool = '$otherSchoolName' "
            . "AND LocalCourseCode = '$localCourseCode' "
            . "AND IsApproved = $isApproved "
            . "AND ApprovedBy = '$approvedBy'";

        $result = $conn->query($sql);
        if (isset($_SESSION['loggedIn'])
                && $_SESSION['loggedIn'] == true) {
            DisplayResults($result, true, true);
        } else {
            DisplayResults($result, false, true);
        }
    }
?>
                    </div>
<?php
    if (isset($_SESSION['loggedIn'])
            && $_SESSION['loggedIn'] == true) {
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        if ($_SESSION['realName'] == $row['ApprovedBy']
                || $_SESSION['username'] == "admin") {
            echo '
            <div class="well">
                <h2>Modify this equivalency:</h2>
                <p>Other school\'s course code: <input type="text" name="otherCourseCode" id="otherCourseCode" class="form-control" value="' . $row['OtherCourseCode'] . '"></p>
                <p>Other school\'s name: <input type="text" name="otherSchoolName" id="otherSchoolName" class="form-control" value="' . $row['OtherSchool'] . '"></p>
                <p>SCU\'s course code: <input type="text" name="localCourseCode" id="localCourseCode" class="form-control" value="' . $row['LocalCourseCode'] . '"></p>
                <p>Approved?</p>
                <input id="isApproved" name="isApproved" type="hidden" value="' . $row['IsApproved'] . '">
                <div class="dropdown">
                    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" id="isApprovedDropdown" data-toggle="dropdown">';
            if ($row['IsApproved'] == 1) {
                echo 'Yes';
            } else {
                echo 'No';
            }
            echo '<span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a onclick="$(\'#isApprovedDropdown\').html(\'Yes<span class=\\\'caret\\\'></span>\');$(\'#isApproved\').val(1).change();">Yes</a></li>
                        <li><a onclick="$(\'#isApprovedDropdown\').html(\'No<span class=\\\'caret\\\'></span>\');$(\'#isApproved\').val(0).change();">No</a></li>
                    </ul>
                </div>
                <p>Notes: </p>
                <textarea class="form-control" id="notes" maxlength="500">' . $row['Notes'] . '</textarea>
                <button class="btn btn-primary" id="submitButton" onclick="updateEquivalency(\''
                . EscapeStringForFunctionCall($row['OtherCourseCode']) . '\', \''
                . EscapeStringForFunctionCall($row['OtherSchool']) . '\', \''
                . EscapeStringForFunctionCall($row['LocalCourseCode']) . '\', '
                . $row['IsApproved'] . ', \''
                . EscapeStringForFunctionCall($row['ApprovedBy'])
                . '\');">Submit</button>
            </div>
            <div id="alertSection"></div>
            ';
        }
    }
?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="well">
                        <h1><a href="SoftwareEngineeringProject.php">Back to home</a></h1>
                    </div>
                </div>
            </div>
        </div>
    </body>

    <script>

    /**
    * Deletes an equivalency.
    */
    deleteEquivalency = function(otherCourseCode,
            otherSchool,
            localCourseCode,
            isApproved,
            approvedBy) {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == XMLHttpRequest.DONE && this.status == 200) {
                window.location = "SoftwareEngineeringProject.php";
            }
        }
        xhttp.open("GET", "RemoveEquivalency.php?otherCourseCode=" + otherCourseCode
            + "&otherSchoolName=" + otherSchool
            + "&localCourseCode=" + localCourseCode
            + "&isApproved=" + isApproved
            + "&approvedBy=" + approvedBy);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send();
    }


    /**
    * Update an equivalency.
    */
    updateEquivalency = function(originalOtherCourseCode,
            originalOtherSchool,
            originalLocalCourseCode,
            originalIsApproved,
            originalApprovedBy) {
        otherCourseCode = $('#otherCourseCode').val();
        otherSchool = $('#otherSchoolName').val();
        localCourseCode = $('#localCourseCode').val();
        isApproved = $('#isApproved').val();
        notes = $('#notes').val();
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == XMLHttpRequest.DONE && this.status == 200) {
                $('#alertSection').html(this.responseText);
                window.location = "ViewEquivalency.php?"
                    + "otherCourseCode=" + otherCourseCode
                    + "&otherSchoolName=" + otherSchool
                    + "&localCourseCode=" + localCourseCode
                    + "&isApproved=" + isApproved
                    + "&approvedBy=<?php echo $_SESSION['realName']?>";
            }
        }
        xhttp.open("POST", "ModifyEquivalency.php");
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("otherCourseCode=" + otherCourseCode
            + "&originalOtherCourseCode=" + originalOtherCourseCode
            + "&otherSchoolName=" + otherSchool
            + "&originalOtherSchool=" + originalOtherSchool
            + "&localCourseCode=" + localCourseCode
            + "&originalLocalCourseCode=" + originalLocalCourseCode
            + "&isApproved=" + isApproved
            + "&originalIsApproved=" + originalIsApproved
            + "&originalApprovedBy=" + originalApprovedBy
            + "&notes=" + notes);
    }

    </script>
</html>
