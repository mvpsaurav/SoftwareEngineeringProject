<?php
    //session_id($_POST['sid']);
    session_start();
    if(isset($_SESSION['loggedIn'])
            && $_SESSION['loggedIn'] == true) {
        session_unset();
        session_destroy();
    }
    http_response_code(404);
?>
