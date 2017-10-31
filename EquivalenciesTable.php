<?php
    // Initalize the DB connection.
    $db_host = "dbserver.engr.scu.edu";
    $db_user = "cwalther";
    $db_pass = "plaintextAF";
    $db_name = "sdb_cwalther";
    $conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

    if ($conn != false) {
        if (isset($_GET['otherSchoolName'])) {
            $otherSchoolName = $conn->real_escape_string($_GET['otherSchoolName']);
        } else {
            $otherSchoolName = "";
        }
        if (isset($_GET['otherCourseCode'])) {
            $otherCourseCode = $conn->real_escape_string($_GET['otherCourseCode']);
        } else {
            $otherCourseCode = "";
        }
        if (isset($_GET['localCourseCode'])) {
            $localCourseCode = $conn->real_escape_string($_GET['localCourseCode']);
        } else {
            $localCourseCode = "";
        }

        $query = "SELECT * FROM COEN174CourseEquivalencies "
                . "WHERE otherSchool LIKE '%" . $otherSchoolName . "%' "
                . "AND otherCourseCode LIKE '%" . $otherCourseCode . "%' "
                . "AND localCourseCode LIKE '%" . $localCourseCode . "%'";

        $result = $conn->query($query);
        DisplayResults($result);
    } else {
        EchoDismissableAlert('Could not retrieve results.');
    }

    # Utility function for doing a regex match that will a split a string into its
    # parts assuming the parts are delimited by camelCasing or by underscores.
    function MatchByUnderscoresOrCamelCase($pattern, $string) {
        $matches = [];
        preg_match_all($pattern, $string, $matches, PREG_OFFSET_CAPTURE);
        $matches = $matches[0];
        $words = [];
        $firstWord = substr($string, 0, $matches[0][1]);
        array_push($words, $firstWord);
        $end = strlen($firstWord);
        for ($i = 0; $i < count($matches) - 1; $i++) {
            if ($matches[$i][1] == "_") {
                $start = $matches[$i][1] + 1;
            } else {
                $start = $matches[$i][1];
            }
            $end = $matches[$i + 1][1];
            array_push($words, substr($string, $start, $end - $start));
        }
        if (count($matches) > 0) {
            array_push($words, substr($string, $end));
        }
        return $words;
    }

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
            # Split the name of the column by either underscores or camelCasing.
            $pattern = "/(?<=[a-z])(?=[A-Z])|_/";
            $words = MatchByUnderscoresOrCamelCase($pattern, $key);
            echo '<th>';
            foreach ($words as $word) {
                echo ucwords($word) . ' ';
            }
            echo "</th>\n";
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
?>
