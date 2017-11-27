<?php
    session_start();
    if(isset($_SESSION['loggedIn'])
            && $_SESSION['loggedIn'] == true) {
        session_unset();
        session_destroy();
    }
?>
