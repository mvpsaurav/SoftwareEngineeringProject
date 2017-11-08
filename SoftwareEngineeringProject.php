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
                <div class="col-md-3">
                    <div class="form-group">
                        <div class="well">
                            <h1>Filter by:</h1>
                            <p>Other school's name: <input type="text" id="otherSchoolNameSearch" class="form-control" placeholder="School name"></p>
                            <p>Other school's course code: <input type="text" id="otherCourseCodeSearch" class="form-control" placeholder="Course code"></p>
                            <p>SCU's course code: <input type="text" id="localCourseCodeSearch" class="form-control" placeholder="Course code"></p>
                            <p>Approver: <input type="text" id="approvedBySearch" class="form-control" placeholder="Approver"></p>
                        </div>
<?php
    include 'HelperFunctions.php';
    session_start();
    if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == true) {
        EchoLoggedInPanel();
    } else {
        EchoNotLoggedInPanel();
    }
?>
                    </div>
                </div>
                <div class="col-md-9">
                    <div id="tableResults" class="well">
                    </div>
                </div>
            </div>
        </div>

    <script>
    /**
    * Goes to the detailed view of an equivalency.
    */
    viewEquivalency = function(occ, os, lcc, ia, ab) {
        window.location = "ViewEquivalency.php?otherCourseCode=" + occ
            + "&otherSchoolName=" + os
            + "&localCourseCode=" + lcc
            + "&isApproved=" + ia
            + "&approvedBy=" + ab;
    }

    /**
    * Updates the table of results to only contain results that match what is entered in the search boxes.
    */
    updateRows = function() {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == XMLHttpRequest.DONE && this.status == 200) {
                document.getElementById("tableResults").innerHTML = this.responseText;
            }
        };
        xhttp.open("GET", "EquivalenciesTable.php?otherCourseCode=" + $('#otherCourseCodeSearch').val()
                + "&otherSchoolName=" + $('#otherSchoolNameSearch').val()
                + "&localCourseCode=" + $('#localCourseCodeSearch').val()
                + "&approvedBy=" + $('#approvedBySearch').val());
        xhttp.send();
    }

    // Any time one of the search terms is changed, update the search results.
    $('#otherSchoolNameSearch').keyup(updateRows);
    $('#otherCourseCodeSearch').keyup(updateRows);
    $('#localCourseCodeSearch').keyup(updateRows);
    $('#approvedBySearch').keyup(updateRows);

    // Helper function that updates whether the submit button may be clicked every
    // time a user changes one of the inputs in the "create new entry" portion.
    updateSubmitButton = function() {
        if ($('#otherCourseCode').val() == "") {
            $('#submitButton').addClass("disabled").prop("type", "button");
        } else if ($('#otherSchoolName').val() == "") {
            $('#submitButton').addClass("disabled").prop("type", "button");
        } else if ($('#localCourseCode').val() == "") {
            $('#submitButton').addClass("disabled").prop("type", "button");
        } else if ($('#isApproved').val() == "") {
            $('#submitButton').addClass("disabled").prop("type", "button");
        } else {
            $('#submitButton').removeClass("disabled").prop("type", "submit");
        }
    }

    // Any time one of the submit fields is changed, update whether the submit
    // button can be clicked.
    $('#otherCourseCode').keyup(updateSubmitButton);
    $('#otherSchoolName').keyup(updateSubmitButton);
    $('#localCourseCode').keyup(updateSubmitButton);
    $('#isApproved').change(updateSubmitButton);

    // Run this when the page loads.
    window.onload = updateRows;

    // Bullshit client-side hashing
    hash = function(s) {
        return s.split("").reduce(function(a,b){a=((a<<5)-a)+b.charCodeAt(0);return a&a},0);
    }
    </script>
    </body>
</html>
