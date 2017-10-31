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
?>
