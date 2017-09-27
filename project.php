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
              <h1>Queries:</h1>
              <p>Other school's name: <input type="text" id="otherSchoolName" class="form-control" placeholder="School name"></p>
              <p>Other school's course code: <input type="text" id="otherCourseCode" class="form-control" placeholder="Course code"></p>
              <p>SCU's course code: <input type="text" id="localCourseCode" class="form-control" placeholder="Course code"></p>
            </div>
            <div class="well">
              <p><button class="btn btn-default" onclick="$('#newEntryFields').toggle();" type="button">Create new entry</button></p>
              <div id="newEntryFields" style="display: none">
                <p>Other school's course code: <input type="text" name="otherCourseCode" class="form-control" placeholder="Course code"></p>
                <p>Other school's name: <input type="text" name="otherSchoolName" class="form-control" placeholder="School name"></p>
                <p>SCU's course code: <input type="text" name="localCourseCode" class="form-control" placeholder="Course code"></p>
                <p>Approved?</p>
                <input id="isApproved" name="isApproved" type="hidden">
                <div class="dropdown">
                  <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" id="isApprovedDropdown" data-toggle="dropdown">
                    Approved?
                    <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu">
                    <li><a onclick="$('#isApprovedDropdown').html('Yes<span class=\'caret\'></span>');$('#isApproved').val(1);">Yes</a></li>
                    <li><a onclick="$('#isApprovedDropdown').html('No<span class=\'caret\'></span>');$('#isApproved').val(0);">No</a></li>
                  </ul>
                </div>
                <p>Approver's name: <input type="text" name="approverName" class="form-control" placeholder="Approver's name"></p>
                <button class="btn btn-primary">Submit</button>
              </div>
            </div>
          </div>
        </form>
        </div>
      <div class="col-md-9">
        <div class="well">
<?php
  # Function for displaying the results of a MySQL query result object nicely,
  # using Bootstrap's table styling.
  function DisplayResults($results) {
    # Check if the query failed.
    if(!$results) {
      echo '<h1>Something went wrong with the query.</h1>';
      return;
    }

    # Output the amount of results.
    if($results->fetch_assoc() == NULL or $results->num_rows == 0) {
      echo '<h1 id="numResults">No results.</h1>';
      return;
    }
    elseif ($results->num_rows == 1) {
      echo '<h1 id="numResults">1 result:</h1>';
    }
    else {
      echo '<h1 id="numResults">' . $results->num_rows . ' results:</h1>';
    }
    echo '<table class="table table-striped table-responsive"><thead><tr>';

    # Iterate through and print the names of each field, as the table headers.
    $results->data_seek(0);
    foreach($results->fetch_assoc() as $key => $value) {
      $key = str_replace("_", " ", $key);
      echo '<th>' . $key . '</th>' . "\n";
    }
    echo '</tr></thead><tbody id="results">';

    # Iterate through and print the contents of each field.
    $results->data_seek(0);
    while($row = $results->fetch_assoc()) {
      echo '<tr>';
      foreach($row as $value) {
        if ($value === '0') {
          $value = 'No';
        } else if ($value === '1') {
          $value = 'Yes';
        }
        echo '<td>' . $value . '</td>' . "\n";
      }
      echo '</tr>';
    }

    # Terminate the table.
    echo '</tbody></table>';
  }

  $query = "SELECT * FROM coen174lProject";

  if ($conn != false) {
    $result = $conn->query($query);
    DisplayResults($result);
  } else {
    echo 'Could not retrieve results.';
  }
?>
        </div>
      </div>
    </div>
  </div>

  <script>
  // Get all the rows from the table.
  var $tableEntries = $('#results tr');

  /**
   * Updates the table of results to only contain results that match what is entered in the search boxes.
   */
  updateRows = function() {
    // Get the search terms.
    var otherSchoolNamesSearch = $.trim($('#otherSchoolName').val()).replace(/ +/g, ' ').toLowerCase();
    var otherCourseCodesSearch = $.trim($('#otherCourseCode').val()).replace(/ +/g, ' ').toLowerCase();
    var localCourseCodesSearch = $.trim($('#localCourseCode').val()).replace(/ +/g, ' ').toLowerCase();

    // Filter the table entries so that only ones that match the search terms are visible.
    $tableEntries.show().filter(function() {
      // For each particular row, split the row up into an array, where each entry is a column in the table.
      var columns = $.trim($(this).text().split("\n")).split(",");
      var otherCourseCode = $.trim(columns[0]).toLowerCase();
      var otherSchoolName = $.trim(columns[1]).toLowerCase();
      var localCourseCode = $.trim(columns[2]).toLowerCase();

      // Check to see if any of the search terms do not match.  If so, return true, so we hide all of the
      // entries that are not a match.
      var otherSchoolNameMatches = !~otherSchoolName.indexOf(otherSchoolNamesSearch);
      var otherCourseCodeMatches = !~otherCourseCode.indexOf(otherCourseCodesSearch);
      var localCourseCodeMatches = !~localCourseCode.indexOf(localCourseCodesSearch);
      return otherSchoolNameMatches || otherCourseCodeMatches || localCourseCodeMatches;
    }).hide();

    // Update the number of results of the search.
    var numOfVisibleRows = $('#results tr:visible').length;
    console.log("numResults: " + numOfVisibleRows);
    if (numOfVisibleRows < 1) {
        $('#numResults').html("No results");
    } else if (numOfVisibleRows == 1) {
        $('#numResults').html("1 result");
    } else {
        $('#numResults').html(numOfVisibleRows + " results");
    }
  }

  // Any time one of the search terms is changed, update the search results.
  $('#otherSchoolName').keyup(updateRows);
  $('#otherCourseCode').keyup(updateRows);
  $('#localCourseCode').keyup(updateRows);
  </script>
  </body>
</html>
<?php
  // Close the DB connection.
  $conn->close();
?>
