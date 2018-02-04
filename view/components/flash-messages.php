<?php
/**
 * Created by PhpStorm.
 * User: Vitalii
 * Date: 2018-02-01
 * Time: 21:48
 */
if (isset($message)) {
    if ($message['Status'] == 'Success') {
        $label = 'alert-success';
    } else {
        $label = 'alert-danger';
    }
    echo "<div class='alert " . $label . "'><a href'#' class='close' data-dismiss='alert' aria-label='close'>&times;</a><strong>" . $message['Status'] . "!</strong> " . $message['Message'] . "    </div>";
}
?>