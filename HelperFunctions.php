<?php
    function EchoDismissableAlert($message) {
        echo "<div class='alert alert-danger alert-dismissible'>
        <button type='button' class='close' data-dismiss='alert'>&times;</button>
        $message
        </div>";
    }

    function EchoDismissableSuccess($message) {
        echo "<div class='alert alert-success alert=dismissible'>
        <button type='button' class='close' data-dismiss='alert'>&times;</button>
        $message
        </div>";
    }

    function EchoLoggedInPanel() {
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
                    document.getElementById(\"alertSection\").innerHTML = this.responseText;
                    if (xhttp.getResponseHeader(\"Success\") == \"true\") {
                        window.location.reload();
                    }
                }
            };
            xhttp.open(\"GET\", \"AddNewEquivalency.php?otherSchoolName=\" + $('#otherSchoolName').val()
                    + \"&otherCourseCode=\" + $('#otherCourseCode').val()
                    + \"&localCourseCode=\" + $('#localCourseCode').val()
                    + \"&isApproved=\" + $('#isApproved').val());
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
                      + \"&password=\" + $('#password').val());
        }
        </script>";
    }
?>
