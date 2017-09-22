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
      echo '<h1>No results.</h1>';
      return;
    }
    elseif ($results->num_rows == 1) {
      echo '<h1>1 result:</h1>';
    }
    else {
      echo '<h1>' . $results->num_rows . ' results:</h1>';
    }
    echo '<table class="table table-striped table-responsive"><thead><tr>';

    # Iterate through and print the names of each field, as the table headers.
    $results->data_seek(0);
    foreach($results->fetch_assoc() as $key => $value) {
      $key = str_replace("_", " ", $key);
      echo '<th>' . $key . '</th>';
    }
    echo '</tr></thead><tbody>';

    # Iterate through and print the contents of each field.
    $results->data_seek(0);
    while($row = $results->fetch_assoc()) {
      echo '<tr>';
      foreach($row as $value) {
        echo '<td>' . $value . '</td>';
      }
      echo '</tr>';
    }

    # Terminate the table.
    echo '</tbody></table>';
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

  </head>

  <body>
	<div class="container-fluid">
      <div class="row">
        <div class="col-md-3">
          <div class="well">
            <h1>Queries:</h1>
            <div class="form-group">
              <p>Other school's name: <input type="text" name="otherSchoolName" class="form-control" placeholder="School name"></p>
              <p>Other school's course code: <input type="text" name="otherCourseCode" class="form-control" placeholder="Course code"></p>
              <p>SCU course code: <input type="text" name="localCourseName" class="form-control" placeholder="Course code"></p>
            </div>
          </div>
        </div>
      <div class="col-md-9">
        <div class="well">
<?php
  $db_host = "dbserver.engr.scu.edu";
  $db_user = "cwalther";
  $db_pass = "plaintextAF";
  $db_name = "sdb_cwalther";

  $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

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
  </body>
</html>
