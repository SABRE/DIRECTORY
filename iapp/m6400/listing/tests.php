<?php

//$now = mktime(0, 120, 0, date("m")  , date("d"), date("Y"));

$now = time();


echo "<br><br>".date("F j, Y, g:i a", $now);
?>