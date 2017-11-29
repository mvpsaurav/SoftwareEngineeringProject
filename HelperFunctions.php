<?php
    // A helper function to display a bootstrap error alert with a custom
    // message.
    function EchoDismissableAlert($message) {
        echo "<div class='alert alert-danger alert-dismissible'>
        <button type='button' class='close' data-dismiss='alert'>&times;</button>
        $message
        </div>";
    }

    // A helper function to display a bootstrap success alert with a custom
    // message.
    function EchoDismissableSuccess($message) {
        echo "<div class='alert alert-success alert=dismissible'>
        <button type='button' class='close' data-dismiss='alert'>&times;</button>
        $message
        </div>";
    }

    // A helper function to display the panel on the home page that is shown
    // when the user is logged in.
    function EchoLoggedInPanel() {
        echo "<div class=\"well\">
            <p>Logged in as " . $_SESSION['realName'] . " (" . $_SESSION['username'] . ")</p>
            <p><button class=\"btn btn-warning\" onclick=\"logout();\" type=\"button\">Logout</button></p>
            <p><button class=\"btn btn-default\" onclick=\"$('#newEntryFields').toggle();\" type=\"button\">Create new entry</button></p>
            <div id=\"newEntryFields\" style=\"display: none\">
                <p>Outside school's course code: <input type=\"text\" name=\"otherCourseCode\" id=\"otherCourseCode\" class=\"form-control\" placeholder=\"Course code\"></p>
                <p>Outside school's name: <input type=\"text\" name=\"otherSchoolName\" id=\"otherSchoolName\" class=\"form-control\" placeholder=\"School name\"></p>
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
                <p>Notes: </p>
                <textarea class=\"form-control\" id=\"notes\" maxlength=\"500\"></textarea>
                <button class=\"btn btn-primary disabled\" id=\"submitButton\" onclick=\"addNewEquivalency();\">Submit</button>
            </div>
        </div>
        <div id=\"alertSection\"></div>";
        if (isset($_SESSION['loggedIn'])
                && $_SESSION['username'] == "admin") {
            echo "<div class=\"well\">
                <p><button class=\"btn btn-default\" onclick=\"$('#addFacultyUserSection').toggle();\" type=\"button\">Create new faculty user</button></p>
    	<div id=\"addFacultyUserSection\" style=\"display: none\">
    		<form action=\"AddFacultyUser.php\" method=\"POST\">
                        <div class=\"form-group\">
    						<p>Username: <input type=\"text\" class=\"form-control\" name=\"username\" id=\"username\" placeholder=\"Username\"></p>
                        </div>
                        <div class=\"form-group\">
                            <input type=\"hidden\" name=\"password\" id=\"password\">
                            <p>Password: <input type=\"password\" class=\"form-control\" name=\"unhashedPassword\" id=\"unhashedPassword\" placeholder=\"Password\"></p>
                        </div>
                        <div class=\"form-group\">
                            <p>Real name: <input type=\"text\" class=\"form-control\" name=\"realName\" id=\"realName\" placeholder=\"Real Name\"></p>
                        </div>
                        <button type=\"submit\" class=\"btn btn-primary\">Submit</button>
                    </form>
    	</div>
    		</div>";
        }
        echo "<script>
        $('#unhashedPassword').change(function() {
            $('#password').val(hash($('#unhashedPassword').val()));
        });

        /**
        * Inserts a new row into the equivalencies table.
        */
        addNewEquivalency = function() {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                document.getElementById(\"alertSection\").innerHTML = this.responseText;
                if (this.readyState == XMLHttpRequest.DONE && this.status == 200) {
                    if (xhttp.getResponseHeader(\"Success\") == \"true\") {
                        window.location.reload();
                    }
                }
            };
            xhttp.open(\"GET\", \"AddNewEquivalency.php?otherSchoolName=\" + $('#otherSchoolName').val()
                    + \"&otherCourseCode=\" + $('#otherCourseCode').val()
                    + \"&localCourseCode=\" + $('#localCourseCode').val()
                    + \"&isApproved=\" + $('#isApproved').val()
                    + \"&notes=\" + $('#notes').val());
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
    }

    // A helper function to display the panel on the home page that is shown
    // when the user is not logged in.
    function EchoNotLoggedInPanel() {
        echo "<div class=\"well\">
            <p><button class=\"btn btn-default\" onclick=\"$('#facultyLogin').toggle();\" type=\"button\">Faculty login</button></p>
            <div id=\"facultyLogin\" style=\"display:none\">
                <p>Username: <input type=\"text\" id=\"username\" class=\"form-control\" placeholder=\"Username\" onkeydown=\"if(event.keyCode == 13){ $('#facultyLoginButton').click(); }\"></p>
                <p>Password: <input type=\"password\" id=\"password\" class=\"form-control\" placeholder=\"Password\" onkeydown=\"if(event.keyCode == 13){ $('#facultyLoginButton').click(); }\"></p>
                <button class=\"btn btn-primary\" id=\"facultyLoginButton\" type=\"submit\" onclick=\"loginFacultyMember();\">Submit</button>
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
                      + \"&password=\" + hash($('#password').val()));
        }
        </script>";
    }

    // A very hacky helper function that properly escapes HTML tags / characters
    // when being fed into a javascript function.
    function EscapeStringForFunctionCall($string) {
        return str_replace("'", "\'", str_replace("\\", "\\\\", htmlentities($string)));
    }

    // Function for displaying the results of a MySQL query result object nicely,
    // using Bootstrap's table styling.
    function DisplayResults($results, $canDelete = false, $detailed = false) {
        // Check if the query failed.
        if(!$results) {
            echo '<h2>Something went wrong with the query.</h2>';
            return;
        }

        // Output the amount of results.
        if($results->fetch_assoc() == NULL or $results->num_rows == 0) {
            echo '<h2 id="numResults">No results.</h2>';
            return;
        }
        elseif ($results->num_rows == 1) {
            echo '<h2 id="numResults">1 result:</h2>';
        }
        else {
            echo '<h2 id="numResults">' . $results->num_rows . ' results:</h2>';
        }
        echo '<table class="table table-striped table-responsive"><thead><tr>';

        // Iterate through and print the names of each field, as the table headers.
        echo '<th>Other Course Code</th>
        <th>Outside School</th>
        <th>SCU\'s Course Code</th>
        <th>Approved?</th>
        <th>Approver</th>';
        if ($detailed == false) {
            echo '<th>Detailed view</th>';
        } else {
            echo '<th>Notes</th>';
        }
        if (isset($_SESSION['loggedIn'])
                && $_SESSION['loggedIn'] == true
                && $canDelete == true
                && isset($row['ApprovedBy'])
                && ($_SESSION['realName'] == $row['ApprovedBy']
                || $_SESSION['username'] == "admin")) {
            echo '<th>Delete this entry</th>';
        }
        echo '</tr></thead><tbody id="results">';

        // Iterate through and print the contents of each field.
        $results->data_seek(0);
        while($row = $results->fetch_assoc()) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['OtherCourseCode']) . '</td>
            <td>' . htmlspecialchars($row['OtherSchool']) . '</td>
            <td>' . htmlspecialchars($row['LocalCourseCode']) . '</td>
            <td>' . ($row['IsApproved'] == 1 ? 'Yes' : 'No') . '</td>
            <td>' . htmlspecialchars($row['ApprovedBy']) . '</td>';
            if ($detailed == false) {
                echo '<td><button class="btn btn-info" type="button" onclick="viewEquivalency(\'';
                echo EscapeStringForFunctionCall($row['OtherCourseCode']);
                echo '\', \'';
                echo EscapeStringForFunctionCall($row['OtherSchool']);
                echo '\', \'';
                echo EscapeStringForFunctionCall($row['LocalCourseCode']);
                echo '\', ';
                echo EscapeStringForFunctionCall($row['IsApproved']);
                echo ', \'';
                echo EscapeStringForFunctionCall($row['ApprovedBy']);
                echo '\')">
                View more info
                </button></td>';
            } else {
                echo '<td>' . htmlspecialchars($row['Notes']) . '</td>';
            }
            if (isset($_SESSION['loggedIn'])
                    && $_SESSION['loggedIn'] == true
                    && $canDelete == true
                    && isset($row['ApprovedBy'])
                    && ($_SESSION['realName'] == $row['ApprovedBy']
                    || $_SESSION['username'] == "admin")) {
                echo '<td><button class="btn btn-danger" type="button" onclick="deleteEquivalency(\'';
                echo EscapeStringForFunctionCall($row['OtherCourseCode']);
                echo '\', \'';
                echo EscapeStringForFunctionCall($row['OtherSchool']);
                echo '\', \'';
                echo EscapeStringForFunctionCall($row['LocalCourseCode']);
                echo '\', ';
                echo EscapeStringForFunctionCall($row['IsApproved']);
                echo ', \'';
                echo EscapeStringForFunctionCall($row['ApprovedBy']);
                echo '\')">
                Delete
                </button></td>';
            }
            echo '</tr>';
        }

        // Terminate the table.
        echo '</tbody></table>';
    }
?>
