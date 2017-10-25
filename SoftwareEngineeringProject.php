<?php
  // Initalize the DB connection.
  $db_host = "dbserver.engr.scu.edu";
  $db_user = "cwalther";
  $db_pass = "plaintextAF";
  $db_name = "sdb_cwalther";
  $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

  // If the submit button was clicked, add a new entry to the table.
  if(isset($_POST['otherCourseCode'])
      && isset($_POST['otherSchoolName'])
      && isset($_POST['localCourseCode'])
      && isset($_POST['isApproved'])
      && isset($_POST['approverName'])) {
    $otherCourseCode = $conn->real_escape_string($_POST['otherCourseCode']);
    $otherSchoolName = $conn->real_escape_string($_POST['otherSchoolName']);
    $localCourseCode = $conn->real_escape_string($_POST['localCourseCode']);
    $isApproved = $conn->real_escape_string($_POST['isApproved']);
    $approverName = $conn->real_escape_string($_POST['approverName']);
    $sql = "INSERT INTO coen174lProject
      (otherCourseCode, otherSchool, localCourseCode, isApproved, approvedBy)
      VALUES('"
      . $otherCourseCode . "', '"
      . $otherSchoolName . "', '"
      . $localCourseCode . "', "
      . $isApproved . ", '"
      . $approverName . "')";
    $result = $conn->query($sql);
  }
  $conn->close();
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
<?php
  if (isset($result) && $result == false) {
      echo '<div class="alert alert-danger alert-dismissible" role="alert">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      There was a problem adding the entry.  Make sure you entered your values correctly and try again.
      </div>';
  }
?>
	<div class="container-fluid">
      <div class="row">
        <div class="col-md-3">
          <form action="SoftwareEngineeringProject.php" method="POST">
            <div class="form-group">
              <div class="well">
                <h1>Filter by:</h1>
                <p>Other school's name: <input type="text" id="otherSchoolNameSearch" class="form-control" placeholder="School name"></p>
                <p>Other school's course code: <input type="text" id="otherCourseCodeSearch" class="form-control" placeholder="Course code"></p>
                <p>SCU's course code: <input type="text" id="localCourseCodeSearch" class="form-control" placeholder="Course code"></p>
              </div>
              <div class="well">
                <p><button class="btn btn-default" onclick="$('#newEntryFields').toggle();" type="button">Create new entry</button></p>
                <div id="newEntryFields" style="display: none">
                  <p>Other school's course code: <input type="text" name="otherCourseCode" id="otherCourseCode" class="form-control" placeholder="Course code"></p>
                  <p>Other school's name: <input type="text" name="otherSchoolName" id="otherSchoolName" class="form-control" placeholder="School name"></p>
                  <p>SCU's course code: <input type="text" name="localCourseCode" id="localCourseCode" class="form-control" placeholder="Course code"></p>
                  <p>Approved?</p>
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
                  <p>Approver's name: <input type="text" name="approverName" id="approverName" class="form-control" placeholder="Approver's name"></p>
                  <button class="btn btn-primary disabled" id="submitButton" type="button">Submit</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      <div class="col-md-9">
        <div id="tableResults" class="well">
<!-- PUT TABLE RESULTS HERE -->
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
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("tableResults").innerHTML = this.responseText;
      }
    };
    xhttp.open("GET", "EquivalenciesTable.php?otherSchoolName=" + $('#otherSchoolNameSearch').val()
      + "&otherCourseCode=" + $('#otherCourseCodeSearch').val()
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
  </script>
  </body>
</html>
