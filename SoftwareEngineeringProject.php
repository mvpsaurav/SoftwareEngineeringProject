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
                        </div>
<?php
    session_start();
    if (isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == true) {
        echo "<div class=\"well\">
            <p>Logged in as " . $_SESSION['realName'] . " (" . $_SESSION['username'] . ")</p>
            <p><button class=\"btn btn-info\" onclick=\"logout();\" type=\"button\">Logout</button></p>
            <p><button class=\"btn btn-default\" onclick=\"$('#newEntryFields').toggle();\" type=\"button\">Create new entry</button></p>
            <div id=\"newEntryFields\" style=\"display: none\">
                <p>Other school's course code: <input type=\"text\" name=\"otherCourseCode\" id=\"otherCourseCode\" class=\"form-control\" placeholder=\"Course code\"></p>
                <p>Other school's name: <input type=\"text\" name=\"otherSchoolName\" id=\"otherSchoolName\" class=\"form-control\" placeholder=\"School name\"></p>
                <p>SCU's course code: <input type=\"text\" name=\"localCourseCode\" id=\"localCourseCode\" class=\"form-control\" placeholder=\"Course code\"></p>
                <p>Approved?</p>
                <input id=\"isApproved\" name=\"isApproved\" type=\"hidden\">
                <div class=\"dropdown\">
                    <button class=\"btn btn-default dropdown-toggle\" type=\"button\" data-toggle=\"dropdown\" id=\"isApprovedDropdown\" data-toggle=\"dropdown\">
                        Select a value
                        <span class=\"caret\"></span>
                    </button>
                    <ul class=\"dropdown-menu\">
                        <li><a onclick=\"$('#isApprovedDropdown').html('Yes<span class=\'caret\'></span>');$('#isApproved').val(1).change();\">Yes</a></li>
                        <li><a onclick=\"$('#isApprovedDropdown').html('No<span class=\'caret\'></span>');$('#isApproved').val(0).change();\">No</a></li>
                    </ul>
                </div>
                <p>Approver's name: <input type=\"text\" name=\"approverName\" id=\"approverName\" class=\"form-control\" placeholder=\"Approver's name\"></p>
                <button class=\"btn btn-primary disabled\" id=\"submitButton\" onclick=\"addNewEquivalency();\">Submit</button>
            </div>
        </div>
        <div id=\"alertSection\"></div>
        <script>
        /**
        * Inserts a new row into the equivalencies table.
        */
        addNewEquivalency = function() {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == XMLHttpRequest.DONE && this.status == 200) {
                    if (this.responseText == \"Success\") {
                        location.reload(true);
                    } else {
                        document.getElementById(\"alertSection\").innerHTML = this.responseText;
                    }
                }
            };
            xhttp.open(\"GET\", \"AddNewEquivalency.php?otherSchoolName=\" + $('#otherSchoolName').val()
                    + \"&otherCourseCode=\" + $('#otherCourseCode').val()
                    + \"&localCourseCode=\" + $('#localCourseCode').val()
                    + \"&isApproved=\" + $('#isApproved').val()
                    + \"&approverName=\" + $('#approverName').val());
            xhttp.send();
        }

        /**
        * Logs out the faculty user.
        */
        logout = function() {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == XMLHttpRequest.DONE && this.status == 200) {
                    location.reload(true);
                }
            };
            xhttp.open(\"GET\", \"Logout.php\");
            xhttp.send();
        }
        </script>";
    } else {
        echo "<div class=\"well\">
            <p><button class=\"btn btn-default\" onclick=\"$('#facultyLogin').toggle();\" type=\"button\">Faculty login</button></p>
            <div id=\"facultyLogin\" style=\"display:none\">
                <p>Username: <input type=\"text\" id=\"username\" class=\"form-control\" placeholder=\"Username\"></p>
                <p>Password: <input type=\"password\" id=\"password\" class=\"form-control\" placeholder=\"Password\"></p>
                <button class=\"btn btn-primary\" id=\"facultyLoginButton\" onclick=\"loginFacultyMember();\">Submit</button>
            </div>
        </div>
        <div id=\"loginAlertSection\"></div>
        <script>
        /**
        * Logs in a faculty member.
        */
        loginFacultyMember = function() {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                if (this.readyState == XMLHttpRequest.DONE && this.status == 200) {
                    document.getElementById(\"loginAlertSection\").innerHTML = this.responseText;
                    if (xhttp.getResponseHeader(\"LoggedIn\") == \"true\") {
                        window.location.reload();
                    }
                }
            };
            xhttp.open(\"POST\", \"AttemptFacultyLogin.php\");
            xhttp.setRequestHeader(\"Content-type\", \"application/x-www-form-urlencoded\");
            xhttp.send(\"username=\" + $('#username').val()
                      + \"&password=\" + $('#password').val());
        }
        </script>";
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
                + "&localCourseCode=" + $('#localCourseCodeSearch').val());
        xhttp.send();
    }

    // Any time one of the search terms is changed, update the search results.
    $('#otherSchoolNameSearch').keyup(updateRows);
    $('#otherCourseCodeSearch').keyup(updateRows);
    $('#localCourseCodeSearch').keyup(updateRows);

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
        } else if ($('#approverName').val() == "") {
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
    $('#approverName').keyup(updateSubmitButton);

    // Run this when the page loads.
    window.onload = updateRows;
    </script>
    </body>
</html>
