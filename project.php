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
        <form action="SoftwareEngineeringProject.php" method="post">
          <div class="form-group">
            <p><button name="b1" class="btn btn-default" onclick="document.getElementById('sub1').style.display = 'block';" type="button">Get rentals by branch</button></p>
              <div id="sub1" style="display: none">
                <input type="text" name="branch1" placeholder="Branch ID" class="form-control">
                <button name="b" value="1" class="btn btn-primary">Submit</button>
              </div>
            <p><button name="b" value="2" class="btn btn-default">Get supervisors and their properties</button></p>
            <p><button name="b3" class="btn btn-default" onclick="document.getElementById('sub3').style.display = 'block';" type="button">Get properties by owner</button></p>
              <div id="sub3" style="display: none">
                <input type="text" name="owner" placeholder="Owner's name" class="form-control">
                <button name="b" value="3" class="btn btn-primary">Submit</button>
              </div>
            <p><button name="b4" class="btn btn-default" onclick="document.getElementById('sub4').style.display = 'block';" type="button">Get properties by criteria</button></p>
              <div id="sub4" style="display: none">
                <input type="text" name="city4" placeholder="City" class="form-control">
                <input type="text" name="rooms" placeholder="Number of rooms" class="form-control">
                <input type="text" name="maxRent" placeholder="Maximum rent" class="form-control">
                <input type="text" name="minRent" placeholder="Minimum rent" class="form-control">
                <button name="b" value="4" class="btn btn-primary">Submit</button>
              </div>
            <p><button name="b" value="5" class="btn btn-default">Get amount of properties by branch</button></p>
            <p><button name="b6" class="btn btn-default" onclick="document.getElementById('sub6').style.display = 'block';" type="button">Create a lease agreement</button></p>
              <div id="sub6" style="display: none">
                <input type="text" name="rName" placeholder="Renter's name" class="form-control">
                <input type="text" name="homePhone" placeholder="Home phone" class="form-control">
                <input type="text" name="workPhone" placeholder="Work phone" class="form-control">
                <input type="text" name="cName" placeholder="Contact name" class="form-control">
                <input type="text" name="cPhone" placeholder="Contact phone" class="form-control">
                <input type="text" name="start" placeholder="Start day: YYYY-MM-DD" class="form-control">
                <input type="text" name="end" placeholder="End day: YYYY-MM-DD" class="form-control">
                <input type="text" name="rNo" placeholder="Rental number" class="form-control">
                <button name="b" value="6" class="btn btn-primary">Submit</button>
              </div>
            <p><button name="b7" class="btn btn-default" onclick="document.getElementById('sub7').style.display = 'block';" type="button">Get a lease agreement</button></p>
              <div id="sub7" style="display: none">
                <input type="text" name="renter" placeholder="Renter's name" class="form-control">
                <input type="text" name="rNo7" placeholder="Property Number" class="form-control">
                <button name="b" value="7" class="btn btn-primary">Submit</button>
              </div>
            <p><button name="b" value="8" class="btn btn-default">Get renters with multiple rentals</button></p>
            <p><button name="b9" class="btn btn-default" onclick="document.getElementById('sub9').style.display = 'block';" type="button">Get average rent for a city</button></p>
              <div id="sub9" style="display: none">
                <input type="text" name="city9" placeholder="City name" class="form-control">
                <button name="b" value="9" class="btn btn-primary">Submit</button>
              </div>
            <p><button name="b" value="10" class="btn btn-default" type="submit">Get leases that will expire in the next 2 months</button></p>
            <p><button name="b11" class="btn btn-default" onclick="document.getElementById('sub11').style.display = 'block';" type="button">Delete a property</button></p>
              <div id="sub11" style="display: none">
                <input type="text" name="rNo11" placeholder="Property number" class="form-control">
                <button name="b" value="11" class="btn btn-primary">Submit</button>
              </div>
          </div>
        </form>
      </div>
      </div>
      <div class="col-md-9">
        <div class="well">

<?php
  /*
  if(isset($_POST['b'])) {
    # Since the functionality we are required to implement can be a query or
    # an insert, and if it's a query, it can have a varying number of inputs,
    # I hardcoded the implementation for all 10 possible transactions, and
    # execute them based on a switch statement that determines which "submit"
    # button was clicked.
    #
    # Each case corresponds to the number of the functionality it implements,
    # according to the assignment.
    switch($_POST['b']) {
      case 1:
        $branch = $conn->real_escape_string($_POST['branch1']);
        $sql = "SELECT name AS Supervisor_Name,
          rNo AS Rental_Number,
          street AS Street,
          city AS City,
          zip AS Zip_Code
          FROM Property, Employees
          WHERE Property.eNo = Employees.eNo
          AND status = 'available'
          AND bNo = " . $branch . "
          ORDER BY name";
        $result = $conn->query($sql);
        DisplayResults($result);
        break;

      case 2:
        $sql = "SELECT name AS Manager_Name,
          owner AS Owner_Name,
          rNo AS Rental_Number,
          street AS Street,
          city AS City,
          zip AS Zip_Code,
          status AS Status
          FROM Property, Employees
          WHERE Property.eNo = Employees.eNo
          ORDER BY name";
        $result = $conn->query($sql);
        DisplayResults($result);
        break;

      case 3:
        $owner = $conn->real_escape_string($_POST['owner']);
        $sql = "SELECT Property.owner AS Owner,
          fees AS Fees,
          bNo AS Branch_Number,
          rNo AS Rental_Number,
          property.street AS Street,
          property.city AS City,
          property.zip AS Zip_Code,
          rent AS Rent,
          status AS Status
          FROM Property
          INNER JOIN PropertyOwner
          ON Property.owner = PropertyOwner.name
          INNER JOIN Employees
          ON Property.eNo = Employees.eNo
          WHERE Property.owner = '" . $owner . "'
          ORDER BY property.owner";
        $result = $conn->query($sql);
        DisplayResults($result);
        break;

      case 4:
        $city = $conn->real_escape_string($_POST['city4']);
        $rooms = $conn->real_escape_string($_POST['rooms']);
        $maxRent = $conn->real_escape_string($_POST['maxRent']);
        $minRent = $conn->real_escape_string($_POST['minRent']);
        $sql = "SELECT rNo AS Rental_Number,
            owner AS Owner,
            name AS Manager,
          noOfRooms AS Rooms,
          rent AS Rent,
          street AS Street,
          city AS City,
          zip AS Zip_Code
          FROM Property
          INNER JOIN Employees
          ON Property.eNo = Employees.eNo
          WHERE status = 'available'";
        if($city) {
          $sql = $sql . " AND city = '" . strtolower($city) . "'";
        }
        if($rooms) {
          $sql = $sql . " AND noOfRooms = " . $rooms;
        }
        if($maxRent) {
          $sql = $sql . " AND rent <= " . $maxRent;
        }
        if($minRent) {
          $sql = $sql . " AND rent >= " . $minRent;
        }
        $result = $conn->query($sql);
        DisplayResults($result);
        break;

      case 5:
        $sql = "SELECT Branch.bNo AS Branch_Number,
          Branch.street AS Street,
          Branch.city AS City,
          Branch.zip AS Zip_Code,
          COUNT(rNo) AS Number_Of_Properties
          FROM Property
          INNER JOIN Employees
          ON Property.eNo = Employees.eNo
          INNER JOIN Branch
          ON Employees.bNo = Branch.bNo
          GROUP BY Branch.bNo";

        $result = $conn->query($sql);
        DisplayResults($result);
        break;

      case 6:
        $rName = $conn->real_escape_string($_POST['rName']);
        $homePhone = $conn->real_escape_string($_POST['homePhone']);
        $workPhone = $conn->real_escape_string($_POST['workPhone']);
        $cName = $conn->real_escape_string($_POST['cName']);
        $cPhone = $conn->real_escape_string($_POST['cPhone']);
        $start = $conn->real_escape_string($_POST['start']);
        $end = $conn->real_escape_string($_POST['end']);
        $rNo = $conn->real_escape_string($_POST['rNo']);
        if(!$rName || !$homePhone || !$workPhone || !$cName || !$cPhone
        || !$start || !$end || !$rNo) {
          echo '<h3>Failed to insert.  Please check the information, and try
          again.</h3>';
          break;
        }

        $sql = "SELECT eNo
          FROM Property
          WHERE rNo = " . $rNo;
        $result = $conn->query($sql);
        $data = $result->fetch_assoc();
        if(!$data) {
          echo 'No employee with that name.  Try again.';
        }
        $eNo = $data['eNo'];

        $sql = "INSERT INTO Lease
          VALUES (
          '" . $rName . "',
          '" . $homePhone . "',
          '" . $workPhone . "',
          '" . $cName . "',
          '" . $cPhone . "',
          '" . $start . "',
          '" . $end . "',
          0,
          0,
          " . $eNo . ",
          " . $rNo . "
          )";

        $result = $conn->query($sql);
        if($result) {
          echo '<h1>Lease was successfully created.</h1>';
        }
        else {
          echo '<h1>Lease creation failed.  Check your inputs, and try again.</h1>';
          break;
        }

        $sql = "SELECT rNo AS Rental_Number,
          owner AS Owner,
          street AS Street,
          city AS City,
          zip AS Zip_Code,
          status AS Status
          FROM Property
          WHERE rNo = " . $rNo;
        $result = $conn->query($sql);
        DisplayResults($result);
        break;

      case 7:
        $renter = $conn->real_escape_string($_POST['renter']);
        $rNo = $conn->real_escape_string($_POST['rNo7']);
        $sql = "SELECT rName AS Renter_Name,
          hPhone AS Home_Phone,
          wPhone AS Work_Phone,
          cName AS Contact_Name,
          cPhone AS Contact_Phone,
          Lease.startDate AS Start_Date,
          endDate AS End_Date,
          deposit AS Deposit,
          rent AS Rent,
          name AS Supervisor,
          rNo AS Rental_Number
          FROM Lease
          INNER JOIN Employees
          ON Lease.eNo = Employees.eNo
          WHERE 1 = 1";
        if($renter) {
          $sql = $sql . " AND rName = '" . $renter . "'";
        }
        if($rNo) {
          $sql = $sql . " AND rNo = " . $rNo;
        }
        $result = $conn->query($sql);
        DisplayResults($result);
        break;

      case 8:
        $sql = "SELECT rName AS Renter,
          COUNT(DISTINCT rNo) AS Number_Of_Properties
          FROM Lease
          GROUP BY rName
          HAVING COUNT(DISTINCT rNo) >= 2";
        $result = $conn->query($sql);
        DisplayResults($result);
        break;

      case 9:
        $city = $conn->real_escape_string($_POST['city9']);
        $sql = "SELECT city AS City,
          AVG(rent) AS Average_Rent,
          COUNT(*) AS Number_Of_Properties
          FROM Property
          WHERE city = '" . $city . "'";
        $result = $conn->query($sql);
        $data = $result->fetch_assoc();
        if($data['Average_Rent'] != NULL) {
          $result->data_seek(0);
        }
        DisplayResults($result);
        break;

      case 10:
        $sql = "SELECT owner AS Owner,
          rName AS Renter,
          Property.rNo AS Rental_Number,
          street AS Street,
          city AS City,
          zip AS Zip,
          endDate AS Lease_End_Date
          FROM Property
          INNER JOIN Lease
          ON Property.rNo = Lease.rNo
          WHERE TIMESTAMPDIFF(DAY, CURDATE(), Lease.endDate) <= 60
          AND TIMESTAMPDIFF(DAY, CURDATE(), Lease.endDate) >= 0
          ORDER BY endDate";
        $result = $conn->query($sql);
        DisplayResults($result);
        break;

      case 11:
        $rNo = $conn->real_escape_string($_POST['rNo11']);
        $sql = "DELETE FROM Property
          WHERE rNo = " . $rNo;
        $result = $conn->query($sql);
        if($result) {
          echo '<h1>Property was successfully deleted.</h1>';
        }
        else {
          echo '<h1>Property deletion failed.  Check your inputs, and try again.</h1>';
        }
        break;

      default:
    }
  }
  else {
    echo '<h1>Results:</h1>';
  }

  $conn->close();
  */

  ini_set('display_errors','On');
  error_reporting(E_ALL);

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
